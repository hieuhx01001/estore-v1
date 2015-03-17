<?php
/** @var \backend\models\Category[] $categories */
/** @var \backend\models\Category   $category */
/** @var integer                    $value */
?>
<?php foreach ($categories as $category) : ?>

    <option value="<?= $category->id ?>"
            <?= $category->id == $value ? 'selected' : '' ?>>
        <?= $category->name ?>
    </option>

    <?php if (count($subCategories = $category->subCategories)) : ?>

        <?= $this->render('_categoriesComboBoxItems', [
            'categories' => $category->subCategories,
            'value'      => $value,
        ]) ?>

    <?php endif ?>

<?php endforeach ?>
