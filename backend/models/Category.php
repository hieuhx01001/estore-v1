<?php

namespace backend\models;

use backend\repositories\ProductCategoryRepository;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property integer $level
 *
 * @property Category $parent
 * @property Category[] $children
 * @property Category[] $subCategories
 * @property ProductCategory[] $productCategories
 * @property CategoryAttribute[] $categoryAttributes
 * @property Attribute[] $attrs
 * @property Attribute[] $fullAttributes
 * @property Category[] $allAncestors
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'level'], 'integer'],
            [['name', 'level'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'level' => 'Level',
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

            /*
             * Calculate current Category entity's level
             * before validating and saving.
             */

            // Default level (used when the current entity DOES NOT have parent)
            $level = 1;

            // If has parent, the entity's level will be the parent's level raised by 1
            if ($parentId = $this->parent_id) {
                $level = static::findOne($parentId)->level + 1;
            }

            // Assign the calculated level to the entity
            $this->level = $level;

            return true;
        }
        return false;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            $children = $this->subCategories;

            foreach ($children as $child) {
                $child->parent_id = $this->parent_id;
                $child->save();
            }

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * Get all ancestors of this category
     *
     * @return array
     */
    public function getAllAncestors()
    {
        $ancestors = [];

        $ancestor = $this->parent;

        while ($ancestor != null) {
            $ancestors[] = $ancestor;
            $ancestor = $ancestor->parent;
        }

        return $ancestors;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryAttributes()
    {
        return $this->hasMany(CategoryAttribute::className(), ['category_id' => 'id']);
    }

    /**
     * @return static
     */
    public function getAttrs()
    {
        return $this->hasMany(Attribute::className(), ['id' => 'attribute_id'])
            ->via('categoryAttributes');
    }

    /**
     * Retrieve not only this category's linked attributes,
     * but also its every ancestors' linked attributes.
     *
     * @return mixed
     */
    public function getFullAttributes()
    {
        // Retrieve its own attributes
        $attributes = $this->attrs;

        // Get the very first ancestor
        $parent = $this->parent;

        while ($parent) {
            // Retrieve the current ancestor's attributes
            $attributes = array_merge($attributes, $parent->attrs);

            // Point to the higher level ancestor (ancestor of current ancestor)
            $parent = $parent->parent;
        }

        return $attributes;
    }

    /**
     * Check if this category is a descendant of
     * the category having the passed-in Category ID
     *
     * @param $categoryId
     * @return bool
     * @throws Exception
     */
    public function isDescendantOf($categoryId)
    {
        $category = Category::findOne($categoryId);

        if (is_null($category)) {
            throw new Exception('Not found Category having ID = ' . $categoryId);
        }

        $ancestor = $this->parent;

        while ($ancestor) {
            if ($ancestor->id == $categoryId) {
                return true;
            }
            $ancestor = $ancestor->parent;
        }

        return false;
    }

    /**
     * If this category is linked to the requested product,
     * set this category as main, and unset the current main one.
     *
     * @param $productId
     * @throws Exception
     */
    public function setAsMainCategoryOf($productId)
    {
        /*
         * Unset the current main category as main
         */
        $curMainPCLink = ProductCategory::findOne([
            'product_id' => $productId,
            'is_main' => 1,
        ]);

        if ($curMainPCLink != null) {

            $curMainPCLink->is_main = 0;

            if ($curMainPCLink->save() == false) {
                throw new Exception('Failed to unset the current main category');
            }
        }

        /*
         * Set this category as main
         */
        $thisPCLink = ProductCategory::findOne([
            'product_id' => $productId,
            'category_id' => $this->id,
        ]);

        if ($thisPCLink == null) {
            throw new Exception('This category is currently not linked to the requested product');
        }

        $thisPCLink->is_main = true;

        if ($thisPCLink->save() == false) {
            throw new Exception('Failed to set this category as main');
        }
    }

    /**
     * Link this category with the requested product.
     *
     * If $errorIfCurrentlyLinked set to true, and
     * is currently linked, throw exception.
     *
     * @param $productId
     * @param bool $errorIfCurrentlyLinked
     * @throws Exception
     */
    public function linkToProduct($productId, $errorIfCurrentlyLinked = false)
    {
        $pc = ProductCategory::findOne([
            'product_id' => $productId,
            'category_id' => $this->id,
        ]);

        $isLinked = ($pc != null);

        if ($isLinked and $errorIfCurrentlyLinked) {
            throw new Exception('This category is currently linked to the requested product');
        }
        else {
            $pc = new ProductCategory();
            $pc->setAttributes([
                'product_id' => $productId,
                'category_id' => $this->id,
            ]);

            // TODO: Check this!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            if ($pc->save() == false) {
//                throw new Exception('Failed to link this category with the requested product');
            }
        }
    }

    /**
     * Unlink this category and the requested product
     *
     * @param $productId
     * @throws Exception
     * @throws \Exception
     */
    public function unlinkWithProduct($productId)
    {
        $pc = ProductCategory::findOne([
            'product_id' => $productId,
            'category_id' => $this->id,
        ]);

        if ($pc) {
            if ($pc->delete() == false) {
                throw new Exception('Failed to unlink this category with the requested product');
            }
        }
    }

    /**
     * Retrieve a list of categories that is selectable
     * to be the parent of the passed in category.
     *
     * @param $category
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getSelectableParents($category)
    {
        // Build a query
        $query = static::find();

        // Get children category ids
        $childrenIds = [];
        foreach ($category->children as $child) {
            $childrenIds[] = $child->id;
        }

        // Query excepts the given Category entity if it exists, and also its children
        if ($entityId = $category->id) {
            $query->where([
                'AND',
                ['!=', 'id', $entityId],
                ['NOT IN', 'id', $childrenIds],
            ]);
        }

        // Execute the query, and retrieve the result
        $categories = $query->all();

        // Put an non-existent Category at the head of the list
        $nonExistentCategory = new Category();
        $nonExistentCategory->setAttributes([
            'id'    => null,
            'name'  => '--- No Parent ---',
        ]);
        array_unshift($categories, $nonExistentCategory);

        return $categories;
    }

    /**
     * Find the common ancestor of the 2 category passed-in
     *
     * @param $categoryId1
     * @param $categoryId2
     * @return null
     */
    public static function getCommonAncestor($categoryId1, $categoryId2)
    {
        $commonAncestor = null;

        $cate1 = Category::findOne($categoryId1);
        $cate2 = Category::findOne($categoryId2);

        $ancestors1 = $cate1->getAllAncestors();
        $ancestors2 = $cate2->getAllAncestors();

        foreach ($ancestors1 as $curAncestor1) {
            foreach ($ancestors2 as $curAncestor2) {
                if ($curAncestor1->id == $curAncestor2->id) {
                    $commonAncestor = $curAncestor1;
                    break;
                }
            }
        }

        return $commonAncestor;
    }
}
