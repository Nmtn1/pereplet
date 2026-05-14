<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class PublisherSearch extends Publisher
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'email', 'phone', 'website'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Publisher::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'phone', $this->phone])
              ->andFilterWhere(['like', 'website', $this->website]);

        return $dataProvider;
    }
}