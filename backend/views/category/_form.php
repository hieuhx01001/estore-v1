<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
/* @var $attributes \backend\models\Attribute[] */
/* @var $selectedAttributeIds array */

$urlManager = Yii::$app->urlManager;
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(
        ArrayHelper::map(\backend\models\Category::getSelectableParents($model), 'id', 'name'),
        [
            'id' => 'cmb-parent-id'
        ]
    ) ?>

    <div class="form-group">
        <label>Category Attributes</label>
        <?= Html::checkboxList(
            'attribute_ids',
            $selectedAttributeIds,
            ArrayHelper::map($attributes, 'id', 'name'),
            [
                'style' => 'display: block;',
                'id'    => 'attribute-list',
            ]
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- TODO: Remove this jQuery import script -->
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

<script>
    $(function () {

        var cmbParentId = $("#cmb-parent-id");

        /*
         * Collect currently selected attributes of this category
         */
        var curSelectedAttributes = (function () {

            attributeIds = [];

            $("#attribute-list input[type='checkbox']").each(function (idx, item) {
                if (item.checked) {
                    attributeIds.push(parseInt($(item).val()));
                }
            });

            return attributeIds;
        })();

        cmbParentId.change(function (evt) {

            var parentId = $(this).val();
            var attributeListDiv = $("#attribute-list");

            $.ajax({
                url: "<?= $urlManager->createUrl(['ajax/category/get-allowed-attributes']) ?>",
                type: "GET",
                data: { parent_id: parentId },
                success: function (data) {
                    if (data.success) {
                        // Clear attribute list
                        attributeListDiv.empty();

                        // Generate a new attribute list
                        data.attributes.forEach(function (item) {

                            var checkbox = $("<input>")
                                .attr("type", "checkbox")
                                .attr("name", "attribute_ids[]")
                                .val(item.id);

                            if (curSelectedAttributes.indexOf(item.id) > -1) {
                                checkbox.attr("checked", "checked");
                            }

                            attributeListDiv.append($("<label>")
                                    .append(checkbox)
                                    .append(" " + item.name)
                            )
                        });
                    }
                    else {
                        alert(data.message);
                    }
                },
                error: function (data) {
                    alert("Error happened on server");
                    console.log(data);
                }
            });
        });

        cmbParentId.change();
    });
</script>
