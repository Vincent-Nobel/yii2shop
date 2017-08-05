<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\LoginForm;
use backend\models\User;
use backend\models\UserForm;
use yii\captcha\CaptchaAction;
use yii\helpers\ArrayHelper;

class UserController extends \yii\web\Controller
{
    public function actionLogin()
    {
        $model=new LoginForm();
        //判定是否是表单传递的数据
        if (\Yii::$app->request->isPost){//验证加载表单
            if ($model->load(\Yii::$app->request->post())){
                if ($model->validate()){//验证数据通过
                    $user=User::findOne(['username'=>$model->username]);
                    if ($user){
                        //密码明文与密文进行对比
                        if (\Yii::$app->security->validatePassword($model->password_hash,$user->password_hash)){
                            \Yii::$app->user->login($user,$model->remeber?3600*24:0);
//                            获取时间及ip
                            $user->last_login_time=time();
                            $user->last_login_ip=ip2long(\Yii::$app->request->getUserIP());
                            $user->save(false);
                            \Yii::$app->session->setFlash('success','登录成功');
                            return $this->redirect(['user/index']);
                        }else{
                            var_dump($user->addError('密码错误'));
                        }
                    }else{
                        var_dump($user->addError('用户不存在'));
                    }
                }
            }
        }

        return $this->render('login',['model'=>$model]);
    }
    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //测试
    public function actionUser()
    {
        //可以通过 Yii::$app->user 获得一个 User实例，
        $user = \Yii::$app->user;
        var_dump($user);
        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
        //var_dump($identity);
        $user = \Yii::$app->user;
        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;
//        var_dump($id);
        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($isGuest);
    }
    //定义验证码操作
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>4,
            ]
        ];
    }
    public function actionIndex()
    {
            $models=User::find()->all();

        return $this->render('index',['models'=>$models]);
    }
    //add
    public function actionAdd()
    {
        $model=new User();
//        $model->scenario = User::SCENARIO_EDIT;//指定当期场景为修改场景
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
//            exit('返回');
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->created_at=time();
//            $model->status=1;
//            exit('返回');
            $model->save(false);
//            exit('返回2');
//            $authManager->revokeAll($model->id);
            if(is_array($model->roles)){
//                exit('返回2');
                foreach ($model->roles as $roleName){
//                    exit('返回');
//                    var_dump($roleName);exit;
                    $authManager = \Yii::$app->authManager;
                    $role = $authManager->getRole($roleName);
//                    var_dump($role,$model);exit;
                    if ($role) $authManager->assign($role,$model->id);
                }
            }
//            exit('返回1');
            \Yii::$app->session->setFlash('success','用户添加成功');
            return $this->redirect(['user/index']);
        }else{
            $model->addError('有问题');
        }
        return $this->render('add',['model'=>$model]);
    }
    //edit
    public function actionEdit($id)
    {
//        var_dump($id);exit;
        $model=User::findOne(['id'=>$id]);
        $authManager = \Yii::$app->authManager;
//        $model->scenario = User::SCENARIO_EDIT;//指定当期场景为修改场景
//        $model->roles=ArrayHelper::map($authManager->getRolesByUser($id),'name','description');
        $model->roles=ArrayHelper::map($authManager->getRolesByUser($id),'name','name');
//        echo "<pre>";
        if (\Yii::$app->request->isPost ){
//            var_dump($model);exit;
            $model->load(\Yii::$app->request->post());
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->updated_at=time();
//            var_dump($model);exit;
            $authManager = \Yii::$app->authManager;
            $authManager->revokeAll($id);
            if(is_array($model->roles)) {
                foreach ($model->roles as $roleName) {
//                    var_dump($roleName);exit;
                    $role = $authManager->getRole($roleName);
                    if ($role) $authManager->assign($role, $id);
                }
            }
            $model->save(false);
//            exit('返回');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //delet
    public function actionDel($id)
    {
                 //判断是否登陆
        if (\Yii::$app->user->identity){
            $model=User::findOne(['id'=>$id])->delete();
//            exit('返回');
            return $this->redirect(['user/index']);
        }else{
            exit('未登录！');
        }


    }
    public function actionPwd($id)
    {
        if (\Yii::$app->user->identity){
            $model=new UserForm();
            $user=User::findOne(['id'=>$id]);
            //加载表单数据并validate验证
            if ($model->load(\Yii::$app->request->post()) && $model->validate()){
                //验证明文密文是否相同
                if (\Yii::$app->security->validatePassword($model->password,$user->password_hash)){
//                    var_dump($model->re_new_password,$model->new_password);exit;
                    //判断两次输入的密码是否相同
                    if ($model->re_new_password == $model->new_password){
//                    $password_hash = \Yii::$app->security->generatePasswordHash('明文密码');
//                        var_dump(11);exit;
                        //加密密码
                        $user->password_hash=\Yii::$app->security->generatePasswordHash($model->re_new_password);
                        //保存
                        $user->save(false);

                    }else{
                        $model->addError('密码不一致');
                        return $this->redirect(['user/index']);
                    }
                }
                \Yii::$app->session->setFlash('修改成功');
                return $this->redirect(['user/index']);
            }
        }else{
            exit('未登录！');
        }
        return $this->render('pwd',['model'=>$model]);
    }

//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacFilter::className(),
//                //规定哪些功能需要rbac
//                'only'=>['index','add','edit','del'],
////                'except'=>['login','captcha'],
//            ]
//        ];
//    }
}
