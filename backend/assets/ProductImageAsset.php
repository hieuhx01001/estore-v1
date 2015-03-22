<?php
namespace backend\assets;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;

class ProductImageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/product-images.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
