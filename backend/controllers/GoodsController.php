<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use flyok666\uploadifive\UploadAction;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=new GoodsSearchForm();
        $quer=Goods::find()->where('status = 1');
        //接收表单传递到查询参数
        $model->search($quer);
        $total=$quer->count();
        $pagesize=3;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pagesize
        ]);
        $models=$quer->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager,'model'=>$model]);
    }
    public function actionAdd()
    {
        $model=new Goods();
        //商品详情表
        $models=new GoodsIntro();
        //商品每日添加数
        if ($model->load(\Yii::$app->request->post())  &&  $models->load(\Yii::$app->request->post())){
            $day=date('Y-m-d');
            $goodsCount=GoodsDayCount::findOne(['day'=>$day]);
            if ($goodsCount==null){
                $modelCount=new GoodsDayCount();
                $modelCount->day=$day;
                $modelCount->count=0;
                $modelCount->save();
            }
            $model->sn = date('Ymd').sprintf("%04d",$goodsCount->count+1);
            $model->create_time=time();
            $model->save();//goods
            $models->goods_id = $model->id;
            $models->save();//goods_intro goods_id content
            $goodsCount->count++;
            $goodsCount->save();
            \Yii::$app->session->setFlash('success','商品添加成功，添加商品相册');
            return $this->redirect(['goods/gallery','id'=>$model->id]);
        }
        return $this->render('add',
            ['model'=>$model,
                'models'=>$models,
]);
    }
    public function actionEdit($id)
    {
        $model=Goods::findOne(['id'=>$id]);
        $models = $model->goodsIntro;

        if ($model->load(\Yii::$app->request->post()) && $models->load(\Yii::$app->request->post())){
//            exit('返回');
            if ($model->validate() && $models->validate()){
                $model->save();$models->save();
                \Yii::$app->session->setFlash('success','商品修改成功');
                return $this->redirect(['goods/index']);
            }
        }
//        exit('返回');
        return $this->render('add',
            ['model'=>$model,
                'models'=>$models,]);
    }
    public function actionDel($id){
        $model = Goods::findOne(['id'=>$id]);
//        var_dump();exit;
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods/index']);

    }

//   AJAX删除图片
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }
    //预览商品信息
    public function actionView($id)
    {
        $model = Goods::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('view',['model'=>$model]);

    }
    //商品附属图片
    public function actionGallery($id)
    {
        $goods=Goods::findOne(['id'=>$id]);
        if ($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }

    public function actionQiniu()
    {
        $config = [
            'accessKey'=>'GXUB-zwzW50alz9F48U88L4ywWnCzSYqyC7whBVc',
            'secretKey'=>'E-Q0qORPSDgaXcSTYr3ihK0KziChZuEJ_ETZZ_1R',
            'domain'=>'http://otbjx9p0t.bkt.clouddn.com/',
            'bucket'=>'yii2shop',
            'area'=>Qiniu::AREA_HUADONG
        ];
        $qiuin=new Qiniu($config);
        $key='/upload/ba/d5/bad5bfdbe7f928883f8d10360433abe2fa7034e6.jpg';
        $qiuin->uploadFile(
            \Yii::getAlias('@webroot').'/upload/ba/d5/bad5bfdbe7f928883f8d10360433abe2fa7034e6.jpg',
            $key
        );
        $url=$qiuin->getLink($key);
//        var_dump($url);
    }

    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }
                },
            ],
        ];
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
