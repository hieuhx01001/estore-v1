<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        /*'enableAjaxValidation' => true,
        'validateOnSubmit'=>true,
        'validateOnChange' => false*/
    ]);
    ?>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Yii::t('app', 'Role'); ?>
            </div>
            <div class="panel-body">
                <?php
                echo $form->field($model, 'name')->textInput($model->isNewRecord ? [] : ['disabled' => 'disabled']) .
                     $form->field($model, 'description')->textarea(['style' => 'height: 100px']) .
                     Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), [
                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                     ]);
                ?>
            </div>

        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Yii::t('app', 'Permissions'); ?>
            </div>

            <div class="panel-body">

                <input type="hidden" name="Auth[_permissions]" value="">
<!--                <div id="auth-_permissions">-->
<!--                    <p>Product Permission:</p>-->
<!--                    <div class="margin">-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="viewProduct"> viewProduct (view a product)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="createProduct"> createProduct (create a product)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="updateProduct"> updateProduct (update product)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="deleteProduct"> deleteProduct (delete a product)</label>-->
<!--                    </div>-->
<!---->
<!--                    <p>Category Permission:</p>-->
<!--                    <div class="margin">-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="viewCategory"> viewCategory (view a category)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="createCategory"> createCategory (create a category)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="updateCategory"> updateCategory (update a category)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="deleteCategory"> deleteCategory (delete a category)</label>-->
<!--                    </div>-->
<!---->
<!--                    <p>Attribute Permission:</p>-->
<!--                    <div class="margin">-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="viewAttribute"> viewAttribute (view an attribute)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="createAttribute"> createAttribute (create an attribute)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="updateAttribute"> updateAttribute (update an attribute)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="deleteAttribute"> deleteAttribute (delete an Atrribute)</label>-->
<!--                    </div>-->
<!---->
<!--                    <p>Order Permission:</p>-->
<!--                    <div class="margin">-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="viewOrder"> viewOrder (view an order)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="updateOrder"> updateOrder (update an order)</label>-->
<!--                    </div>-->
<!---->
<!--                    <p>User Permission:</p>-->
<!--                    <div class="margin">-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="viewUser"> viewUser (view an user)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="createUser"> createUser (create an user)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="updateUser"> updateUser (update an user)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="deleteUser"> deleteUser (delete an user)</label>-->
<!--                    </div>-->
<!---->
<!--                    <p>Role Permission:</p>-->
<!--                    <div class="margin">-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="viewRole"> viewRole (view a role)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="createRole"> createRole (create a role)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="updateRole"> updateRole (update a role)</label>-->
<!--                        <label><input type="checkbox" name="Auth[_permissions][]" value="deleteRole"> deleteRole (delete a role)</label>-->
<!--                    </div>-->
<!--                </div>-->
                <?= $form->field($model, '_permissions')->checkboxList($permissions)->label('', ['hidden' => 'hidden']); ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
