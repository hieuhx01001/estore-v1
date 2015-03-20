<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $product backend\models\Product */
/* @var $attribute \backend\models\Attribute */
?>
<div class="form-group">
    <label><?= $attribute->name ?></label>
    <?= Html::input('number', 'attributes['.$attribute->id.']', $product->getAttrValue($attribute->id), [
        'class'     => 'form-control',
    ]) ?>
</div>
