<?php
echo \yii\bootstrap\Html::a('添加',['rbac/add-role'],['class'=>'btn btn-info btn-sm']);
?>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php
    foreach ($models as $model):
    ?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-info btn-sm'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$model->name],['class'=>'btn btn-info btn-sm'])?>
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</table>