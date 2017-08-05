<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Vincent-Nobel',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
//    $menuItems = [
//        ['label' => 'Home', 'url' => ['/brand/index']],
//        ['label'=>'品牌管理','items'=>[
//            ['label'=>'添加品牌','url'=>['brand/add']],
//            ['label'=>'品牌列表','url'=>['brand/index']]
//        ]],
//        ['label'=>'文章管理','items'=>[
//            ['label'=>'文章添加','url'=>['article/add']],
//            ['label'=>'文章列表','url'=>['article/list']],
//            ['label'=>'添加分类','url'=>['article-category/add']],
//            ['label'=>'分类列表','url'=>['article-category/index']],
//        ]],
//        ['label' => '商品管理', 'items' => [
//            ['label'=>'商品添加','url'=>['goods/add']],
//            ['label'=>'商品列表','url'=>['goods/index']],
//            ['label'=>'添加分类','url'=>['goods-category/add2']],
//            ['label'=>'分类列表','url'=>['goods-category/index']],
//        ]],
//        ['label' => '注销', 'url' => ['user/logout']],
////        ['label' => '文章管理', 'url' => ['/site/index']],
//    ];
    $menuItems=[];
    $menus=\backend\models\Menu::findAll(['parent_id'=>0]);
    foreach ($menus as $menu){
        //一级菜单
        $itme=[];
        foreach ($menu->chile as $chile){
            //判断用户是否有该权限路由的权限
            if (Yii::$app->user->can($chile->url)){
                //二级菜单
                $itme[]=['label' =>$chile->name, 'url' =>[$chile->url]];
            }
        }
        if (!empty($itme)){
            $menuItems[]=['label' => $menu->name, 'items' =>$itme];
        }
    }
    if (Yii::$app->user->isGuest) {
//        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        $menuItems[] = ['label' => 'Login', 'url' => ['user/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/user/logout'], 'post')
            . Html::submitButton(
                '欢迎帝爵: ' . Yii::$app->user->identity->username . ' 阁下',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
