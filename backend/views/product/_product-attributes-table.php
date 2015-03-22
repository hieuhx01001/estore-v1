<?php
/** @var \backend\models\Product $model */

$urlManager = Yii::$app->getUrlManager();
?>

<!-- BEGIN: Product-Attributes data -->
<div class="panel panel-primary">
    <div class="panel-heading">
                    <span class="panel-title">
                        <i class="fa fa-tags"></i> Product Attributes
                    </span>
    </div>
    <?php if (empty($productAttributes = $model->productAttributes)) : ?>
        <div class="panel-body">
            <p>
                This product currently has no attributes.
                Its because the product's main category and
                every ancestor categories has no attributes.
            </p>
            <p>
                Edit this product's main category or any
                of the ancestor categories to add attributes.
            </p>
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
