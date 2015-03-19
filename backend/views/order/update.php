<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Order */
/* @var $model backend\models\Invoice */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', '{modelClass} #: ', [
    'modelClass' => 'Order',
]) . ' ' . $model->order_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_code];
?>
<div class="order-update">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> EStore, Inc.
                <small class="pull-right">Date: <?php echo Yii::$app->formatter->asDate($model->order_date);  ?></small>
            </h2>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            From
            <address>
                <strong><?php echo $modelStoreDetail->store_name?></strong><br>
                <?php if ($modelStoreDetail->address1 != ''): ?><?php echo $modelStoreDetail->address1; ?><br><?php endif; ?>
                <?php if ($modelStoreDetail->address2 != ''): ?><?php echo $modelStoreDetail->address2; ?><br><?php endif; ?>
                <?php if ($modelStoreDetail->address3 != ''): ?><?php echo $modelStoreDetail->address3; ?><br><?php endif; ?>
                Phone: <?php if ($modelStoreDetail->phone != ''): ?><?php echo $modelStoreDetail->phone; ?><br><?php endif; ?>
                Email: <?php echo $modelStoreDetail->email; ?>
            </address>
        </div><!-- /.col -->
        <div class="col-sm-4 invoice-col">
            To
            <address>

                <strong><?php echo $model->customer->name ?></strong><br>
                <?php if ($model->customer->address1 != ''): ?><?php echo $model->customer->address1; ?><br>
                <?php endif; ?>
                <?php if ($model->customer->address2 != ''): ?><?php echo $model->customer->address2; ?><br>
                <?php endif; ?>
                <?php if ($model->customer->address3 != ''): ?><?php echo $model->customer->address3; ?><br>
                <?php endif; ?>
                Phone: <?php if ($model->customer->phone != ''): ?><?php echo $model->customer->phone; ?><br>
                <?php endif; ?>
                Email: <?php echo $model->customer->email; ?>
            </address>
        </div><!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice #<?php echo $model->invoice->invoice_code?></b><br/>
            <b>Order ID:</b> <?php echo $model->order_code ?><br/>
            <b>Order Date :</b> <?php echo Yii::$app->formatter->asDate($model->order_date);  ?><br/>
            <b>Customer:</b> <?php echo $model->customer->name?>
        </div><!-- /.col -->
    </div><!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Qty</th>
                    <th>Product</th>
                    <th>Serial #</th>
                    <th>Description</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0?>
                <?php foreach($model->orderItems as $orderItem){ ?>
                    <tr>
                        <td><?php echo $orderItem->order_item_quantity; ?></td>
                        <td><?php echo $orderItem->product->name; ?></td>
                        <td><?php echo $orderItem->product->code; ?></td>
                        <td><?php echo $orderItem->product->description; ?></td>
                        <td><?php echo Yii::$app->formatter->asCurrency($orderItem->order_item_price) ?></td>
                    </tr>
                    <?php $total = $total + ($orderItem->order_item_price * $orderItem->order_item_quantity)  ?>
                <?php } ?>
                </tbody>
            </table>
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">

            <div class="col-xs-12">
                <p class="lead">Status</p>
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div><!-- /.col -->
        <div class="col-xs-6">
            <p class="lead">Total Price</p>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td><?php echo Yii::$app->formatter->asCurrency($total)?></td>
                    </tr>
                    <tr>
                        <?php $tax = ($total * $model->tax)/100?>
                        <th>Tax (<?php echo $model->tax?>%)</th>
                        <td><?php echo Yii::$app->formatter->asCurrency($tax)?></td>
                    </tr>
                    <tr>
                        <th>Shipping:</th>
                        <td><?php echo Yii::$app->formatter->asCurrency($model->shipment_price) ?></td>
                    </tr>
                    <tr>
                        <?php $totalFinal = $total + $tax + $model->shipment_price  ?>
                        <th>Total:</th>
                        <td><?php echo Yii::$app->formatter->asCurrency($totalFinal) ?></td>
                    </tr>

                </table>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->

</div>
