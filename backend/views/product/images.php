<?php
/** @var \backend\models\Product $product */
/** @var \backend\models\Image[] $images */

$urlManager = Yii::$app->getUrlManager();

// Register Breadcrumbs
$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Images';

// Register assets
\backend\assets\ProductImageAsset::register($this);
?>

<form method="post" action="<?= $urlManager->createUrl('product/add-image') ?>" enctype="multipart/form-data">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
    <input type="hidden" name="product_id" value="<?= $product->id ?>">
    <div class="form-group">
        <label>Select Image to Upload</label>
        <input type="file" name="image" class="form-control" required>
    </div>
    <div class="form-group">
        <button class="btn btn-primary">Add Image</button>
    </div>
</form>

<hr>

<div class="row">
    <?php $count = 0 ?>
    <?php foreach ($images as $img) : ?>
        <?php ++ $count ?>
        <div class="col-sm-4 col-md-4 col-lg-3">
            <div class="product-image-container <?= $img->is_main ? 'main' : '' ?>">
                <img src="<?= $urlManager->createUrl("img/product/{$img->product_id}/{$img->name}") ?>" alt="<?= $img->name ?>">
                <div class="btn-group">
                    <a href="<?= $urlManager->createUrl(['product/remove-image', 'id' => $img->id]) ?>"
                       class="btn btn-danger">Remove</a>
                    <a href="<?= $urlManager->createUrl(['product/set-main-image', 'product_id' => $product->id, 'image_id' => $img->id]) ?>"
                       <?= $img->is_main ? 'disabled' : '' ?>
                       class="btn btn-primary">Set main</a>
                </div>
            </div>
        </div>
        <?php if (3 === $count) : ?>
            <div class="clearfix visible-sm visible-md"></div>
        <?php elseif (4 === $count) : ?>
            <div class="clearfix visible-lg"></div>
        <?php endif ?>
    <?php endforeach ?>
</div>
