<?php

namespace app\models;

use yii\db\ActiveRecord;

class Reviews extends ActiveRecord
{
    public static function tableName()
    {
        return 'reviews';
    }

    public function rules()
    {
        return [
            [['user_id', 'book_id', 'rating'], 'required'],
            [['user_id', 'book_id', 'rating', 'helpful_count', 'not_helpful_count'], 'integer'],
            [['rating'], 'in', 'range' => [1, 2, 3, 4, 5]],
            [['comment', 'pros', 'cons'], 'string'],
            [['title'], 'string', 'max' => 255],
            
            [['is_verified', 'is_approved'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'book_id' => 'Книга',
            'rating' => 'Оценка',
            'title' => 'Заголовок',
            'comment' => 'Комментарий',
            'pros' => 'Достоинства',
            'cons' => 'Недостатки',
            'is_verified' => 'Подтверждён',
            'is_approved' => 'Одобрен',
            'helpful_count' => 'Полезных',
            'not_helpful_count' => 'Бесполезных',
            'created_at' => 'Дата',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
    
    public function getStarsHtml()
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $html .= '★';
            } else {
                $html .= '☆';
            }
        }
        return $html;

    }
    
}