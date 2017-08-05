=<?php
/* @var $this yii\web\View */
echo "<h1>登录</h1>";
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'username');
echo $form->field($model,'password_hash')->passwordInput();
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'user/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');
echo $form->field($model,'remeber')->inline()->checkboxList([1=>'记住密码'])->label(false);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info btn-sm']);


\yii\bootstrap\ActiveForm::end();
?>



