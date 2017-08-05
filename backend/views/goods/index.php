<?php
/* @var $this yii\web\View */
?>
<h1>商品列表</h1>
<?php
echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info btn-sm']);
$form=\yii\bootstrap\ActiveForm::begin([
        'method'=>'get',
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'layout'=>'inline'
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'￥'])->label('-');
echo \yii\bootstrap\Html::submitButton('<span class="glyphicon glyphicon-search"></span>搜索',['class'=>'btn btn-info btn-sm']);
\yii\bootstrap\ActiveForm::end();

?>
<table class="table table-responsive table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php
    foreach ($models as $model):
    ?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->sn?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>
        <td><?=$model->brand_id?></td>
        <td><?=$model->market_price?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=\backend\models\Goods::$sale_options[$model->is_on_sale]?></td>
        <td><?=\backend\models\Goods::$status_options[$model->status]?></td>
        <td><?=$model->sort?></td>
        <td><?=date('Y-m-d',$model->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-picture"></span>相册',['goods/gallery','id'=>$model->id],['class'=>'btn btn-default btn-sm    '])?>
            <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span>编辑',['goods/edit','id'=>$model->id],['class'=>'btn btn-primary btn-sm'])?>
            <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>删除',['goods/del','id'=>$model->id],['class'=>'btn btn-warning btn-sm'])?>
            <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-film"></span>预览',['goods/view','id'=>$model->id],['class'=>'btn btn-success'])?>
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager]);
