<?php
/* @var $this yii\web\View */
echo \yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-info btn-sm']);
echo "&nbsp;";
echo \yii\bootstrap\Html::a('安全退出',['user/logout'],['class'=>'btn btn-info btn-sm']);
echo "&nbsp;";
$id = \Yii::$app->user->id;
    echo \yii\bootstrap\Html::a('修改当前密码',['user/pwd','id'=>$id],['class'=>'btn btn-info btn-sm']);

?>
<h1>管理员列表</h1>
<table class="table table-bordered table-responsive table-striped">
    <tr>
        <th>ID</th>
        <th>姓名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>最后登录时间</th>
        <th>IP</th>
        <th>操作</th>
    </tr>
    <?php
    foreach ($models as $model):
    ?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->username?></td>
        <td><?=$model->email?></td>
        <td><?=\backend\models\User::$userStatus[$model->status]?></td>
        <td><?=date('Y-m-d H:i:s',$model->created_at)?></td>
        <td><?=$model->updated_at?></td>
        <td><?=date('Y-m-d H:i:s',$model->last_login_time)?></td>
        <td><?=$model->last_login_ip?></td>
        <td>
            <?php
                echo \yii\bootstrap\Html::a('编辑用户',['user/edit','id'=>$model->id],['class'=>'btn btn-info btn-sm']);
                echo \yii\bootstrap\Html::a('删除',['user/del','id'=>$model->id],['class'=>'btn btn-danger btn-sm']);
            ?>
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</table>


