<?php
/* @var $this yii\web\View */
/* @var $products backend\models\ProductCategory */

$urlManager = Yii::$app->getUrlManager();
$baseBackEndUrl = \Yii::$app->urlManagerBackEnd->baseUrl;
$this->title = 'Product Grid';
$selected = 'selected="selected"';
?>
<section id="maincontent" class="ten columns positionleft">
    <div class="padcontent">
        <section class="content" id="product-container">
            <div class="breadcrumb">Trang Chủ / Sản Phẩm </div>
            <!--<img src="images/content/clothing.jpg" alt=""/>-->
            <div class="sortPagiBar">
                <form style="width: 100%" id="search_by" action="<?php echo $urlManager->createUrl("site/all")?>" method="get" class="productsSortForm">
                    <p style="float:left" class="select">
                        <label for="selectProductSort">Xem theo</label>
                        <select id="search_dropdown" name="search_dropdown" class="selectProductSort">
                            <option <?php if ($searchBy == '') echo $selected ?> selected="selected" value="default">Mặc định</option>
                            <option <?php if ($searchBy == 'price:asc') echo $selected ?> value="price:asc">Giá: giá thấp nhất</option>
                            <option <?php if ($searchBy == 'price:desc') echo $selected ?> value="price:desc">Giá: giá cao nhất</option>
                            <option <?php if ($searchBy == 'name:asc') echo $selected ?> value="name:asc">Tên Sản Phẩm: A to Z</option>
                            <option <?php if ($searchBy == 'name:desc') echo $selected ?> value="name:desc">Tên Sản Phẩm: Z to A</option>
                            <!--<option value="quantity:desc">Còn Trong Kho</option>-->
                        </select>
                    </p>

                    <p class="select">
                        <label style="margin-left: 10px" for="selectPrductSort">Hiển Thị:</label>
                        <select id="show_dropdown" name="show_dropdown" class="selectProductSort">
                            <option <?php if ($showBy == Yii::$app->params['listPerPage']) echo $selected ?> value="<?php echo Yii::$app->params['listPerPage']?>"> Mặc định </option>
                            <option <?php if ($showBy == '1') echo $selected ?> value="1">1</option>
                            <option <?php if ($showBy == '2') echo $selected ?> value="2">2</option>
                            <option <?php if ($showBy == '3') echo $selected ?> value="3">3</option>
                            <option <?php if ($showBy == '4') echo $selected ?> value="4">4</option>
                        </select>
                    </p>
                </form>
            </div>

            <div class="row">
                <?php foreach($products as $product):?>

                    <?php
                        $imgLink = $baseBackEndUrl."/img/product/default_noimage.jpg";
                        if(isset($product->mainImage->product_id)){
                            $imgLink = $baseBackEndUrl."/img/product/".$product->mainImage->product_id."/".$product->mainImage->name;
                        };
                        $priceHtml = '';
                        if ($product->sales_price > 0) {
                            $priceHtml = '<div class="priceInfo">
                                            <span class="old-price">'.Yii::$app->formatter->asCurrency($product->price) .'</span>
                                            <span class="price">'.Yii::$app->formatter->asCurrency($product->sales_price) .'</span>
                                        </div>
                                        <div class="cart cart-sale">
                                            <a href="'.$urlManager->createUrl("site/detail/".$product->id).'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                        </div>';
                        }else {
                            $priceHtml = '<div class="price">
                                            '.Yii::$app->formatter->asCurrency($product->price) .'
                                        </div>
                                        <div class="cart">
                                            <a href="'.$urlManager->createUrl("site/detail/".$product->id).'" class="more">more</a> | <a href="#" class="buy">buy</a>
                                        </div>';
                        }
                    ?>
                    <div class="one_fourth columns">
                        <div class="product-wrapper">
                            <a title="<? echo $product->name?>" href="<? echo $urlManager->createUrl("site/detail/".$product->id)?>">
                                <img src="<?php echo $imgLink?>" alt=""/></a>
                            <h3>
                                <a title="<? echo $product->name?>" href="<? echo $urlManager->createUrl("site/detail/".$product->id)?>"><?php echo $product->name?></a>
                            </h3>
                            <div class="price-cart-wrapper">
                                <?php echo $priceHtml?>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="wp-pagenavi">
                <?php
                // display pagination
                echo \yii\widgets\LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                ?>
            </div>

        </section>

    </div>
</section>
