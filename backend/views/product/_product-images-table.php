<?php
/** @var \backend\models\Product $model */

$urlManager = Yii::$app->getUrlManager();

$productImages = $model->images;
?>

<!-- BEGIN: Product-Images data -->
<div class="panel panel-primary">
    <div class="panel-heading">
                    <span class="panel-title">
                        <i class="fa fa-picture-o"></i> Product Images
                    </span>
    </div>
    <?php if (empty($productImages)) : ?>
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
