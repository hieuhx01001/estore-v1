<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "store_detail".
 *
 * @property integer $id
 * @property string $store_name
 * @property string $ower_name
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $phone
 * @property string $email
 * @property string $tax
 */
class StoreDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tax'], 'number'],
            [['store_name', 'ower_name', 'address1', 'address2', 'address3', 'phone', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'store_name' => Yii::t('app', 'Store Name'),
            'ower_name' => Yii::t('app', 'Ower Name'),
            'address1' => Yii::t('app', 'Address1'),
            'address2' => Yii::t('app', 'Address2'),
            'address3' => Yii::t('app', 'Address3'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'tax' => Yii::t('app', 'Tax'),
        ];
    }
}
