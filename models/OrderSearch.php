<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'bonus_used', 'bonus_earned'], 'integer'],
            [['guest_email', 'guest_phone', 'status', 'payment_method', 'payment_status', 'delivery_method', 'delivery_address', 'customer_comment', 'manager_comment', 'created_at', 'updated_at'], 'safe'],
            [['total_amount', 'discount_amount', 'delivery_price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_amount' => $this->total_amount,
            'discount_amount' => $this->discount_amount,
            'delivery_price' => $this->delivery_price,
            'bonus_used' => $this->bonus_used,
            'bonus_earned' => $this->bonus_earned,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'guest_email', $this->guest_email])
            ->andFilterWhere(['like', 'guest_phone', $this->guest_phone])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'delivery_method', $this->delivery_method])
            ->andFilterWhere(['like', 'delivery_address', $this->delivery_address])
            ->andFilterWhere(['like', 'customer_comment', $this->customer_comment])
            ->andFilterWhere(['like', 'manager_comment', $this->manager_comment]);

        return $dataProvider;
    }
}
