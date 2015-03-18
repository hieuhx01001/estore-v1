<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $order_code
 * @property integer $order_status
 * @property string $order_date
 * @property string $order_detail
 * @property integer $shipment_status
 * @property integer $tax
 * @property string $shipment_price
 * @property Invoice $invoice
 * @property Customer $customer
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    private $_orderStatus;
    const STATUS_CANCEL = 3;
    const STATUS_PROCESSING = 2;
    const STATUS_FINISH = 1;

    private $_shipmentStatus;
    const STATUS_SHIPMENT_PROCESSING = 2;
    const STATUS_SHIPMENT_YES = 1;
    const STATUS_SHIPMENT_NO = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_code'], 'required'],
            [['customer_id', 'order_status', 'shipment_status', 'tax'], 'integer'],
            [['order_date'], 'safe'],
            [['order_detail'], 'string'],
            [['shipment_price'], 'number'],
            [['order_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'order_code' => Yii::t('app', 'Order Code'),
            'order_status' => Yii::t('app', 'Order Status'),
            'order_date' => Yii::t('app', 'Order Date'),
            'order_detail' => Yii::t('app', 'Order Detail'),
            'shipment_status' => Yii::t('app', 'Shipment Status'),
            'tax' => Yii::t('app', 'Tax'),
            'shipment_price' => Yii::t('app', 'Shipment Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        if ($this->_orderStatus === null) {
            $statuses = self::getArrayStatus();
            $this->_orderStatus = $statuses[$this->order_status];
        }
        return $this->_orderStatus;
    }

    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_CANCEL => Yii::t('app', 'CANCEL'),
            self::STATUS_PROCESSING => Yii::t('app', 'PROCESSING'),
            self::STATUS_FINISH => Yii::t('app', 'FINISHED'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getStatusShipmentLabel()
    {
        if ($this->_shipmentStatus === null) {
            $statuses = self::getArrayShipmentStatus();
            $this->_shipmentStatus = $statuses[$this->shipment_status];
        }
        return $this->_shipmentStatus;
    }

    /**
     * @inheritdoc
     */
    public static function getArrayShipmentStatus()
    {
        return [
            self::STATUS_SHIPMENT_NO => Yii::t('app', 'NO'),
            self::STATUS_SHIPMENT_PROCESSING => Yii::t('app', 'PROCESSING'),
            self::STATUS_SHIPMENT_YES => Yii::t('app', 'YES'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getInvoiceStatus()
    {
        return [
            self::STATUS_SHIPMENT_NO => Yii::t('app', 'NO'),
            self::STATUS_SHIPMENT_YES => Yii::t('app', 'YES'),
        ];
    }




}
