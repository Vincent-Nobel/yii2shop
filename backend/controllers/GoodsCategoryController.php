<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    //添加商品分类
    public function actionAdd()
    {
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类

                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);

        }
        return $this->render('add',['model'=>$model]);
    }

    //添加商品分类（ztree选择上级分类id）
    public function actionAdd2()
    {
//        var_dump(11);exit;
        $model=new GoodsCategory(['parent_id'=>0]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->parent_id){//判断是否添加一级分类
                //非一级分类
                $category=GoodsCategory::findOne(['id'=>$model->parent_id]);
                if ($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect('index');
        }
        $categories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add2',['model'=>$model,'categories'=>$categories]);
    }
    public function actionEdit($id)
    {
        $model=GoodsCategory::findOne(['id'=>$id]);
        if ($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            try{
                if($model->parent_id){//判断是否添加一级分类
                    //非一级分类
                    $category=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    if ($category){
                        $model->prependTo($category);
                    }else{
                        throw new HttpException(404,'上级分类不存在');
                    }
                }else{
                    if ($model->oldAttributes['parent_id']==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success','分类添加成功');
                return $this->redirect('index');
            }catch (Exception $e){
                $model->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
            }
        }
       $categories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add2',['model'=>$model,'categories'=>$categories]);
    }

    public function actionIndex()
    {
        $quer=GoodsCategory::find();
        $total=$quer->count();//总条数
        $pagesize=3;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pagesize
        ]);
        $models=$quer->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    public function actionDel($id)
    {
        $model=GoodsCategory::findOne(['id'=>$id]);
        if ($model==null){
            throw new HttpException('商品分类不存在');
        }
        if (!$model->isLeaf()){
            throw new ForbiddenHttpException('该分类下有子分类，无法删除');
        }
        $model->deleteWithChildren();
//        var_dump($model);exit;
        \Yii::$app->session->setFlash('success','该分类删除成功');
        return $this->redirect(['goods-category/index']);
    }

    //测试ztree
    public function actionZtree()
    {
        return $this->renderPartial('ztree');
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
