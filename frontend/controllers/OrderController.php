<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\HttpException;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionChec()
    {
        $this->layout=false;
        $member_id=\Yii::$app->user->id;
        if (!\Yii::$app->user->isGuest){
            $address=Address::find()->where(['member_id'=>$member_id])->all();
            $order=new Order();
//        var_dump($address);exit;
            return $this->render('checking',['address'=>$address,'order'=>$order]);
        }else{
            var_dump("未登录");
        }

    }

    //订单页面
    public function actionOrder()
     {
         $this->layout=false;
//         var_dump("11");exit;
        //判断是否登录
             if (!\Yii::$app->user->isGuest){
                 $model = new Order();//新订单
//                 var_dump("11");exit;
                 $member_id=\Yii::$app->user->id;
                 $address=Address::find()->where(['member_id'=>$member_id])->all();
//                 var_dump($address);exit;
                 $carts = Cart::find()->where(['member_id'=>$member_id])->all();//购物车数据
//                 var_dump($carts);exit;
                 //开启事务
                 $transaction = \Yii::$app->db->beginTransaction();
                 if($model->load(\Yii::$app->request->post()) && $model->validate()) {
//                     $addres=Address::find()->where(['id'=>$model->address_id])->all();
//                     var_dump($addres);exit;
                     foreach ($address as $addre){
                     }
//                     var_dump($addre);exit;
                     foreach ($carts as $cart){
                     }
    //                 var_dump("11");exit;
                     try {
//                         echo "<pre>";
//                         var_dump($cart->goods->shop_price);exit;
//                         echo "<pre>";
//                        $naic= Order::$deliveries[$model->delivery_id]['name'];
//                         $naic='';
//                         var_dump($member_id);exit;
//                         $model=new Order();
                         $model->member_id=$member_id;
                         $model->name=$addre->username;
                         $provinceinfo = Locations::findOne($addre->province);
                         $model->province=$provinceinfo->name;
                         $cityinfo = Locations::findOne($addre->city);
                         $model->city=$cityinfo->name;
                         $areainfo = Locations::findOne($addre->area);
                         $model->area=$areainfo->name;
                         $model->address=$addre->address;
                         $model->tel=$addre->cell;
                         $model->delivery_name = Order::$deliveries[$model->delivery_id]['name'];
                         $model->delivery_price = Order::$deliveries[$model->delivery_id]['price'];
                         $model->payment_name=  Order::$payment[$model->payment_id]['name'];
                         $model->total=$cart->goods->shop_price * $cart->amount;
                         $model->status=1;
                         $model->create_time=time();
                         $model->save();
//                         var_dump($model->getErrors());exit;
//                         exit('返回1');
                         //继续保存订单商品表
                         //检查库存：购物车商品的数量和商品表库存对比，足够
                         if ($cart->amount <= $cart->goods->stock){
                             //（检查库存，如果足够）保存订单商品表
                             $goods = Goods::findOne($cart->goods_id);
                             //扣减对应商品的库存
                             $goods->stock= $cart->goods->stock - $cart->amount;

//                             var_dump($cart->goods->logo);exit;
                             $goods->save(false);
//                             var_dump($goods->updateAttributes(['stock'=>$goods->stock]),$goods->getErrors());exit;
                             //$order_goods的其他属性赋值
                             $order_goods = new OrderGoods();
                             $order_goods->order_id=$model->id;
                             $order_goods->goods_id=$cart->goods->id;
                             $order_goods->goods_name=$cart->goods->name;
                             $order_goods->logo=$cart->goods->logo;
                             $order_goods->price=$cart->goods->shop_price;
                             $order_goods->amount=$cart->amount;
                             $order_goods->total=$model->total;
                             $order_goods->save(false);
//                             exit('返回1');
                             return $this->redirect('goods/index');
                                     //（检查库存，如果不够）
                                     //抛出异常
                         }else{
                            throw new Exception('商品库存不足，无法继续下单，请修改购物车商品数量');
                         }


                         //下单成功后清除购物车
                         //提交事务
                         $transaction->commit();
                     } catch (Exception $e) {
                         //回滚
                         var_dump($e->getMessage());exit;
                         $transaction->rollBack();
                     }
                 }
                 //获取地址
                 return $this->render('checking',['model'=>$model,'carts'=>$carts,'address'=>$address]);
             }else{
                 return $this->redirect(['member/logo']);
             }
         }
}
