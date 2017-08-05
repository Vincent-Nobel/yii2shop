<?php

namespace frontend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;

class GoodsController extends \yii\web\Controller
{
    public $layout=false;
    public $enableCsrfValidation=false;

    //网站首页
    public function actionIndex()
    {
//        $this->layout=false;
        $goods_category=GoodsCategory::find()->where(['parent_id'=>0])->all();
//        echo "<pre>";
//        var_dump($goods_category);exit;
        return $this->render('index',['goods_category'=>$goods_category]);
    }
    //商品列表
    public function actionList($id)
    {
        //商品
        $ate=GoodsCategory::findOne(['id'=>$id]);
        if ($ate->depth == 2){
            $goods=Goods::find()->where(['goods_category_id'=>$id])->all();
        }else{
            $is=$ate->leaves()->asArray()->column();
//            var_dump($is);exit;
            $goods=Goods::find()->where(['in','goods_category_id',$is])->all();
        }
//        echo "<pre>";
//        var_dump($goods);exit;
        return $this->render('list',['goods'=>$goods]);
    }
    //商品详情
    public function actionShow($id)
    {
//        var_dump($id);exit;
        $goods=Goods::find()->where(['id'=>$id])->all();
        $gallery=GoodsGallery::findAll(['goods_id'=>$id]);
        return $this->render('goods',['goods'=>$goods,'gallery'=>$gallery,'goods_id'=>$id]);
    }

}
