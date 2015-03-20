<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
/* @var $attributes \backend\models\Attribute[] */
/* @var $selectedAttributeIds array */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(
        ArrayHelper::map(\backend\models\Category::getSelectableParents($model), 'id', 'name')
    ) ?>

    <div class="form-group">
        <label>Category Attributes</label>
        <?= Html::checkboxList(
            'attribute_ids',
            $selectedAttributeIds,
            ArrayHelper::map($attributes, 'id', 'name'),
            [
                'style' => 'display: block;'
            ]
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
