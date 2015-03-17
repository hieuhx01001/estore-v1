<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property integer $invoice_status
 * @property string $invoice_date
 * @property string $payment_date
 * @property string $total_amount
 * @property string $other_detail
 * @property string $invoice_code
 * @property Order $order
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'invoice_code'], 'required'],
            [['id', 'invoice_status'], 'integer'],
            [['invoice_date', 'payment_date'], 'safe'],
            [['total_amount'], 'number'],
            [['other_detail', 'invoice_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invoice_status' => Yii::t('app', 'Invoice Status'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'other_detail' => Yii::t('app', 'Other Detail'),
            'invoice_code' => Yii::t('app', 'Invoice Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'id']);
    }

}
