<?php
/* @var $this yii\web\View */
/* @var $newProducts backend\models\Product */
$this->title = 'My Yii Application';
$urlManager = Yii::$app->getUrlManager();
$baseBackEndUrl = \Yii::$app->urlManagerBackEnd->baseUrl;

?>
<div class="container">
<div class="row">
<section id="maincontent" class="twelve columns">
<section class="content">

<div class="featured-products">
    <div class="header-wrapper">
        <h2>Sản Phẩm Mới</h2><span></span>
        <div class="clear"></div>
    </div>
    <?php $index = 0; $row1 = ''; $row2 = '';?>
        <?php foreach($newProducts as $key => $product){ ?>
        <?php
            $imgLink = $baseBackEndUrl."/img/product/default.jpeg";
            if(isset($product->mainImage->product_id)){
                $imgLink = $baseBackEndUrl."/img/product/".$product->mainImage->product_id."/".$product->mainImage->name;
            };
        ?>
        <?php if ($index < 4):?>
            <?php
                $priceHtml = '';
                if ($product->sales_price > 0) {
                    $priceHtml = '<div class="priceInfo">
                                        <span class="old-price">'.Yii::$app->formatter->asCurrency($product->price) .'</span>
                                        <span class="price">'.Yii::$app->formatter->asCurrency($product->sales_price) .'</span>
                                    </div>
                                    <div class="cart cart-sale">
                                        <a href="'.$urlManager->createUrl("site/product").'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                    </div>';
                }else {
                    $priceHtml = '<div class="price">
                                        '.Yii::$app->formatter->asCurrency($product->price) .'
                                    </div>
                                    <div class="cart">
                                        <a href="'.$urlManager->createUrl("site/product").'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                    </div>';
                }

                $row1 .= '<div class="one_fifth columns">
                            <div class="product-wrapper">
                                <span class="new"></span>
                                    <a title="'.$product->name.'" href="'.$urlManager->createUrl("site/product").'">
                                    <img src="'.$imgLink.'" alt=""/></a>
                                    <h3><a title="" href="'.$urlManager->createUrl("site/product").'">'.$product->name.'</a></h3>
                                <div class="price-cart-wrapper">
                                    '.$priceHtml.'
                                <div class="clear"></div>
                                </div>
                            </div>
                         </div>';
            ?>
        <?php else: ?>
             <?php $row2 = '<div class="one_fifth columns">
                            <div class="product-wrapper">
                                <span class="new"></span>
                                    <a title="'.$product->name.'" href="'.$urlManager->createUrl("site/product").'">
                                    <img src="'.$imgLink.'" alt=""/></a>
                                    <h3><a title="" href="'.$urlManager->createUrl("site/product").'">'.$product->name.'</a></h3>
                                <div class="price-cart-wrapper">
                                    '.$priceHtml.'
                                <div class="clear"></div>
                                </div>
                            </div>
                         </div>';?>
        <?php endif; ?>
        <?php $index++;?>
    <?php } ?>

    <div class="row">
        <?php echo $row1?>
    </div>
    <div class="row">
        <?php echo $row2?>
    </div>
</div>

<div class="featured-products">
    <div class="header-wrapper">
        <h2>Sản Phẩm Khuyến Mãi</h2><span></span>
        <div class="clear"></div>
    </div>
    <?php $index = 0; $saleRow1 = '';$saleRow2 = '';?>
    <?php foreach($saleProducts as $key => $product){ ?>
        <?php
        $imgLink = $baseBackEndUrl."/img/product/default.jpeg";
        if(isset($product->mainImage->product_id)){
            $imgLink = $baseBackEndUrl."/img/product/".$product->mainImage->product_id."/".$product->mainImage->name;
        };

        ?>
        <?php if ($index < 4):?>
            <?php
            $saleRow1 .= '<div class="one_fifth columns">
                            <div class="product-wrapper">
                                <!--<span class="new"></span>-->
                                    <a title="'.$product->name.'" href="'.$urlManager->createUrl("site/product").'">
                                    <img src="'.$imgLink.'" alt=""/></a>
                                    <h3><a title="" href="'.$urlManager->createUrl("site/product").'">'.$product->name.'</a></h3>
                                <div class="price-cart-wrapper">
                                    <div class="priceInfo">
                                        <span class="old-price">'.Yii::$app->formatter->asCurrency($product->price) .'</span>
                                        <span class="price">'.Yii::$app->formatter->asCurrency($product->sales_price) .'</span>
                                    </div>
                                    <div class="cart cart-sale">
                                        <a href="'.$urlManager->createUrl("site/product").'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                    </div>
                                <div class="clear"></div>
                                </div>
                            </div>
                         </div>';
            ?>
        <?php else: ?>
            <?php $saleRow2 = '<div class="one_fifth columns">
                            <div class="product-wrapper">
                                <!--<span class="new"></span>-->
                                    <a title="'.$product->name.'" href="'.$urlManager->createUrl("site/product").'">
                                    <img src="'.$imgLink.'" alt=""/></a>
                                    <h3><a title="" href="'.$urlManager->createUrl("site/product").'">'.$product->name.'</a></h3>
                                <div class="price-cart-wrapper">
                                    <div class="priceInfo">
                                        <span class="old-price">'.Yii::$app->formatter->asCurrency($product->price) .'</span>
                                        <span class="price">'.Yii::$app->formatter->asCurrency($product->sales_price) .'</span>
                                    </div>
                                    <div class="cart cart-sale">
                                        <a href="'.$urlManager->createUrl("site/product").'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                    </div>
                                <div class="clear"></div>
                                </div>
                            </div>
                         </div>';?>
        <?php endif; ?>
        <?php $index++;?>
    <?php } ?>
    <div class="row">
        <?php echo $saleRow1 ?>
    </div>
    <div class="row">
        <?php echo $saleRow2 ?>
    </div>
