<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{
    // add customer name attributes that will be used to store the data to be search
    public $customer_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'order_status', 'shipment_status'], 'integer'],
            [['order_code', 'order_date', 'order_detail'], 'safe'],
            [['customer_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();

        $query->joinWith(['customer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // add customer name to sort
        $dataProvider->sort->attributes['customer_name'] = [
            'asc' => ['customer.name' => SORT_ASC],
            'desc' => ['customer.name' => SORT_DESC],
        ];

        // no search? then return data provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_status' => $this->order_status,
            'order_date' => $this->order_date,
            'shipment_status' => $this->shipment_status,
        ]);

        $query->andFilterWhere(['like', 'order_date', $this->order_date])
            ->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'customer.name',$this->customer_name]);

        return $dataProvider;
    }
}
