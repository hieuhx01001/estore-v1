<?php
namespace backend\repositories;

use backend\models\Category;
use backend\models\CategoryAttribute;
use backend\models\ProductAttribute;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

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

    public function saveCategory(Category $category, $selectedAttrIds)
    {
        if ($selectedAttrIds == null) {
            $selectedAttrIds = [];
        }

        // Check if this is a new Category or an existed one
        $isNew = $category->isNewRecord;

        // Start new transaction
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            // Save the category before continue
            if ($category->save() == false) {
                throw new Exception('Failed to save category');
            }

            // If this is an existed one, delete all current Category-Attribute links
            // of this Category before creating a new series of links
            if ($isNew === false) {
                CategoryAttribute::deleteAll([static::FIELD_CATEGORY_ID => $category->id]);
            }

            /*
             * Remove any selected category that ancestors have
             */
            if (! empty($selectedAttrIds)) {
                // Get IDs of ancestors' attributes
                $ancestorAttrIds = [];
                foreach ($category->allAncestors as $ancestor) {
                    $ancestorAttrIds = array_merge($ancestorAttrIds, ArrayHelper::getColumn($ancestor->attrs, 'id'));
                }
                // Filter attributes
                $selectedAttrIds = array_diff($selectedAttrIds, $ancestorAttrIds);

                // Link the category with selected attributes
                if ($selectedAttrIds) {
                    foreach ($selectedAttrIds as $attrId) {
                        // Create a new instance of Category-Attribute link
                        $ca = new CategoryAttribute();
                        // Assign data
                        $ca->setAttributes([
                            static::FIELD_CATEGORY_ID => $category->id,
                            static::FIELD_ATTRIBUTE_ID => $attrId,
                        ]);
                        // Save the link
                        if ($ca->save() == false) {
                            throw new Exception("Failed to save Category[{$category->id}]-Attribute[{$attrId}] link");
                        }
                    }
                }
            }

            // Update Product-Attribute links
            $productIds = [];
            foreach ($category->productCategories as $pc) {
                $productIds[] = $pc->product_id;
            }

            $attrIds = array_merge([], $selectedAttrIds);

            foreach ($category->children as $child) {
                foreach ($child->attrs as $attr) {
                    $attrIds[] = $attr->id;
                }
            }

            foreach ($category->allAncestors as $ancestor) {
                foreach ($ancestor->attrs as $attr) {
                    $attrIds[] = $attr->id;
                }
            }

            ProductAttribute::deleteAll([
                'AND',
                ['IN', 'product_id', $productIds],
                ['NOT IN', 'attribute_id', $attrIds],
            ]);

            // Commit transaction after everything got done
            $transaction->commit();
        }
        catch (Exception $ex) {
            // Rollback transaction
            $transaction->rollBack();

            throw $ex;
        }

        // Return TRUE after everything got done
        return true;
    }

}
