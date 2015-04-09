<?php
use backend\models\Product;
use frontend\assets\SearchAsset;
use yii\web\View;

/** @var View $this */
/** @var Product[] $products */
/** @var double $lowestPrice */
/** @var double $highestPrice */

$app = Yii::$app;
$urlManager = $app->urlManager;
$backendUrl = $app->get('urlManagerBackEnd')->baseUrl;
$formatter = $app->formatter;

$this->registerAssetBundle(SearchAsset::className());

?>

<section id="maincontent" class="ten columns positionleft">
    <div class="padcontent">
        <section class="content" id="product-container">

            <div class="breadcrumb">Trang Chủ / Tìm kiếm</div>

            <div id="price-range-container" class="margin_bottom_large">
                <form id="frm-price-range">
                    <label>Min Price</label>
                    <input id="txt-min-price" type="number">
                    <label>Max Price</label>
                    <input id="txt-max-price" type="number">
                    <button id="btn-update-price-range">Update</button>
                </form>
            </div>
            
            <div class="row">
                <?php foreach ($products as $product) : ?>
                    <?php $detailsUrl = $urlManager->createAbsoluteUrl('site/detail/' . $product->id) ?>
                    <?php $mainImage = $product->mainImage ?>
                    <?php $imageUrl = is_null($mainImage) ?
                        ($urlManager->createAbsoluteUrl('estore/images/default_noimage.jpg')) :
                        ($backendUrl . "/img/product/{$product->id}/{$mainImage->name}") ?>
                    <div class="one_fourth columns">
                        <div class="product-wrapper">
                            <a title="<?= $product->name ?>" href="<?= $detailsUrl ?>">
                                <img src="<?= $imageUrl ?>" alt="">
                            </a>
                            <h3>
                                <a title="<?= $product->name ?>" href="<?= $detailsUrl ?>">
                                    <?= $product->name ?>
                                </a>
                            </h3>
                            <div class="price-cart-wrapper">
                                <?php if (empty($product->sales_price)) : ?>
                                    <div class="price"><?= $formatter->asCurrency($product->price) ?></div>
                                <?php else : ?>
                                    <div class="priceInfo">
                                        <span class="old-price"><?= $formatter->asCurrency($product->price) ?></span>
                                        <span class="price"><?= $formatter->asCurrency($product->sales_price) ?></span>
                                    </div>
                                <?php endif ?>
                                <div class="cart">
                                    <a href="<?= $detailsUrl ?>" class="more">more</a> |
                                    <a class="buy"
                                       data-id="<?= $product->id ?>"
                                       data-url="<?= $urlManager->createAbsoluteUrl('/order/ajax-add-to-cart') ?>">buy</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </section>
    </div>
</section>

<style>
    #category-list a.selected
    {
        padding: 2px 5px;
        border-radius: 2px;
        background-color: red;
        color: white;
    }
    #btn-update-price-range,
    #txt-max-price,
    #txt-min-price {
        display: inline-block;
        margin-right: 15px;
        padding: 2px 5px;
        line-height: 10px;
        border: solid 1px #d3d3d3;
        border-radius: 2px;
    }
    #btn-update-price-range {
        line-height: 15px;
    }
    #frm-price-range > label {
        margin-right: 15px;
    }
</style>
