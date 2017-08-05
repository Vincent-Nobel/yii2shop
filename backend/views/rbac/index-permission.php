
<?php
/* @var $this yii\web\View */
echo "<h2>权限管理</h2>";
echo \yii\bootstrap\Html::a('添加',['rbac/add-permission'],['class'=>'btn btn-info btn-sm']);
?>

<table id="table_id_example" class="display table table-bordered">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>操作</th>
    </tr>
    </thead>

        <tbody>
        <?php
        foreach ($models as $model):
        ?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?=date('Y-m-d H:i:s',$model->createdAt)?></td>
            <td><?=date('Y-m-d H:i:s',$model->updatedAt)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$model->name],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
            <?php
        endforeach;
        ?>
        </tbody>
</table>
<?php
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
language: {
        url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Chinese.json"
    }
});');
