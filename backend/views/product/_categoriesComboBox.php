<?php
/** @var \backend\models\Category[] $categories */
/** @var integer                    $value */
?>
<div class="form-group">
    <label>Category</label>
    <select name="category_id" class="form-control">
        <?= $this->render('_categoriesComboBoxItems', [
            'categories' => $categories,
            'value'      => $value,
        ]) ?>
    </select>
</div>
