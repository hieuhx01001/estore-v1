<?php
/* @var $this yii\web\View */
/* @var $product backend\models\Product */
$this->title = 'Product Detail';
$urlManager = Yii::$app->getUrlManager();
$baseBackEndUrl = \Yii::$app->urlManagerBackEnd->baseUrl;

$productImages = $product->images;
$productAttributes = $product->productAttributes;
?>
<section id="maincontent" class="ten columns positionleft">
    <div class="padcontent">

        <section class="content" id="product-detail">

            <div class="breadcrumb"><a href="index.html">Trang Chủ</a> / Sản Phẩm / <? echo $product->name ?></div>
            <h1 class="pagetitle"><? echo $product->name ?></h1>

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
                                                <a class="image" href="<? echo $imgLink ?>" data-rel="prettyPhoto[mixed]" >

                                                    <img src="<? echo $imgLink ?>" alt="">
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>

                            <div id="thumbslider">
                                <div id="carouselslider" class="flexslider">
                                    <ul class="slides">
                                        <?php foreach ($productImages as $img) : ?>
                                            <? $imgLink = $baseBackEndUrl."/img/product/".$img->product_id."/".$img->name; ?>
                                            <li><img src="<? echo $imgLink ?>" alt="" /></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="#" method="get" class="product_attributes">
                            <fieldset class="attribute_fieldset">
                                <br>
                                <label class="qty_label">Số lượng:</label>
                                <div class="qty_list">
                                    <select>
                                        <option title="1" selected="selected">1</option>
                                        <option title="2">2</option>
                                        <option title="3">3</option>
                                        <option title="4">4</option>
                                    </select>
                                </div>
                            </fieldset>
                        </form>

                        <a href="checkout.html" class="cart">Đặt Hàng</a>
                        <!--<ul id="usefull_link_block">-->
                        <!--<li class="print"><a href="#">Print</a></li>-->
                        <!--<li class="share_fb"><a href="#">Share on Facebook</a> </li>-->
                        <!--<li class="favoriteproducts"><a href="#">my favorites</a></li>-->
                        <!--</ul>-->

                    </div>
                </div>

                <div class="three_fourth columns">

                    <div class="feature-title" style="font-size: 15px;">Giá: <span style="font-weight: normal;color: red"><?echo Yii::$app->formatter->asCurrency($product->price)?></span></div>
                    <div class="feature-title" style="font-size: 15px;">Mã sản phẩm: <span style="font-weight: normal"><? echo $product->code ?></span></div>
                    <div class="feature-title" style="font-size: 15px;">Miêu tả ngắn: <span style="font-weight: 100"><?echo $product->short_description ?></span></div>
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
                        <p><? echo $product->description?></p>
                    </div>
                    <div id="tab1" class="tab-content">
                        <p><? echo $product->feature?></p>
                    </div>

                </div>
            </div>

        </section>

    </div>
</section>