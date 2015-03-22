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

    <h1><?= Html::encode($this->title) ?></h1>

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
            <!-- BEGIN: Product-Attributes data -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="panel-title">
                        <i class="fa fa-tags"></i> Product Attributes
                    </span>
                </div>
                <?php if (empty($productAttributes = $model->productAttributes)) : ?>
                    <div class="panel-body">
                        This product currently has no attributes.
                        Its because the product's main category and
                        every ancestor categories has no attributes.
                    </div>
                <?php else : ?>
                    <table class="table table-bordered table-striped table-responsive">
                        <tbody>
                        <?php foreach ($productAttributes as $pa) : ?>
                            <tr>
                                <th><?= $pa->attr->name ?></th>
                                <td><?= $pa->value ?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="panel-footer text-right">
                        <a href="<?= $urlManager->createUrl("product/attributes/{$model->id}") ?>"
                           class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                <?php endif ?>
            </div><!-- END: Product-Attributes data -->
            <!-- BEGIN: Product-Images data -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="panel-title">
                        <i class="fa fa-picture-o"></i> Product Images
                    </span>
                </div>
                <?php if (empty($productImages = $model->images)) : ?>
                    <div class="panel-body">
                        This product currently has no images.
                        Click <kbd>Edit</kbd> button below to add images.
                    </div>
                <?php else : ?>
                    <div class="panel-body">
                        <?php $count = 0 ?>
                        <?php foreach ($productImages as $img) : ?>
                            <?php ++ $count ?>
                            <?php if (1 === $count % 2) : ?><div class="row"><?php endif ?>
                            <div class="col-xs-6" style="margin-bottom: 15px;">
                                <img src="<?= $urlManager->createUrl("img/product/{$img->product_id}/{$img->name}") ?>">
                            </div>
                            <?php if (0 === $count % 2) : ?></div><?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
                <div class="panel-footer text-right">
                    <a href="<?= $urlManager->createUrl("product/images/{$model->id}") ?>"
                       class="btn btn-primary">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </div>
            </div><!-- END: Product-Images data -->
        </div><!-- END: Product-Attributes & Product Images -->
    </div>

</div>

<!--<style>-->
<!--    .table td {-->
<!--        overflow: hidden;-->
<!--    }-->
<!--</style>-->
