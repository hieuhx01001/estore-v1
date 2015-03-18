<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="table-responsive">
    <table class="table">
        <tr>
            <th style="width:50%; vertical-align: middle">Order Status</th>
            <td><?= $form->field($model, 'order_status',array('options' => ['class' => 'order-form']))
                    ->dropDownList(\backend\models\Order::getArrayStatus())
                    ->label(false)->error(false) ?>
            </td>
        </tr>
        <tr>
            <th style="width:50%; vertical-align: middle">Payment Status</th>
            <td><?= $form->field($model->invoice, 'invoice_status',array('options' => ['class' => 'order-form']))
                    ->dropDownList(\backend\models\Order::getInvoiceStatus())
                    ->label(false)->error(false) ?>
            </td>
        </tr>
        <tr>
            <th style="width:50%; vertical-align: middle">Shipment Status</th>
            <td><?= $form->field($model, 'shipment_status',array('options' => ['class' => 'order-form']))
                    ->dropDownList(\backend\models\Order::getArrayShipmentStatus())
                    ->label(false)->error(false) ?>
            </td>
        </tr>
    </table>
</div>
<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
<button type="submit" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Update</button>
<!--                <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Cancel</button>-->
<?= Html::a('Cancel', ['/order/index'], ['class'=>'btn btn-primary pull-right', 'style' => 'margin-right: 5px;',]) ?>
<?php \yii\widgets\ActiveForm::end(); ?>
