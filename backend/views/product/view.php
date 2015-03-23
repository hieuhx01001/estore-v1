<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */

$urlManager = Yii::$app->getUrlManager();

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <!-- BEGIN: Product data column -->
        <div class="col-sm-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'code',
                    'mainCategory.name',
                    'manufacturer.name',
                    'supplier.name',
                    'price',
                    'quantity',
                    'description:ntext',
                    'short_description:ntext',
                    'feature:ntext',
                ],
            ]) ?>
        </div><!-- END: Product data column -->
        <!-- BEGIN: Product-Attributes & Product Images -->
        <div class="col-sm-6">
            <!-- BEGIN: Product Attributes table -->
            <?= $this->render('_product-attributes-table', [
                'model' => $model,
            ]) ?>
            <!-- BEGIN: Product Images table -->
            <?= $this->render('_product-images-table', [
                'model' => $model,
            ]) ?>
        </div><!-- END: Product-Attributes & Product Images -->
    </div>

</div>

<!--<style>-->
<!--    .table td {-->
<!--        overflow: hidden;-->
<!--    }-->
<!--</style>-->
