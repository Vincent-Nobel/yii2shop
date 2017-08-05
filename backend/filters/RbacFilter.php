<?php

/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/28
 * Time: 23:33
 */
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class RbacFilter extends \yii\base\ActionFilter
{
    public function beforeAction($action)
    {
        //判断用户哦是否登录，没登录引导登录界面
        if (\Yii::$app->user->isGuest){
//            return $action->controller->redirect(\Yii::$app->user->loginUrl);
            return $action->controller->redirect(['user/login']);
        }
        //判断用户是否有权限
        if (!\Yii::$app->user->can($action->uniqueId)){
            throw new ForbiddenHttpException('对不起，您没有该执行权限!');
        }
        return parent::beforeAction($action);
    }
}