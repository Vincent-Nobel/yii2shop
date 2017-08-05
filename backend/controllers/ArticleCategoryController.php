<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        $mod=new ArticleCategory();
//        var_dump($mod);exit;
        $quer=ArticleCategory::find();
        $total=$quer->count();
        $pagesize=3;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pagesize
        ]);
        $models=$quer->limit($pager->limit)->offset($pager->offset)->all();
//        var_dump($models);exit;
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    public function actionAdd()
    {
        $model=new ArticleCategory();
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());//加载编单数据
            if ($model->validate()){
                $model->save();
                $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id)
    {
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());//加载编单数据
            if ($model->validate()){
                $model->save();
                $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id)
    {
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['article-category/index']);
    }
    public function actionInde()
    {
//        var_dump(11);exit;
        $quer=ArticleCategory::find()->where(['and','status = -1']);
        $total=$quer->count();
        $pagesize=3;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pagesize
        ]);
        $models=$quer->limit($pager->limit)->offset($pager->offset)->all();
//        var_dump($models);exit;
        return $this->render('inde',['models'=>$models,'pager'=>$pager]);
    }
    public function actionDele($id)
    {
        $model=ArticleCategory::findOne(['id'=>$id])->delete();
        return $this->redirect(['article-category/index']);

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
