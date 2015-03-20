<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "category_attribute".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $attribute_id
 *
 * @property Attribute $attr
 * @property Category $category
 */
class CategoryAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'attribute_id'], 'required'],
            [['category_id', 'attribute_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttr()
    {
        return $this->hasOne(Attribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
