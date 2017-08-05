<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use backend\models\ArticleForm;
use yii\data\Pagination;
use yii\web\Request;

//use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    //搜索分页展示数据
    public function actionList()
    {
        //分页读取类别数据
//        $model = Article::find()->with('articleDetail');
//        $model=ArticleForm::className();
        $model=new ArticleForm();
        $name='';
//        var_dump($model);exit;
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            $name=$model->name;
//            var_dump($name);exit;
                $name="name like '%{$name}%' and ";
    }
//        var_dump($model->name);exit;
        $quer=Article::find()->where($name.'status != -1');
        $total=$quer->count();
        $pagination = new Pagination([
            'defaultPageSize' => 1,
            'totalCount' => $total,
        ]);
        $models = $quer->orderBy('sort desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'models' => $models,
           'pagination' => $pagination,
            'model'=>$model,
        ]);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    //添加
    public function actionAdd()
    {
        $model=new Article();
        $models=new ArticleDetail();
        $request=new Request();
        if ($request->isPost){
            $model->load(\Yii::$app->request->post());$models->load(\Yii::$app->request->post());
            $model->create_time=time();
            $model->save();
            $models->article_id=$model->id;
////            echo "<pre>";
////            var_dump($models);exit;
            $models->save();
            \Yii::$app->session->setFlash('success','文章添加成功');
            return $this->redirect(['article/list']);
        }
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }
    //修改文章
    public function actionEdit($id)
    {
        $model=Article::findOne(['id'=>$id]);
        $models=$model->articleDetail;
//        $models=new ArticleDetail();
//        $request=new Request();
        if ($model->load(\Yii::$app->request->post())
            && $models->load(\Yii::$app->request->post())
            && $model->validate()
            && $models->validate()){
//            var_dump($model);exit;
            $model->create_time=time();
            $model->save();
//            var_dump($model);exit;
            $models->article_id=$model->id;
////            echo "<pre>";
////            var_dump($models);exit;
            $models->save();
//            var_dump($model);exit;
            \Yii::$app->session->setFlash('success','文章添加成功');
            return $this->redirect(['article/list']);
        }
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }
    //del删除假
    public function actionDel($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status=-1;
        $model->create_time=time();
        $model->save();
        return $this->redirect(['article/list']);
    }
    //回收站
    public function actionInde()
    {
        //分页读取类别数据
//        $model = Article::find()->with('articleDetail');
//        $model=ArticleForm::className();
        $model=new ArticleForm();
        $name='';
//        var_dump($model);exit;
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());
            $name=$model->name;
//            var_dump($name);exit;
            $name="name like '%{$name}%' and ";
        }
//        var_dump($model->name);exit;
        $quer=Article::find()->where($name.'status = -1');
        $total=$quer->count();
        $pagination = new Pagination([
            'defaultPageSize' => 1,
            'totalCount' => $total,
        ]);
        $models = $quer->orderBy('sort desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('inde', [
            'models' => $models,
            'pagination' => $pagination,
            'model'=>$model,
        ]);
    }
    //删除真实
    public function actionDele($id)
    {
        $model=Article::findOne(['id'=>$id])->delete();
        return $this->redirect(['article/list']);
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
