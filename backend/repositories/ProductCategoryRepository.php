<?php
namespace backend\repositories;

use backend\models\Category;
use backend\models\Product;
use backend\models\ProductCategory;
use yii\base\Exception;
use yii\db\Transaction;

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

    /**
     * Save all changes of an existing product. If its category
     * is changed, execute processes to change its Product-Category
     * links.
     *
     * @param Product $product
     * @param integer $newCateId
     * @return bool
     * @throws Exception
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function updateProduct($product, $newCateId)
    {
        // Create a new TRANSACTION
        $transaction = null;

        try {
            // Start the TRANSACTION
            $transaction = \Yii::$app->db->beginTransaction();

            $product->save();

            $productId = $product->{static::FIELD_ID};
            $curCate = $product->mainCategory;
            $newCate = Category::findOne($newCateId);

            // Check is product's category is changed
            $isCategoryChanged = ($product->mainCategory->id != $newCateId);

            /*
             * ONLY execute these Product-Category links changes
             * if the product's main-category is changed
             */
            if ($isCategoryChanged) {
                /*
                 * 1 - If newCate is descendant of curCate
                 */
                if ($newCate->isDescendantOf($curCate->id)) {
                    // 1.1 - Link the product with the new category
                    $newCate->linkToProduct($productId);

                    // 1.2 - Set the new category is main category
                    //       (This will also unset the current main one)
                    $newCate->setAsMainCategoryOf($productId);

                    // 1.3 - Link the product with the new category's ancestors
                    //       until hit the current category (currently linked
                    //       and is ancestor)
                    $ancestor = $newCate->parent;

                    while ($ancestor != null and $ancestor->id != $newCateId) {
                        $ancestor->linkToProduct($productId);
                        $ancestor = $ancestor->parent;
                    }
                }
                /*
                 * 2. If newCate is ancestor of curCate
                 */
                elseif ($curCate->isDescendantOf($newCateId)) {
                    // 2.1 - Unlink the product and the current main category
                    $curCate->unlinkWithProduct($productId);

                    // 2.2 - Unlink all ancestors of the current main category
                    //       until hit the new category (this new is an ancestor
                    //       and will be set to be main right after this step)
                    $ancestor = $curCate->parent;

                    while ($ancestor != null and $ancestor->id != $newCateId) {
                        $ancestor->unlinkWithProduct($productId);
                        $ancestor = $ancestor->parent;
                    }

                    // 2.3 - Set the new category as main
                    $newCate->setAsMainCategoryOf($productId);
                }
                /*
                 * 3. newCate and curCate is on different branch
                 */
                else {
                    // 3.1 - Find the common ancestor of the new and the current categories.
                    //       The common one can be null if the 2 are absolutely on different
                    //       branches from the very root.
                    $commonAncestor = Category::getCommonAncestor($newCateId, $curCate->id);

                    // 3.2 - Unlink the current category and its ancestors until hit the common
                    $curCate->unlinkWithProduct($productId);

                    $ancestor = $curCate->parent;
                    while (($commonAncestor == null and $ancestor != null)
                        or ($commonAncestor != null and $ancestor != null and $ancestor->id != $commonAncestor->id)) {
                        $ancestor->unlinkWithProduct($productId);
                        $ancestor = $ancestor->parent;
                    }

                    // 3.3 - Link the new category and its ancestors until hit the common
                    $newCate->linkToProduct($productId);

                    $ancestor = $newCate->parent;
                    while (($commonAncestor == null and $ancestor != null)
                        or ($commonAncestor != null and $ancestor != null and $ancestor->id != $commonAncestor->id)) {
                        $ancestor->linkToProduct($productId);
                        $ancestor = $ancestor->parent;
                    }

                    // 3.4 - Set the new category as main
                    $newCate->setAsMainCategoryOf($productId);
                }
            }

            // Works done, commit the transaction
            $transaction->commit();

            // Return TRUE after every processes are successful
            return true;
        }
        catch (Exception $ex) {
            // Error happened, roll the fucking back the god-damned transaction
            $transaction->rollBack();

            throw $ex;
        }
    }

}
