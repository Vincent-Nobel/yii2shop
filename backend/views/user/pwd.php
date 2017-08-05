<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/24
 * Time: 15:04
 */
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'password')->passwordInput();
    echo $form->field($model,'new_password')->passwordInput();
    echo $form->field($model,'re_new_password')->passwordInput();
    echo \yii\bootstrap\Html::submitButton('确定',['class'=>'btn btn-info btn-sm']);
\yii\bootstrap\ActiveForm::end();