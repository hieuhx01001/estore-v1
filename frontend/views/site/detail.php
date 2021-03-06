<?php
/* @var $this yii\web\View */
/* @var $product backend\models\Product */

$this->title = 'Product Detail';
$urlManager = Yii::$app->getUrlManager();
$baseBackEndUrl = \Yii::$app->urlManagerBackEnd->baseUrl;

$productImages = $product->images;
$productMainImage = $product->mainImage;
if (empty($productMainImage)){
    $imgMainLink = $urlManager->createUrl("estore/images/default_noimage.jpg");
}else {
    $imgMainLink = $baseBackEndUrl."/img/product/".$productMainImage->product_id."/".$productMainImage->name;
}

$productAttributes = $product->productAttributes;
// store recently product view
$productRecentlyView = array();
$productRecentlyView['name'] = $product->name;
$productRecentlyView['price'] = $product->price;
$productRecentlyView['id'] = $product->id;
$productRecentlyView['img'] = $imgMainLink;
$session = Yii::$app->session;

if(!empty($session['items'])){
    $items = $session['items'];
    $added = false;
    foreach($items as $item){
        if($item['id'] == $productRecentlyView['id'] ){
            $added = true;
        }
    }
    if ($added == false){
        $items[] = $productRecentlyView;
        $session['items'] =  $items;
    }
}else {
    $items[] = $productRecentlyView;
    $session['items'] = $items;
}
?>

<script>
    window.currentCategoryId = <?= $product->mainCategory->id ?>;
</script>

<section id="maincontent" class="ten columns positionleft">
    <div class="padcontent">

        <section class="content" id="product-detail">

            <div class="breadcrumb"><a href="index.html">Trang Chủ</a> / Sản Phẩm / <?php echo $product->name ?></div>
            <h1 class="pagetitle"><?php echo $product->name ?></h1>

            <div class="row">
                <div class="one_fourth columns">
                    <div id="pb-right-column">
                        <?php if (empty($productImages)) : ?>
                            <div class="image-block">
                                <div id="imageitems" class="flexslider">
                                    <ul class="slides">
                                        <li>
                                            <a class="image" href="<?= $urlManager->createUrl("estore/images/default_noimage.jpg") ?>" data-rel="prettyPhoto[mixed]" >
                                                <img src="<?= $urlManager->createUrl("estore/images/default_noimage.jpg") ?>">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="image-block">
                                <div id="imageitems" class="flexslider">
                                    <ul class="slides">
                                        <?php foreach ($productImages as $img) : ?>
                                            <?php
                                                $imgLink = $baseBackEndUrl."/img/product/".$img->product_id."/".$img->name;
                                            ?>
                                            <li>
                                                <a class="image" href="<?php echo $imgLink ?>" data-rel="prettyPhoto[mixed]" >

                                                    <img src="<?php echo $imgLink ?>" alt="">
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>

                            <?php if (count((array)$productImages)>1):?>
                            <div id="thumbslider">
                                <div id="carouselslider" class="flexslider">
                                    <ul class="slides">

                                        <?php foreach ($productImages as $img) : ?>
                                            <?php $imgLink = $baseBackEndUrl."/img/product/".$img->product_id."/".$img->name; ?>
                                            <li><img src="<?php echo $imgLink ?>" alt="" /></li>
                                        <?php endforeach ?>

                                    </ul>
                                </div>
                            </div>
                            <?php endif;?>
                        <?php endif; ?>

                        </br>

                        <a href="checkout.html" class="cart"
                           data-id="<?= $product->id ?>"
                           data-url="<?= $urlManager->createUrl('order/ajax-add-to-cart') ?>">Đặt Hàng</a>
                        <!--<ul id="usefull_link_block">-->
                        <!--<li class="print"><a href="#">Print</a></li>-->
                        <!--<li class="share_fb"><a href="#">Share on Facebook</a> </li>-->
                        <!--<li class="favoriteproducts"><a href="#">my favorites</a></li>-->
                        <!--</ul>-->

                    </div>
                </div>

                <div class="three_fourth columns">

                    <div class="feature-title" style="font-size: 15px;">Giá: <span style="font-weight: normal;color: red"><?php echo Yii::$app->formatter->asCurrency($product->price)?></span></div>
                    <div class="feature-title" style="font-size: 15px;">Mã sản phẩm: <span style="font-weight: normal"><?php echo $product->code ?></span></div>
                    <div class="feature-title" style="font-size: 15px;">Miêu tả ngắn: <span style="font-weight: 100"><?php echo $product->short_description ?></span></div>
                    <div class="feature-title" style="font-size: 15px;">Tính năng</div>
                    <div class="iSpecial">
                        <table width="100%">
                            <thead>

                            </thead>
                            <tbody>
                            <?php foreach ($productAttributes as $pa) : ?>
                                <tr>
                                    <th><?= $pa->attr->name ?></th>
                                    <td><?= $pa->value ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <!--<p>Cras sed tortor a tortor malesuada tempus eget non ante. Pellentesque cursus, elit nec semper porttitor, nisi magna adipiscing quam, nec convallis leo erat a nunc. Nulla libero urna, faucibus eget fermentum tempus, porttitor ac urna. In tempus lacinia neque id auctor. </p>-->


                </div>
            </div>

            <div class="separator"></div>


            <div class="tabcontainer">
                <ul class="tabs">
                    <li><a href="#tab0">Thông tin chi tiết</a></li>
                    <li><a href="#tab1">Tính năng</a></li>
                </ul>
                <div class="tab-body">
                    <div id="tab0" class="tab-content">
                        <p><?php echo $product->description?></p>
                    </div>
                    <div id="tab1" class="tab-content">
                        <p><?php echo $product->feature?></p>
                    </div>

                </div>
            </div>

        </section>

    </div>
</section>
