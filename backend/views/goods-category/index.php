<?php
echo \yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info btn-sm']);

echo "&nbsp;";

echo \yii\bootstrap\Html::a('添加2',['goods-category/add2'],['class'=>'btn btn-info btn-sm']);
/* @var $this yii\web\View */
?>

<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>上级分类id</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php
    foreach ($models as $model):
    ?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->parent_id?></td>
        <td><?=$model->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['edit','id'=>$model['id']],['class'=>'btn btn-info btn-sm'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-info btn-sm'])?>
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager]);
