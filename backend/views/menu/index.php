<?php
/* @var $this yii\web\View */
echo \yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-info btn-sm']);

?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>分类ID</th>
        <th>权限地址</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php
    foreach ($models as $model):
    ?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->parent_id?></td>
        <td><?=$model->url?></td>
        <td><?=$model->sort?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$model->id],['class'=>'btn btn-info btn-sm'])?>
            <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-danger btn-sm'])?>
        </td>
    </tr>
        <?php
        foreach ($model->chile as $menu):
        ?>
            <tr>
                <td><?=$menu->id?></td>
                <td>!-_- <?=$menu->name?></td>
                <td><?=$menu->parent_id?></td>
                <td><?=$menu->url?></td>
                <td><?=$model->sort?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$menu->id],['class'=>'btn btn-info btn-sm'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$menu->id],['class'=>'btn btn-danger btn-sm'])?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
    <?php
    endforeach;
    ?>
</table>
</table>
