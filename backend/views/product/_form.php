<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\repositories\CategoryRepository;
use kartik\money\MaskMoney;
/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $attributes \backend\models\Attribute[] */
/* @var $manufacturers \backend\models\Manufacturer[] */
/* @var $suppliers \backend\models\Supplier[] */

$urlManager = Yii::$app->getUrlManager();
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_categoriesComboBox', [
            'categories' => (new CategoryRepository())->getTopCategories(),
            'value'      => ($mainCategory = $model->mainCategory) ? $mainCategory->id : null,
        ]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'manufacturer_id')->dropDownList(
            ArrayHelper::map($manufacturers, 'id', 'name')
        ) ?>

        <?= $form->field($model, 'supplier_id')->label('Supplier    ')->dropDownList(
            ArrayHelper::map($suppliers, 'id', 'name')
        ) ?>

        <?= $form->field($model, 'price')->textInput(['maxlength' => 10])
//            ->widget(MaskMoney::classname(), [
//                'pluginOptions' => [
//                    'prefix' => '',
//                    'suffix' => ' đ',
//                    'precision' => 0,
//                    'allowNegative' => false
//                ]
//            ]) ?>

        <?= $form->field($model, 'sales_price')->textInput(['maxlength' => 10])
             ?>

        <?= $form->field($model, 'quantity')->textInput() ?>

        <?= $form->field($model, 'short_description')->textarea([
            'rows'  => 4,
            'id'    => 'short_description'
        ]) ?>

        <?= $form->field($model, 'description')->textarea([
            'rows'  => 4,
            'id'    => 'description'
        ]) ?>

        <?= $form->field($model, 'feature')->textarea([
            'rows'  => 4,
            'id'    => 'feature'
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- CKEditor Support -->
<script src="<?= $urlManager->createUrl('vendor/ckeditor/ckeditor.js') ?>"></script>
<script>
    var ids = ["description", "feature"];
    for (var i in ids) {
        CKEDITOR.replace(ids[i]);
    }
</script>
