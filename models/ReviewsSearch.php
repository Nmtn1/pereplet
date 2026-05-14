<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class ReviewsSearch extends Reviews
{
    public $book_title;
    public $user_name;
    
    public function rules()
    {
        return [
            [['id', 'user_id', 'book_id', 'rating', 'helpful_count', 'is_approved'], 'integer'],
            [['title', 'comment', 'book_title', 'user_name', 'created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Reviews::find()
            ->joinWith(['book', 'user'])
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        $dataProvider->sort->attributes['book_title'] = [
            'asc' => ['books.title' => SORT_ASC],
            'desc' => ['books.title' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['user_name'] = [
            'asc' => ['users.email' => SORT_ASC],
            'desc' => ['users.email' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['reviews.id' => $this->id]);
        $query->andFilterWhere(['reviews.user_id' => $this->user_id]);
        $query->andFilterWhere(['reviews.book_id' => $this->book_id]);
        $query->andFilterWhere(['reviews.rating' => $this->rating]);
        $query->andFilterWhere(['reviews.is_approved' => $this->is_approved]);
        
        $query->andFilterWhere(['like', 'reviews.title', $this->title]);
        $query->andFilterWhere(['like', 'reviews.comment', $this->comment]);
        $query->andFilterWhere(['like', 'books.title', $this->book_title]);
        $query->andFilterWhere(['like', 'users.email', $this->user_name]);
        
        if ($this->created_at) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'DATE(reviews.created_at)', date('Y-m-d', $date), date('Y-m-d', strtotime('+1 day', $date))]);
        }

        return $dataProvider;
    }
}