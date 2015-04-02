<?php
$urlManager = Yii::$app->getUrlManager();
?>
<div id="outerheader">
    <header>
        <div id="top">
            <div class="container">
                <div class="row">
                    <div id="topmenu" class=" six columns">
                        <ul id="topnav">
                            <li><a href="<?php $urlManager->createUrl("site/intro")?>">Giới Thiệu</a></li>
                            <li><a href="#">Liên Hệ</a></li>
                            <li><a href="#">Sơ Đồ</a></li>
                        </ul>
                    </div>
                    <div id="topright" class="six columns">
                        <div class="clear"></div>
                        <div class="login"><a href="login.html" style="color: #ffffff;"><strong>Đăng nhập</strong></a> / <a href="register.html" style="color: #ffffff"><strong>Đăng ký</strong></a></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="logo-wrapper">
            <div class="container">
                <div class="row">
                    <div id="logo" class="six columns">
                        <a href="index.html"></a>
                        <span class="desc">LINH KIỆN ĐIỆN TỬ VIỆT NAM</span>
                    </div>
                    <div class="right six columns">

                        <form action="#" id="searchform" method="get">

                            <input type="text" onblur="if (this.value == '')this.value = 'Nhập để tìm kiếm';" onfocus="if (this.value == 'Nhập để tìm kiếm')this.value = '';" value="Nhập để tìm kiếm" id="s" name="s" class="field">
                            <input type="submit" value="" class="searchbutton">

                        </form>

                        <div id="shopping-cart-wrapper">
                            <div id="shopping_cart"><a href="#" id="shop-bag">Giỏ Hàng : (2) sản phẩm</a><a class="btncart" href="#"></a>
                                <ul class="shop-box">
                                    <li>
                                        <h2>TL084 - DIP14</h2>
                                        <div class="price">1 x 150.000</div>
                                        <div class="clear"></div>
                                    </li>
                                    <li>
                                        <h2>TRANSISTOR NPN</h2>
                                        <div class="price">1 x 750.000</div>
                                        <div class="clear"></div>
                                    </li>
                                    <li class="total">
                                        <h2>Tổng</h2>
                                        <div class="price"> 900.000</div>
                                        <div class="clear"></div>
                                    </li>
                                    <li class="btn-wrapper">
                                        <a href="#" class="cart">Giỏ hàng</a> <a href="#" class="checkout">Thanh toán</a>
                                        <div class="clear"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <section id="navigation">
            <div class="container">
                <div class="row">
                    <nav id="nav-wrap" class="twelve columns">
                        <ul id="sf-nav" class="sf-menu">
                            <li class="<?php if ($page == 'dashboard') {echo 'current';}?>"><a href="<?php echo $urlManager->createUrl("site/index")?>">Trang chủ</a></li>
                            </li>
                            <li class="<?php if ($page == 'product') {echo 'current';}?>"><a href="<?php echo $urlManager->createUrl("site/product")?>">Sản Phẩm</a>
                                <ul>
                                <li><a href="product-grid.html">Product Grid</a></li>
                                <li><a href="product-list.html">Product List</a></li>
                                <li><a href="product-details.html">Product Details</a></li>
                                <li><a href="checkout.html">Checkout</a></li>
                                </ul>
                            </li>
                            <li><a href="portfolio.html">Tin Tức</a></li>
                            <li><a href="contact.html">Liên Hệ</a></li>
                        </ul><!-- topnav -->
                    </nav><!-- nav -->
                </div>
            </div>
        </section>

        <div class="clear"></div>
    </header>
</div>
