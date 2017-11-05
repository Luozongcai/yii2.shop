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
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => '品牌', 'url' => ['/brand/list']],
        ['label' => '文章分类', 'url' => ['/article/category-list']],
        ['label' => '文章列表', 'url' => ['/article/list']],
    ];
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],

        ['label' => '品牌管理', 'items'=>[
            ['label' => '品牌列表', 'url' => ['/brand/list']],
            ['label' => '添加品牌', 'url' => ['/brand/add']],
        ]],

        ['label' => '文章管理', 'items'=>[
            ['label' => '文章列表', 'url' => ['/article/list']],
            ['label' => '添加文章', 'url' => ['/article/add']],
            ['label' => '文章分类列表', 'url' => ['/article/category-list']],
            ['label' => '添加文章分类', 'url' => ['/article/category-add']],
        ]],

        ['label' => '商品管理', 'items'=>[

            ['label' => '商品分类列表', 'url' => ['/goods/category-list']],
            ['label' => '添加商品分类', 'url' => ['/goods/category-add']],
        ]],

    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
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
