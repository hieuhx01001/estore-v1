<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StoreDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'store_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'ower_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'address1')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'address2')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'address3')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'tax')->textInput(['maxlength' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
