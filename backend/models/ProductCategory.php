<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_category".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $category_id
 * @property integer $is_main
 *
 * @property Category $category
 * @property Product $product
 */
class ProductCategory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'category_id'], 'required'],
            [['product_id', 'category_id', 'is_main'], 'integer'],
            [['product_id', 'category_id'], 'unique', 'targetAttribute' => ['product_id', 'category_id'], 'message' => 'The combination of Product ID and Category ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'category_id' => 'Category ID',
            'is_main' => 'Is Main',
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();

        /*
         * Remove all Product-Attribute links of this Category
         * after being deleted successfully
         */

        $categoryId = $this->category_id;
        $productId = $this->product_id;

        // Find all attributes that the category this Product-Category link to has
        $attributes = Category::findOne($categoryId)
            ->getAttrs()
            ->select('id')
            ->all();

        // Start unlink
        foreach ($attributes as $attr) {
            $pa = ProductAttribute::findOne([
                'product_id' => $productId,
                'attribute_id' => $attr->id,
            ]);

            if ($pa === null) { continue; }

            if ($pa->delete() == false) {
                throw new Exception("Failed to delete Product-Attribute link of pId={$productId} and aId={$attr->id}");
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
