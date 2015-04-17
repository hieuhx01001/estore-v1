<?php
/* @var $this yii\web\View */
/* @var $orderData backend\models\Order */
/* @var $productData backend\models\Product */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'My Yii Application';
$urlMng = Yii::$app->getUrlManager();
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <a href="<?php echo $urlMng->createUrl('product/index')?>"><span class="info-box-icon bg-yellow"><i class="fa fa-plus-square"></i></span></a>
                <div class="info-box-content">
                    <span class="info-box-text">Total Products</span>
                    <span class="info-box-number"><?php echo $totalProduct?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <a href="<?php echo $urlMng->createUrl('order/index')?>"><span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span></a>
                <div class="info-box-content">
                    <span class="info-box-text">Total Orders</span>
                    <span class="info-box-number"><?php echo $totalOrder ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <a href="<?php echo $urlMng->createUrl('customer/index')?>"><span class="info-box-icon bg-red"><i class="ion ion-person-stalker"></i></span></a>
                <div class="info-box-content">
                    <span class="info-box-text">Customers</span>
                    <span class="info-box-number"><?php echo $totalCustomer?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sales</span>
                    <span class="info-box-number">760</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <!-- TABLE: LATEST ORDERS -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Orders</h3>
                </div><!-- /.box-header -->
                <div class="box-body"">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Status</th>
                                <th>Order Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($orderData as $order){ ?>
                                <tr>
                                    <td><a href="pages/examples/invoice.html"><?php echo $order->order_code?></a></td>
                                    <td><?php echo $order->customer->name?></td>
                                    <?php
                                    if ($order->order_status === $order::STATUS_NEW) {
                                        $class = 'label-success';
                                    } elseif ($order->order_status === $order::STATUS_PROCESSING) {
                                        $class = 'label-warning';
                                    }elseif ($order->order_status === $order::STATUS_FINISH) {
                                        $class = 'label-info';
                                    }
                                    else {
                                        $class = 'label-danger';
                                    }
                                    ?>
                                    <td><span class="label <?php echo $class ?>"><?php echo $order->statusLabel?></span></td>
                                    <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo Yii::$app->formatter->asDate($order->order_date)?> </div></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <a href="<?php echo $urlMng->createUrl('order/index')?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
                </div><!-- /.box-footer -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <!-- PRODUCT LIST -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Recently Added Products</h3>
                </div><!-- /.box-header -->
                <div class="box-body" style="padding-bottom: 17px;">
                    <ul class="products-list product-list-in-box">
                        <?php foreach($productData as $product){ ?>
                            <li class="item">
                                <div class="product-img">
                                    <?php
                                        $imgLink = '';
                                        $mainImage = $product->mainImage;
                                        if(is_null($mainImage)){
                                            $imgLink = $urlMng->createUrl('img/product/default.jpg');
                                        } else {
                                            $imgLink = $urlMng->createUrl("img/product/{$product->id}/{$product->mainImage->name}");
                                        }
                                    ?>

                                    <img src="<?php echo $imgLink ?>" style="width: 50px;height: 50px" alt="Product Image"/>
                                </div>
                                <div class="product-info">
                                    <a href="<?php echo $urlMng->createUrl(['product/view', 'id' => $product->id]); ?>" class="product-title"><?php echo $product->name?><span class="label label-success pull-right"><?php echo Yii::$app->formatter->asCurrency($product->price) ?></span></a>
                                    <span class="product-description">
                                      <?php echo $product->short_description == '' ? '(no description)' : $product->short_description ?>
                                    </span>
                                </div>
                            </li><!-- /.item -->
                        <?php } ?>
                    </ul>
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                    <a href="<?php echo $urlMng->createUrl('product/index')?>" class="uppercase">View All Products</a>
                </div><!-- /.box-footer -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</div>
