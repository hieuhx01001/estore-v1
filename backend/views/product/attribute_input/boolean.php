<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $product backend\models\Product */
/* @var $attribute \backend\models\Attribute */
?>
<div class="form-group">
    <label><?= $attribute->name ?></label>
    <?= Html::radioList(
        'attributes['.$attribute->id.']',
        $product->getAttrValue($attribute->id) ? : 0,
        [
            0 => 'No',
            1 => 'Yes',
        ]
    ) ?>
</div>
