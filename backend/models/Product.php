<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property double $price
 * @property double $sales_price
 * @property integer $quantity
 * @property string $description
 * @property string $short_description
 * @property string $feature
 *
 * @property double $finalPrice
 *
 * @property OrderItem[] $orderItems
 * @property ProductCategory[] $productCategories
 * @property ProductCategory $mainProductCategory
 * @property Category $mainCategory
 * @property ProductAttribute[] $productAttributes
 * @property Image[] $images
 * @property Image $mainImage
 * @property Manufacturer $manufacturer
 * @property Supplier $supplier
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
            [['price', 'sales_price'], 'number', 'min'=>0],
            [['quantity', 'manufacturer_id', 'supplier_id'], 'integer', 'min'=>0],
            [['description', 'short_description', 'feature'], 'string'],
            [['name'],'string', 'max' => 255],
            [['code'], 'unique'],
            [['code'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'price' => Yii::t('app', 'Price'),
            'sales_price' => Yii::t('app', 'Sales Price'),
            'quantity' => Yii::t('app', 'Quantity'),
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short Description'),
            'feature' => Yii::t('app', 'Feature'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Last Updated At'),

            'manufacturer_id' => 'Manufacturer',
            'supplier' => 'Supplier',

            'mainCategory.name' => Yii::t('app', 'Category'),
            'manufacturer.name' => Yii::t('app', 'Manufacturer'),
            'supplier.name' => Yii::t('app', 'Supplier'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * Return the final price
     *
     * @return float|mixed
     */
    public function getFinalPrice()
    {
        if ($this->sales_price > 0) {
            return $this->sales_price;
        }
        return $this->price;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
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
        return $this->getProductCategories()->where(['=', 'is_main', true])->one();
    }

    /**
     * @return Category
     */
    public function getMainCategory()
    {
        return ($mainProductCategory = $this->mainProductCategory) ?
            $mainProductCategory->category : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['product_id' => 'id']);
    }

    /**
     * @return static
     */
    public function getMainImage()
    {
        return Image::findOne([
            'product_id' => $this->id,
            'is_main' => 1,
        ]);
    }

    /**
     * Get the value of an attribute of this product
     *
     * @param $attributeId
     * @return mixed|null
     */
    public function getAttrValue($attributeId)
    {
        // Find the Product-Attribute link
        $pa = ProductAttribute::findOne([
            'product_id' => $this->id,
            'attribute_id' => $attributeId,
        ]);

        // Return the attribute value get from the link,
        // or nothing if not found the link
        return ($pa != null) ? $pa->value : null;
    }

    /**
     * Set value for the requested attribute of this product
     *
     * @param $attributeId
     * @param $value
     * @return bool
     */
    public function setAttrValue($attributeId, $value)
    {
        $attribute = Attribute::findOne($attributeId);
        $attrType = $attribute->type;

        // Find the Product-Attribute link
        $pa = ProductAttribute::findOne([
            'product_id' => $this->id,
            'attribute_id' => $attributeId,
        ]);

        // It not found the link, this is the first time value setting.
        // Create a new link
        if ($pa == null) {
            $pa = new ProductAttribute();
        }

        $pa->setAttributes([
            'product_id' => $this->id,
            'attribute_id' => $attributeId,
            'value_' . $attrType => $value,
        ]);

        return $pa->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * Return the currently lowest price
     *
     * @return mixed
     */
    public static function getMinPrice()
    {
        $db = Yii::$app->db;

        $minPrice = $db->createCommand(
            'SELECT MIN(price) FROM product'
        )->queryScalar();

        $minSalesPrice = $db->createCommand(
            'SELECT MIN(sales_price) FROM product'
        )->queryScalar();

        return $minPrice < $minSalesPrice ?
            $minPrice : $minSalesPrice;
    }

    /**
     * Return the currently highest price
     *
     * @return mixed
     */
    public static function getMaxPrice()
    {
        $db = Yii::$app->db;

        $maxPrice = $db->createCommand(
            'SELECT MAX(price) FROM product'
        )->queryScalar();

        $maxSalesPrice = $db->createCommand(
            'SELECT MAX(sales_price) FROM product'
        )->queryScalar();

        return $maxPrice > $maxSalesPrice ?
            $maxPrice : $maxSalesPrice;
    }
}
