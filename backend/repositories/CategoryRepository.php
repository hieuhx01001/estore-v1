<?php
namespace backend\repositories;

use backend\models\Category;
use backend\models\CategoryAttribute;

class CategoryRepository
{
    const FIELD_CATEGORY_ID = 'category_id';
    const FIELD_ATTRIBUTE_ID = 'attribute_id';

    /**
     * Retrieve all categories from database
     *
     * @return array|Category[]
     */
    public function getAll()
    {
        return Category::find()->all();
    }

    /**
     * Retrieve all top-level categories
     *
     * @return array|Category[]
     */
    public function getTopCategories()
    {
        return Category::find()->where(['=', 'level', 1])->all();
    }

    public function saveCategory(Category $category, $attributeIds)
    {
        // Check if this is a new Category or an existed one
        $isNew = $category->isNewRecord;

        // Save the category before continue
        $categorySavingResult = $category->save();

        // If this is an existed one, delete all current Category-Attribute links
        // of this Category before creating a new series of links
        if ($isNew === false) {
            CategoryAttribute::deleteAll([static::FIELD_CATEGORY_ID => $category->id]);
        }

        // Link the category with selected attributes
        if ($attributeIds) {
            foreach ($attributeIds as $attrId) {
                // Create a new instance of Category-Attribute link
                $ca = new CategoryAttribute();
                // Assign data
                $ca->setAttributes([
                    static::FIELD_CATEGORY_ID => $category->id,
                    static::FIELD_ATTRIBUTE_ID => $attrId,
                ]);
                // Save the link
                $caSavingResult = $ca->save();
            }
        }

        // Return TRUE after everything got done
        return true;
    }

}
