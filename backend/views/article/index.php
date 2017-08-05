<?php

echo \yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-info btn-sm']);


echo \yii\bootstrap\Html::a('回收站',['article/inde'],['class'=>'btn btn-info btn-sm']);
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model, 'name',[
    'options'=>['class'=>''],
    'inputOptions' => ['placeholder' => '文章搜索','class' => 'input-sm form-control'],
])->label('搜索');
//echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info btn-sm']);

\yii\bootstrap\ActiveForm::end();
/* @var $this yii\web\View */
?>

<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>类别</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php
    foreach ($models as $model):
    ?>


        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->articleDetail->content?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\Article::$modes[$model->status]?></td>
            <td><?=date('Y-m-d',$model->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['article/edit','id'=>$model->id],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-info btn-sm'])?>
            </td>
        </tr>
    <?php
    endforeach;
    ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pagination,
        'firstPageLabel'=>"首页",
        'prevPageLabel'=>'上一页',
        'nextPageLabel'=>'下一页',
        'lastPageLabel'=>'尾页',
    ]);
