<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
//    public function actions()
//    {
//
//        return [
//            'class'=>CaptchaAction::className(),
//            'minLength'=>3,
//            'maxLength'=>3
//        ];
//    }

    public function actionIndex()
    {
        $quer=Brand::find();
        $total=$quer->count();//总条数
        $pagesize=3;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pagesize
        ]);
        $models=$quer->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加
    public function actionAdd()
    {
        $model=new Brand();
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());//加载表单数据
            //实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){//各种判断
                if ($model->imgFile){
                    $ci=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');

                    if (!is_dir($ci)){
                        mkdir($ci);
                    }
                    //生成文件路径
                    $fileName='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
//                    var_dump($model);exit;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo=$fileName;
                }
                $model->save(false);
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //图片文件上传插件
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
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
                    $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛晕
                    $qiniu=new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(),$action->getWebUrl()
                    );
                    $url=$qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']=$url;

                },
            ],
        ];
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

    public function actionEdit($id)
    {
        $model=Brand::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            $model->load($request->post());//加载表单数据

            //实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){//各种判断
                if ($model->imgFile){

                    $ci=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');

                    if (!is_dir($ci)){
                        mkdir($ci);
                    }

                    //生成文件路径
                    $fileName='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
//                    var_dump($model);exit;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);

                    $model->logo=$fileName;
                }

                $model->save(false);
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id)
    {
        $model=Brand::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
//        var_dump($model->status);exit;
        return $this->redirect(['brand/index']);

    }
    public function actionInde()
    {
//        $status=new Brand();
        $quer=Brand::find()->where(['and','status = -1']);
//        var_dump($quer);exit;
        $total=$quer->count();
        $pagesize=3;
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pagesize
        ]);
        $models=$quer->limit($pager->limit)->offset($pager->offset)->all();

        return $this->render('inde',['models'=>$models,'pager'=>$pager]);
    }
    public function actionDele($id)
    {
            $model=Brand::findOne(['id'=>$id])->delete();

//        var_dump($model->status);exit;
        return $this->redirect(['brand/index']);
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
