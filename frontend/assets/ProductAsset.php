<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ProductAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'estore/styles/style.css',
        'estore/styles/inner.css',
        'estore/styles/layout.css',
        'estore/styles/layerslider.css',
        'estore/styles/flexslider.css',
        'estore/styles/color.css',
        'estore/styles/prettyPhoto.css',
    ];
    public $js = [
        'estore/js/jquery-1.7.1.min.js',
        'estore/js/hoverIntent.js',
        'estore/js/superfish.js',
        'estore/js/supersubs.js',
        'estore/js/jquery.elastislide.js',
        'estore/js/jquery.prettyPhoto.js',
        'estore/js/fade.js',
        'estore/js/tinynav.min.js',
        'estore/js/custom.js',
        'estore/js/jquery-easing-1.3.js',
        'estore/js/layerslider.js',
        'estore/js/jquery.flexslider-min.js',
        'vendor/accountingjs/accounting.min.js',
        'estore/js/product/product.js',
        'estore/js/order/order.js',
        'estore/js/common/topbar-cart.js',
    ];
}
