<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/19
 * Time: 19:43
 */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');
echo $form->field($model,'intro');
//echo $form->field($model,'articleDetail');
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\ArticleCategory::find()->all(),'id','name'));
//echo \kucha\ueditor\UEditor::widget([
//    'clientOptions' => [
//        //编辑区域大小
//        'initialFrameHeight' => '200',
//        //设置语言
//        'lang' =>'en', //中文为 zh-cn
//        //定制菜单
//        'toolbars' => [
//                'fullscreen', 'source', 'undo', 'redo', '|',
//                'fontsize',
//                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
//                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
//                'forecolor', 'backcolor', '|',
//                'lineheight', '|',
//                'indent', '|'
//            ],
//        ]
//    ]);
echo $form->field($model,'sort');
//echo $form->field($models,'content')->textarea();
echo $form->field($models,'content')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($model,'status')->inline()->radioList(\backend\models\Article::getStatus());
echo \yii\bootstrap\Html::submitButton('确定',['class'=>'btn btn-info btn-sm']);

\yii\bootstrap\ActiveForm::end();