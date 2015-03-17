<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $price
 * @property integer $quantity
 * @property string $description
 * @property string $short_description
 * @property string $feature
 *
 * @property ProductCategory[] $productCategories
 * @property ProductCategory $mainProductCategory
 * @property Category $mainCategory
 */
class Product extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'price', 'quantity'], 'required'],
            [['price'], 'number'],
            [['quantity'], 'integer'],
            [['description', 'short_description', 'feature'], 'string'],
            [['name', 'code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'description' => 'Description',
            'short_description' => 'Short Description',
            'feature' => 'Feature',
        ];
    }

    public function afterSave($insert, array $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainProductCategory()
    {
        return $this->getProductCategories()->where(['=', 'is_main', true]);
    }

    /**
     * @return Category
     */
    public function getMainCategory()
    {
        return ($mainProductCategory = $this->mainProductCategory) ?
            $mainProductCategory->category : null;
    }
}
