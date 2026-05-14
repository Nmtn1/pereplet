<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book;

class BookSearch extends Book
{
    public function rules()
    {
        return [
            [['id', 'discount_percent', 'stock', 'pages', 'year', 'author_id', 'publisher_id', 'category_id'], 'integer'],
            [['title', 'isbn', 'cover_type'], 'safe'],
            [['price'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Book::find()->with(['author', 'publisher']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'isbn', $this->isbn]);
        $query->andFilterWhere(['cover_type' => $this->cover_type]);
        $query->andFilterWhere(['author_id' => $this->author_id]);
        $query->andFilterWhere(['publisher_id' => $this->publisher_id]);
        $query->andFilterWhere(['category_id' => $this->category_id]);
        
        if ($this->price) {
            $query->andFilterWhere(['<=', 'price', $this->price]);
        }

        return $dataProvider;
    }
}