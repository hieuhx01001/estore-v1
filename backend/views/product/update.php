<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $manufacturers \backend\models\Manufacturer[] */
/* @var $suppliers \backend\models\Supplier[] */

$this->title = 'Update Product: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="product-update">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <div class="row">
        <!-- BEGIN: Left column - Product Form -->
        <div class="col-sm-6">
            <?= $this->render('_form', [
                'model' => $model,
                'categories' => $categories,
                'manufacturers' => $manufacturers,
                'suppliers' => $suppliers,
            ]) ?>
        </div><!-- BEGIN: Left column - Product Form -->
        <!-- BEGIN: Right column - Product Attributes & Product Images -->
        <div class="col-sm-6">
            <!-- BEGIN: Product Attributes table -->
            <?= $this->render('_product-attributes-table', [
                'model' => $model,
            ]) ?>
            <!-- BEGIN: Product Images table -->
            <?= $this->render('_product-images-table', [
                'model' => $model,
            ]) ?>
        </div><!-- END: Right column - Product Attributes & Product Images -->
    </div>

</div>
