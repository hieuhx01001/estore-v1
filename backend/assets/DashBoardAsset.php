<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DashBoardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'adminlte/css/ionicons.min.css',
        'adminlte/css/morris/morris.css',
        'adminlte/css/jvectormap/jquery-jvectormap-1.2.2.css',
        'adminlte/css/dashboard.css',

    ];
    public $js = [
        'adminlte/js/AdminLTE/app.js',
        'adminlte/js/plugins/chartjs/Chart.min.js',
        'adminlte/js/dashboard/dashboard2.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',

    ];
}
