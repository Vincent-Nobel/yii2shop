<?php

namespace frontend\controllers;

use frontend\models\Cart;
use backend\models\Goods;
use yii\helpers\Json;
use yii\web\Cookie;
use yii\web\HttpException;

class CartController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAddToCart($goods_id)
    {

        $amount = \Yii::$app->request->post('amount');
        //$goods_id = \Yii::$app->request->post('goods_id');
        if (!$amount){
            throw  new HttpException('amount不能为空!');
        }
        //未登录
        if(\Yii::$app->user->isGuest){
            //如果没有登录就存放在cookie中
            $cookies =\Yii::$app->request->cookies;
           

                //获取cookie中的购物车数据
                $cart=$cookies->get('cart');
                if(!$cart){
                    $carts = [$goods_id=>$amount];
                }else{
                    $carts = unserialize($cart->value);
                    if(isset($carts[$goods_id])){
                        //购物车中已经有该商品，数量累加
                        $carts[$goods_id] += $amount;
                    }else{
                        //购物车中没有该商品
                        $carts[$goods_id] = $amount;
                    }
                }
                //将商品id和商品数量写入cookie
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie([
                    'name'=>'cart',
                    'value'=>serialize($carts),
                    'expire'=>7*24*3600+time()
                ]);
                $cookies->add($cookie);
            }else{
                if(!$carts = Cart::findOne(['goods_id'=>$goods_id])){
                    $carts=new Cart();
                }
                //用户已登录，操作购物车数据表
                $member_id=\Yii::$app->user->id;
                $carts->load(\Yii::$app->request->get());

                $carts->goods_id=$goods_id;
                if($carts->isNewRecord){
                    $carts->amount =$amount;
                }else{
                    $carts->amount +=$amount;
                }
                $carts->member_id=$member_id;
                $carts->save();
                if(!$carts->save()){
                    var_dump($carts->getErrors());exit;
                }
            }
        return $this->redirect(['cart']);
    }
    //购物车
    public function actionCart()
    {
//        var_dump("11");exit;
        $this->layout=false;
//        用户未登录，购物车数据从cookie取出
        if (\Yii::$app->user->isGuest){
//            var_dump("11");exit;
            $cookies=\Yii::$app->request->cookies;
            $cart=$cookies->get('cart');
//            exit('返回1');
            if ($cart==null){
                $carts=[];
            }else{
                $carts=unserialize($cart->value);
            }
//            var_dump($carts);exit;
            //获取商品数据                              将数组的键取出
            $models=Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();
//            var_dump($models);exit;
        }else{
//            var_dump("11");exit;
//            var_dump(\Yii::$app->user->isGuest);exit;
//            exit('11');
            $member_id=\Yii::$app->user->id;
            $car=Cart::find()->where(['member_id'=>$member_id])->all();
            $goods_id=[];
            $carts=[];
            foreach ($car as $cart){
                $goods_id[]=$cart->goods_id;
                $carts[$cart->goods_id]=$cart->amount;
            }
            $models=Goods::find()->where(['in','id',$goods_id])->all();

//            return $this->render('cart',['models'=>$models,'carts'=>$carts]);
//            echo "<pre>";
//            var_dump($models);exit;
        }
//        echo "11";exit;
//        return $this->render('cart');
        return $this->render('cart',['models'=>$models,'carts'=>$carts]);
    }

    //修改购物车数据
    public function actionAjaxCart()
    {
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        //测试js
//        return Json::encode(['ss'=>$goods_id]);//
        //数据验证
        if(\Yii::$app->user->isGuest){
            $cookies =\Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies =\Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }else{
            $member_id=\Yii::$app->user->getId();
            $carts=Cart::findOne(['member_id'=>$member_id]);
            if (!$amount){
                return Json::encode(['s'=>'修改失败']);
            }else{
                    $carts->amount=$amount;
                }
                $carts->save();
                return 'success';
            }
    }
}
