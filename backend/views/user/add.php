<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/24
 * Time: 15:04
 */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'username');
if ($model->isNewRecord){
    echo $form->field($model,'password_hash')->passwordInput();
}
echo $form->field($model,'email');
if (!$model->isNewRecord){
    echo $form->field($model,'status')->inline()->radioList(\backend\models\User::$userStatus);
}
//角色
//var_dump(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','description'));exit;
echo $form->field($model,'roles')->inline()->checkboxList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','description'));
echo \yii\bootstrap\Html::submitButton('确定',['class'=>'btn btn-info btn-sm']);

\yii\bootstrap\ActiveForm::end();