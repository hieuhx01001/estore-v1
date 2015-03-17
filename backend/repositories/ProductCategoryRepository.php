<?php
namespace backend\repositories;

use backend\models\Category;
use backend\models\Product;
use backend\models\ProductCategory;

class ProductCategoryRepository
{
    const FIELD_ID          = 'id';
    const FIELD_PRODUCT_ID  = 'product_id';
    const FIELD_CATEGORY_ID = 'category_id';
    const FIELD_IS_MAIN     = 'is_main';

    /**
     * Save the new product, and link it with the category
     * having the passed-in category ID as its main category.
     * In addition, link the product with its main category's
     * ancestors.
     *
     * @param Product $product
     * @param integer $categoryId
     * @return bool
     */
    public function createProduct($product, $categoryId)
    {
        // TODO: Use transaction

        $product->save();

        $productId = $product->{static::FIELD_ID};

        /*
         * Link the product with the chosen category
         * as its main category
         */
        $productCategory = new ProductCategory();
        $productCategory->setAttributes([
            static::FIELD_PRODUCT_ID  => $productId,
            static::FIELD_CATEGORY_ID => $categoryId,
            static::FIELD_IS_MAIN     => true,
        ]);
        $productCategory->save();

        /*
         * Link the product with its main category's ancestors
         */
        $mainCategory = Category::findOne($categoryId);
        $ancestorCategory = $mainCategory->parent; // Retrieve the first (the nearest) ancestor

        while ($ancestorCategory) {
            $productCategory = new ProductCategory();
            $productCategory->setAttributes([
                static::FIELD_PRODUCT_ID  => $productId,
                static::FIELD_CATEGORY_ID => $ancestorCategory->id,
            ]);
            $productCategory->save();

            // Retrieve the higher ancestor of the current ancestor
            $ancestorCategory = $ancestorCategory->parent;
        }

        // Return TRUE after every processes are successful
        return true;
    }

    public function updateProduct($product, $categoryId)
    {
        // TODO: Use transaction

        $product->save();

        $productId = $product->{static::FIELD_ID};

        /*
         * 
         */
    }

}
