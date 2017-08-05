<?php

namespace frontend\controllers;

use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use frontend\models\Address;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;

class MemberController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    //屏蔽yii2自带的头部展示框
//    public $layout=false;
    public function actionIndex()
    {
//        echo "11";exit;
        return $this->render('index');
    }
    //注册
    public function actionRegist(){
        $this->layout=false;
        $model=new Member();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $code2=\Yii::$app->session->get('code_'.$model->tel);
                if ($model->smscode==$code2){
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                    $model->auth_key=\Yii::$app->security->generateRandomString();
                    $model->save();//保存
                    return Json::encode(['status'=>true,'msg'=>'注册成功']);
                }else{
                    $model->addError('smsCode','验证码错误');
                }

            }else{
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('regist',['model'=>$model]);
    }
    //用户登录
    public function actionLogin(){
        $this->layout=false;
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //找到该用户
                $member=Member::findOne(['username'=>$model->username]);
                if($member){
                    //验证用户信息
                    if(\Yii::$app->security->validatePassword($model->password,$member->password_hash)){
//                        \Yii::$app->user->login($member,$model->remeber?3600*24:0);
                        //验证通过
                        $member->last_login_time=time();//保存最后登录时间
                        $member->last_login_ip=ip2long(\Yii::$app->request->userIP);//保存用户登录ip
                        \Yii::$app->user->login($member,$model->rememberMe ? 3600*24*7:0);
                        $member->save(false);
                        return Json::encode(['status'=>true,'msg'=>'登录成功']);
                    }else{
                        $model->addError('password','密码不正确');
                    }
                }else{
                    $model->addError('username','用户名不存在');
                }
            }else{
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    //添加用户地址
    public function actionAddress()
    {
        $this->layout=false;
        $model=new Address();
        $models=Address::find()->all();
        if ($model->load(\Yii::$app->request->post())){
            if ($model->validate()){
//                var_dump($model);exit;
                $model->member_id=\Yii::$app->user->getId();
                $model->save();
                return Json::encode(['status'=>true,'msg'=>'success']);
            }else{
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('address',['model'=>$model,'models'=>$models]);
    }
    //地址修改
    public function actionEdit($id)
    {
        $this->layout=false;
        $member_id=\Yii::$app->user->getId();
//        var_dump($member_id);exit;
        $models=Address::find()->where(['member_id'=>$member_id])->all();
//        echo "<pre>";
//        var_dump($models);exit;
        $model=Address::findOne(['id'=>$id]);
//        $model=Address::find()->where(['id'=>$id])->all();

        if ($model->load(\Yii::$app->request->post())){
            if ($model->validate()){
//                exit('返回');
//                var_dump($model);exit;
                $model->member_id=$member_id;
                $model->save();
                return Json::encode(['status'=>true,'msg'=>'success']);
            }else{
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('edit',['model'=>$model,'models'=>$models]);

    }
    //三级联动地址
    public function actionLocations($pid)
    {
        $rows=Locations::find()->asArray()->where(['parent_id'=>$pid])->all();
        return json_encode($rows);

    }
    //注册短信验证
    public function actionTestSms()
    {
        $code=rand(1000,9999);//验证码
        $tel=$_POST['tel'];//手机号接收
        $res=\Yii::$app->sms->setPhoneNumbers('18280180174')->setTemplateParam(['code'=>$code])->send();
//        var_dump($res);
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$tel,$code);
//        Config::load();
        // 加载区域结点配置
//        Config::load();
//        //此处需要替换成自己的AK信息
//        $accessKeyId = "LTAIbhfIddssDJzp";//参考本文档步骤2
//        $accessKeySecret = "VpCPIp9sqtK5ZH20vvpImyT5isFnUP";//参考本文档步骤2
//        //短信API产品名（短信产品名固定，无需修改）
//        $product = "Dysmsapi";
//        //短信API产品域名（接口地址固定，无需修改）
//        $domain = "dysmsapi.aliyuncs.com";
//        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
//        $region = "cn-hangzhou";
//        //初始化访问的acsCleint
//        $profile =DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
//        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
//        $acsClient= new DefaultAcsClient($profile);
////        DefaultAcsClient
//        $request = new SendSmsRequest();
////        $request = new Dysmsapi\Request\V20170525\SendSmsRequest;
////        DefaultAcsClient
//        //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
//        $request->setPhoneNumbers("18280180174");
//        //必填-短信签名
//        $request->setSignName("超哥网站");
//        //必填-短信模板Code
//        $request->setTemplateCode("SMS_80680008");
//        //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
//        $request->setTemplateParam("{\"code\":\"12345\"}");
//        //选填-发送短信流水号
//        $request->setOutId("1234");
//        //发起访问请求
//        $acsResponse = $acsClient->getAcsResponse($request);
//        var_dump($acsResponse);exit;
    }
    //定义验证码操作
    public function actions(){
        return [
            'captcha'=>[
//                CaptchaAction
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>4,
            ]
        ];
    }

}
