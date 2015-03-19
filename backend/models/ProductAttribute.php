<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_attribute".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $attribute_id
 * @property integer $value_boolean
 * @property integer $value_int
 * @property double $value_double
 * @property string $value_varchar
 * @property string $value_text
 *
 * @property Product $product
 * @property Attribute $attribute
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'attribute_id'], 'required'],
            [['product_id', 'attribute_id', 'value_boolean', 'value_int'], 'integer'],
            [['value_double'], 'number'],
            [['value_text'], 'string'],
            [['value_varchar'], 'string', 'max' => 255],
            [['product_id', 'attribute_id'], 'unique', 'targetAttribute' => ['product_id', 'attribute_id'], 'message' => 'The combination of Product ID and Attribute ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'value_boolean' => Yii::t('app', 'Value Boolean'),
            'value_int' => Yii::t('app', 'Value Int'),
            'value_double' => Yii::t('app', 'Value Double'),
            'value_varchar' => Yii::t('app', 'Value Varchar'),
            'value_text' => Yii::t('app', 'Value Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute()
    {
        return $this->hasOne(Attribute::className(), ['id' => 'attribute_id']);
    }
}
