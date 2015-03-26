<?php
/* @var $this yii\web\View */
$this->title = 'Product Grid';
?>
<section id="maincontent" class="ten columns positionleft">
    <div class="padcontent">
        <section class="content" id="product-container">

            <div class="breadcrumb"><a href="index.html">Trang Chủ</a> / Sản Phẩm / Điện Trở </div>
            <img src="images/content/clothing.jpg" alt=""/>

            <div class="sortPagiBar">
                <form action="index.html" class="productsSortForm">
                    <p class="select">
                        <label for="selectProductSort">Xem theo</label>
                        <select class="selectProductSort">
                            <option selected="selected" value="position:asc">Mặc định</option>
                            <option value="price:asc">Giá: giá thấp nhất</option>
                            <option value="price:desc">Giá: giá cao nhất</option>
                            <option value="name:asc">Tên Sản Phẩm: A to Z</option>
                            <option value="name:desc">Tên Sản Phẩm: Z to A</option>
                            <option value="quantity:desc">Còn Trong Kho</option>
                        </select>
                    </p>
                </form>

                <form action="index.html" class="productsShowForm">
                    <p class="select">
                        <label for="selectPrductSort">Hiển Thị:</label>
                        <select class="selectProductSort">
                            <option selected="selected"> 4 </option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </p>
                </form>
                <a href="#" class="button">So Sánh</a>

            </div>

            <div class="row">
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <span class="new"></span>
                        <a title="Woman's Dress Flower" href="product-details.html"><img src="images/content/products/product1.jpg" alt=""/></a>
                        <h3><a title="Woman's Dress Flower" href="product-details.html">ADC0838CCN</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                120.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more" alt="Chi tiết">Chi tiết</a> | <a href="checkout.html" class="buy">Mua</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <a title="Gold Dress" href="product-details.html"><img src="images/content/products/product2.jpg" alt=""/></a>
                        <h3><a title="Gold Dress" href="product-details.html">KBJ3510</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                120.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more">more</a> | <a href="checkout.html" class="buy">buy</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <a title="Blue &amp; White" href="product-details.html"><img src="images/content/products/product3.jpg" alt=""/></a>
                        <h3><a title="Blue &amp; White" href="product-details.html">1N5822</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                20.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more">more</a> | <a href="checkout.html" class="buy">buy</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <a title="Brown Dress" href="product-details.html"><img src="images/content/products/product4.jpg" alt=""/></a>
                        <h3><a title="Brown Dress" href="product-details.html">AT43301-AU</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                1.500.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more">more</a> | <a href="checkout.html" class="buy">buy</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <a title="Brown Dress" href="product-details.html"><img src="images/content/products/product5.jpg" alt=""/></a>
                        <h3><a title="Brown Dress" href="product-details.html">LPC2148FBD64</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                180.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more">more</a> | <a href="checkout.html" class="buy">buy</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <a title="White Dress" href="product-details.html"><img src="images/content/products/product6.jpg" alt=""/></a>
                        <h3><a title="White Dress" href="product-details.html">TRANSISTOR NPN</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                750.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more">more</a> | <a href="checkout.html" class="buy">buy</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <a title="Unique Dress" href="product-details.html"><img src="images/content/products/product7.jpg" alt=""/></a>
                        <h3><a title="Unique Dress" href="product-details.html">AT43301-AU</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                30.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more">more</a> | <a href="checkout.html" class="buy">buy</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="one_fourth columns">
                    <div class="product-wrapper">
                        <a title="Flower Dress" href="product-details.html"><img src="images/content/products/product8.jpg" alt=""/></a>
                        <h3><a title="Flower Dress" href="product-details.html">LL4148 - SMD</a></h3>
                        <div class="price-cart-wrapper">
                            <div class="price">
                                140.000 VND
                            </div>
                            <div class="cart">
                                <a href="product-details.html"  class="more">more</a> | <a href="checkout.html" class="buy">buy</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wp-pagenavi">
                <a href="#" class="page">1</a><span class="current"><span>2</span></span><a href="#" class="page">3</a> &nbsp;&nbsp;Hiển thị 1 của 3 (3 trang)
            </div>

        </section>

    </div>
</section>
