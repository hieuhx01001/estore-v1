<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/*NavBar::begin([
    'brandLabel' => 'My Company',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);*/
$menuItems = [
    [
        'label' => Yii::t('app', 'Home'),
        'url' => ['/site/index']
    ],
    [
        'label' => Yii::t('app', 'Logout') . '(' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ]
];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);

$menuItemsMain = [

];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => $menuItemsMain,
    'encodeLabels' => false,
]);

//NavBar::end();

