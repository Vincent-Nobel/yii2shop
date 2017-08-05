<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RbacForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    public function actionIndexPermission()
    {
        //获取所有权限
        $authManage=\Yii::$app->authManager;//实例化权限组件
        $models=$authManage->getPermissions();
        return $this->render('index-permission',['models'=>$models]);
    }
    //添加权限
    public function actionAddPermission()
    {
        $model=new PermissionForm();
//        添加场景
        $model->scenario=PermissionForm::SCENARIO_ADD;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
//        实例化权限组件
            $authManage=\Yii::$app->authManager;
//        创建权限
            $permission=$authManage->createPermission($model->name);
//        保存权限
            $permission->description=$model->description;
            //保存到数据表中
            $authManage->add($permission);
            \Yii::$app->session->setFlash('success','权限添加成功');
            return $this->redirect(['rbac/index-permission']);
        }

        return $this->render('add-permission',['model'=>$model]);
    }
    //修改权限
    public function actionEditPermission($name)
    {
        //检测权限是否存在
        $authManage=\Yii::$app->authManager;
        $permission=$authManage->getPermission($name);//存在与否
        if ($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        //实例化权限对象
        $model=new PermissionForm();
        //检测是否是post
        if (\Yii::$app->request->isPost){
            //加载表单数据并判断
            if ($model->load(\Yii::$app->request->post()) && $model->validate()){
                //保存表单数据
                $permission->name=$model->name;
                $permission->description=$model->description;
                //更新数据
                $authManage->update($name,$permission);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['rbac/index-permission']);
            }
        }else{
            $permission->name=$model->name;
            $permission->description=$model->description;
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDelPermission($name)
    {
        $authManage=\Yii::$app->authManager;//检测权限是否存在
        $permission=$authManage->getPermission($name);
//        var_dump($permission);exit;
        if ($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }else{
            $authManage->remove($permission);
            \Yii::$app->session->setFlash('success','权限删除成功');
            return $this->redirect(['rbac/index-permission']);
        }
    }
    //添加角色
    public function actionAddRole()
    {
        $model=new RoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager=\Yii::$app->authManager;//实例化权限组件
            $role=$authManager->createRole($model->name);//保存
            $role->description=$model->description;
//            var_dump($model);exit;
            $authManager->add($role);
            //给角色赋予权限
            //is_array是否有这个数组
            if (is_array($model->permissions)){
                //遍历数组
                foreach ($model->permissions as $permissionName){
                    if($permissionName && $permission=$authManager->getPermission($permissionName)){
                        //保存
                        $authManager->addChild($role,$permission);
                    }
                    //保存
                }
            }
            \Yii::$app->session->setFlash('success','角色添加成功');
            return $this->redirect(['index-role']);
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //role视图
    public function actionIndexRole()
    {
        $authManager=\Yii::$app->authManager;
        $models=$authManager->getRoles();
//        var_dump($models);exit;
        return $this->render('index-role',['models'=>$models]);
    }
    //role修改
    public function actionEditRole($name)
    {
//        var_dump($name);
//        echo "<pre>";
        //实例化角色对象
        $model=new RoleForm();
//        var_dump($name);
//        echo "<pre>";
        //取消角色和权限的关联
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
//        var_dump($name);
//        echo "<pre>";
        //getPermissionsByRole按角色获取权限
        $permissions=$authManager->getPermissionsByRole($name);
        //保存
        $model->name=$role->name;
        $model->description=$role->description;
//        var_dump($name);
//        echo "<pre>";
        //对象转换为数组
        $model->permissions=ArrayHelper::map($permissions,'name','name');
            if ($model->load(\Yii::$app->request->post()) && $model->validate()){
                $role->description=$model->description;
                var_dump($name);exit;
                $authManager->update($name,$role);//修改
                $authManager->removeChildren($role);//给角色赋予权限
                //is_array是否有这个数组
                if (is_array($model->permissions)){
                    //遍历数组
                    foreach ($model->permissions as $permissionName){
                        if($permissionName && $permission=$authManager->getPermission($permissionName)){
                            //保存
                            $authManager->addChild($role,$permission);
                        }
                        //保存
                    }
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['rbac/index-role']);
            }
        return $this->render('add-role',['model'=>$model]);
    }
    //删除角色
    public function actionDelRole($name)
    {
        $authManage=\Yii::$app->authManager;//检测权限是否存在
        $role=$authManage->getRole($name);
//        var_dump($role);exit;
        if ($role==null){

            throw new NotFoundHttpException('角色不存在');
        }else{

            $authManage->remove($role);
//            var_dump($role);exit;
            \Yii::$app->session->setFlash('success','角色删除成功');
            return $this->redirect(['rbac/index-role']);
        }
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}
