<?php
echo \yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info btn-sm']);
echo "&nbsp;";
echo \yii\bootstrap\Html::a('回收站',['brand/inde'],['class'=>'btn btn-info btn-sm'])
?>

<table class="table table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php
    foreach ($models as $model):
    ?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>
        <td><?=$model->sort?></td>
        <td><?=$model->status?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['brand/edit','id'=>$model->id],['class'=>'btn btn-sm btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$model->id],['class'=>'btn btn-warning btn-sm'])?>
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager]);
