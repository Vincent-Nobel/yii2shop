<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/26
 * Time: 14:47
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-info btn-sm']);
\yii\bootstrap\ActiveForm::end();