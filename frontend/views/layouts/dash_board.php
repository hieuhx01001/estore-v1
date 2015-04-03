<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\DashBoardAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */
DashBoardAsset::register($this);

$GLOBALS['urlMgr'] = Yii::$app->urlManager;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- CSS
  ================================================== -->
    <?php $this->head() ?>
    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="" />
</head>

<body>
<?php $this->beginBody() ?>
    <div id="bodychild">
        <div id="outercontainer">

            <!-- HEADER -->
                <?= $this->render('//layouts/top.php',array('page'=>'dashboard')) ?>
            <!-- END HEADER -->

            <!-- SLIDER -->
                <?= $this->render('//layouts/slider.php') ?>
            <!-- END SLIDER -->

            <!-- MAIN CONTENT -->
            <div id="outermain">
                <?= $content ?>
            </div>
            <!-- END MAIN CONTENT -->

            <!-- FOOTER -->
                <?= $this->render('//layouts/footer.php') ?>
            <!-- END FOOTER -->
            <div class="clear"></div><!-- clear float -->
        </div><!-- end outercontainer -->
    </div><!-- end bodychild -->
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
