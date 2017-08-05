<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/28
 * Time: 10:46
 */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenu(),['prompt' => '请选择上级菜单']);
echo $form->field($model,'url')->dropDownList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','name'),['prompt'=>'请选择路径']);
echo $form->field($model,'sort');
echo \yii\bootstrap\Html::submitButton('确定',['class'=>'btn btn-info btn-sm']);
\yii\bootstrap\ActiveForm::end();