<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/21
 * Time: 20:06
 */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');
echo $form->field($model,'parent_id');
echo $form->field($model,'intro');
echo \yii\bootstrap\Html::submitButton('确定',['class'=>'btn btn-info btn-sm']);

\yii\bootstrap\ActiveForm::end();