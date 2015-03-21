<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Product Attributes', ['attributes', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= HTml::a('Product Images', ['images', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-sm-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'code',
                    'mainCategory.name',
                    'price',
                    'quantity',
                    'description:ntext',
                    'short_description:ntext',
                    'feature:ntext',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <table class="table table-bordered table-striped">
                <?php foreach ($model->productAttributes as $pa) : ?>
                    <tr>
                        <td><?= $pa->attr->name ?></td>
                        <td><?= $pa->value ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>

</div>
