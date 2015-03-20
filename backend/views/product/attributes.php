<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $product backend\models\Product */

$this->title = 'Product Attributes of ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $product->name;
$this->params['breadcrumbs'][] = 'Attributes';
?>

<div class="col-sm-6">
    <?php $form = ActiveForm::begin() ?>
        <?php foreach ($attributes as $attr) : ?>
            <?= $this->render('attribute_input/' . $attr->type, [
                'product' => $product,
                'attribute' => $attr,
            ]) ?>
        <?php endforeach ?>
        <div class="form-group">
            <button class="btn btn-primary">Save</button>
        </div>
    <?php $form->end() ?>
</div>