</div>

<div class="featured-products">
    <div class="header-wrapper">
        <h2>Sản Phẩm Bán Chạy</h2><span></span>
        <div class="clear"></div>
    </div>

    <?php $index = 0; $hotRow1 = ''; $hotRow2 = '';?>
    <?php foreach($hotProducts as $key => $product){ ?>
        <?php
        $imgLink = $baseBackEndUrl."/img/product/default.jpeg";
        if(isset($product->product->mainImage->product_id)){
            $imgLink = $baseBackEndUrl."/img/product/".$product->product->mainImage->product_id."/".$product->product->mainImage->name;
        };
        ?>
        <?php if ($index < 4):?>
            <?php
            $priceHtml = '';
            if ($product->product->sales_price > 0) {
                $priceHtml = '<div class="priceInfo">
                                        <span class="old-price">'.Yii::$app->formatter->asCurrency($product->product->price) .'</span>
                                        <span class="price">'.Yii::$app->formatter->asCurrency($product->product->sales_price) .'</span>
                                    </div>
                                    <div class="cart cart-sale">
                                        <a href="'.$urlManager->createUrl("site/product").'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                    </div>';
            }else {
                $priceHtml = '<div class="price">
                                        '.Yii::$app->formatter->asCurrency($product->product->price) .'
                                    </div>
                                    <div class="cart">
                                        <a href="'.$urlManager->createUrl("site/product").'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                    </div>';
            }
            $hotRow1 .= '<div class="one_fifth columns">
                            <div class="product-wrapper">
                                <!--<span class="new"></span>-->
                                    <a title="'.$product->product->name.'" href="'.$urlManager->createUrl("site/product").'">
                                    <img src="'.$imgLink.'" alt=""/></a>
                                    <h3><a title="" href="'.$urlManager->createUrl("site/product").'">'.$product->product->name.'</a></h3>
                                <div class="price-cart-wrapper">
                                    '.$priceHtml.'
                                <div class="clear"></div>
                                </div>
                            </div>
                         </div>';
            ?>
        <?php else: ?>
            <?php $hotRow2 = '<div class="one_fifth columns">
                            <div class="product-wrapper">
                                <!--<span class="new"></span>-->
                                    <a title="'.$product->product->name.'" href="'.$urlManager->createUrl("site/product").'">
                                    <img src="'.$imgLink.'" alt=""/></a>
                                    <h3><a title="" href="'.$urlManager->createUrl("site/product").'">'.$product->product->name.'</a></h3>
                                <div class="price-cart-wrapper">
                                    '.$priceHtml.'
                                <div class="clear"></div>
                                </div>
                            </div>
                         </div>';?>
        <?php endif; ?>
        <?php $index++;?>
    <?php } ?>
    <div class="row">
        <?php echo $hotRow1?>
    </div>
    <div class="row">
        <?php echo $hotRow2?>
    </div>
</div>

<!--<div class="row">-->
<!--<div class="one_third columns"><img src="images/content/EasyCustomize.jpg" alt="" class="border"/></div>-->
<!--<div class="one_third columns"><img src="images/content/AwesomeAdmin.jpg" alt="" class="border"/></div>-->
<!--<div class="one_third columns"><img src="images/content/sofaAwesome.jpg" alt="" class="border"/></div>-->
<!--</div> -->

<div class="new-products">
    <div class="header-wrapper">
        <h2>Vừa Mới Xem</h2><span></span>
        <div class="clear"></div>
    </div>
    <div class="row">
        <?php if(!empty(Yii::$app->session['items'])): ?>
            <?php foreach(array_reverse(Yii::$app->session['items']) as $key => $item):?>
                <?php if($key < 5): ?>
                    <div class="one_fifth columns">
                        <div class="product-wrapper">

                            <a title="<?php echo $item['name']?>" href="<?echo $urlManager->createUrl(['site/detail','id'=>$item['id']])?>"><img src="<?php echo $item['img']?>" alt=""/></a>
                            <h3><a title="White Dress" href="<?echo $urlManager->createUrl(['site/detail','id'=>$item['id']])?>"><?php echo $item['name']?></a></h3>
                            <div class="price-cart-wrapper">
                                <div class="price" style="float: none">
                                    <?php echo Yii::$app->formatter->asCurrency($item['price'])?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <input type="hidden" id="baseUrl" value="<?php echo Yii::getAlias("@web") ?>">

    </div>
</div>

<!--<div class="row">-->
<!--<div class="one_half columns"><img src="images/content/FlexibleLayout.png" alt=""/></div>-->
<!--<div class="one_half columns"><img src="images/content/WellDocumented.png" alt=""/></div>-->
<!--</div>-->

</section>

</section>
</div>
</div>
