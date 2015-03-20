<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property integer $level
 *
 * @property Category $parent
 * @property Category[] $subCategories
 * @property ProductCategory[] $productCategories
 * @property CategoryAttribute[] $categoryAttributes
 * @property Attribute[] $attrs
 * @property Attribute[] $fullAttributes
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

        // Query excepts the given Category entity if it exists
        if ($entityId = $category->id) {
            $query->where('`id` != :entityId', [':entityId' => $entityId]);
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
}
