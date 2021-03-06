<?php
use common\widgets\Menu;

$catalogVisibleIdentities = ['admin', 'editor'];

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            [
                'label' => Yii::t('app', 'Dashboard'),
                'url' => Yii::$app->homeUrl,
                'icon' => 'fa-dashboard',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl
            ],
            [
                'label' => Yii::t('app', 'Catalogue'),
                'url' => ['#'],
                'icon' => 'fa fa-leaf',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => in_array(Yii::$app->user->identity->username, $catalogVisibleIdentities),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Product'),
                        'url' => ['/product/index'],
                        'icon' => 'fa-gift',
                        'visible' => Yii::$app->user->can('viewProduct'),
                    ],
                    [
                        'label' => Yii::t('app', 'Category'),
                        'url' => ['/category/index'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('viewCategory'),
                    ],
                    [
                        'label' => Yii::t('app', 'Attribute'),
                        'url' => ['/attribute/index'],
                        'icon' => 'fa-tag',
                        'visible' => Yii::$app->user->can('viewAttribute'),
                    ],
                    [
                        'label' => Yii::t('app', 'Manufacturer'),
                        'url' => ['/manufacturer'],
                        'icon' => 'fa fa-leaf',
                    ],
                    [
                        'label' => Yii::t('app', 'Supplier'),
                        'url' => ['/supplier'],
                        'icon' => 'fa fa-leaf',
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', 'Order'),
                'url' => ['/order/index'],
                'icon' => 'fa-money',
                'visible' => Yii::$app->user->can('viewOrder'),
            ],
            [
                'label' => Yii::t('app', 'Customer'),
                'url' => ['/customer/index'],
                'icon' => 'ion ion-person-stalker',
            ],
            [
                'label' => Yii::t('app', 'System'),
                'url' => ['#'],
                'icon' => 'fa fa-cog',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => (Yii::$app->user->identity->username == 'admin'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'User'),
                        'url' => ['/user/index'],
                        'icon' => 'fa fa-user',
                        'visible' => Yii::$app->user->can('viewUser')
                    ],
                    [
                        'label' => Yii::t('app', 'Role'),
                        'url' => ['/role/index'],
                        'icon' => 'fa fa-lock',
                        'visible' => Yii::$app->user->can('viewRole')
                    ],
                ],

            ],
        ]
    ]
);
