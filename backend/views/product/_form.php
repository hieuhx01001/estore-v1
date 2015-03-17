<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\repositories\CategoryRepository;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $this->render('_categoriesComboBox', [
        'categories' => (new CategoryRepository())->getTopCategories(),
        'value'      => ($mainCategory = $model->mainCategory) ? $mainCategory->id : null,
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'short_description')->textarea([
        'rows'  => 6,
        'id'    => 'short_description'
    ]) ?>

    <?= $form->field($model, 'description')->textarea([
        'rows'  => 6,
        'id'    => 'description'
    ]) ?>

    <?= $form->field($model, 'feature')->textarea([
        'rows'  => 6,
        'id'    => 'feature'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- CKEditor Support -->
<script src="/vendor/ckeditor/ckeditor.js"></script>
<script>
    var ids = ["short_description", "description", "feature"];
    for (var i in ids) {
        CKEDITOR.replace(ids[i]);
    }
</script>
