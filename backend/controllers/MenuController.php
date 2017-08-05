<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\helpers\ArrayHelper;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        var_dump(11);exit;
        $models=Menu::find()->where(['parent_id'=>0])->all();

        return $this->render('index',['models'=>$models]);
    }
    //添加菜单，二级分类
    public function actionAdd()
    {
//        var_dump(11);exit;
        $model=new Menu();
//        var_dump($model);exit;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }
//        $models=ArrayHelper::map(Menu::find()->all(),'id','url');
        return $this->render('add',['model'=>$model]);
    }
    //修改菜单 二级菜单
    public function actionEdit($id)
    {
      $model=Menu::findOne(['id'=>$id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->parent_id && !empty($model->chile)){
                $model->addError('parent_id','只能为顶级菜单');
            }else{
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['menu/index']);
            }

        }
//        $models=ArrayHelper::map(Menu::find()->all(),'id','url');
        return $this->render('add',['model'=>$model]);
    }
    //删除菜单 二级菜单
    public function actionDel($id)
    {
        $model=Menu::findOne(['id'=>$id]);
//        var_dump($model->parent_id);exit;
        if ($model->parent_id && !empty($model->chile)){
            $model->addError('parent_id','只能为顶级菜单');
        }else{
            $model->delete();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['menu/index']);
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
