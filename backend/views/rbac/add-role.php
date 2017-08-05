<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/26
 * Time: 18:58
 */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');
echo $form->field($model,'description');
echo $form->field($model,'permissions')->inline()->checkboxList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'));
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-info btn-sm']);

\yii\bootstrap\ActiveForm::end();