<?php
use backend\models\Category;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\DashBoardAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */
\frontend\assets\ProductAsset::register($this);
\frontend\assets\DetailsAsset::register($this);
/** @var \yii\web\UrlManager $urlMgr */
$GLOBALS['urlMgr'] = Yii::$app->urlManager;

/**
 * Generate a category list
 *
 * @param Category[] $categories
 * @return string
 */
function generateCategoryList($categories)
{
    if (! empty($categories)) {

        $html = '<ul>';
        $urlMgr = $GLOBALS['urlMgr'];

        foreach ($categories as $cate) {

            $html .= '<li>';

            $html .= "<a data-id='{$cate->id}'>{$cate->name}</a>";

            $children = $cate->children;

            if (! empty($children) or $cate->level == 1) {
                $html .= '<span class="btn-sub-category">&gt;</span>';
            }

            $html .= generateCategoryList($cate->children);

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}

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
                <?= $this->render('//layouts/top.php',array('page'=>'product')) ?>
            <!-- END HEADER -->

            <!-- MAIN CONTENT -->
            <div id="outermain">
                <div class="container">
                    <div class="row">
                        <aside class="two columns">
                            <div class="sidebar">
                                <ul>
                                    <li class="widget-container">
                                        <!-- BEGIN: Generate list of categories -->
                                        <div id="category-list">
                                            <h2 class="widget-title">Danh mục sản phẩm</h2>
                                            <?= generateCategoryList(Category::findAll(['level'=> 1])) ?>
                                        </div>
                                        <!-- BEGIN: Price range search box -->
                                        <div id="price-range-container" class="margin_bottom_large">
                                            <h2 class="widget-title">Lọc theo giá</h2>
                                            <form id="frm-price-range">
                                                <label>Min Price</label>
                                                <input id="txt-min-price" type="number">
                                                <label>Max Price</label>
                                                <input id="txt-max-price" type="number">
                                                <button id="btn-update-price-range">Update</button>
                                            </form>
                                        </div>
                                    </li>
                                    <?php if(!empty(Yii::$app->session['items'])): ?>
                                        <li class="widget-container" style="margin-bottom: 15px !important;">
                                            <h2 class="widget-title" style="text-align: center">Vừa mới xem</h2>
                                            <ul class="sp-widget">
                                                <?php foreach(array_reverse(Yii::$app->session['items']) as $key => $item):?>
                                                    <?php if($key < 5): ?>
                                                        <li>
                                                            <a class="image" href="<?echo $GLOBALS['urlMgr']->createUrl(['site/detail','id'=>$item['id']])?>"><img src="<?php echo $item['img']?>" alt="" /></a>
                                                            <h3><a href="<?echo $GLOBALS['urlMgr']->createUrl(['site/detail','id'=>$item['id']])?>"><?php echo $item['name']?></a></h3>
                                                            <div class="price"><?php echo Yii::$app->formatter->asCurrency($item['price'])?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                    <li class="widget-container">
                                        <a href="#"><img src="<?php echo $GLOBALS['urlMgr']->createUrl('estore/images/content/banner.gif')?>" alt="" /></a>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                        <?= $content ?>
                    </div>
                </div>

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

<script>
    $(function () {

        $(".btn-sub-category", "#category-list").click(function () {
            $(this).siblings("ul").toggle();
        });

        //$(".btn-sub-category", "#category-list").click();
    });
</script>

<style>
    .sidebar li li {
        background-image: none;
        padding: 0 0 4px;
        border-bottom: none;
    }
    .btn-sub-category {
        display: block;
        float: left;
        margin-right: 5px;
        cursor: pointer;
    }
    .btn-sub-category ~ ul {
        margin-left: 10px;
    }
</style>
