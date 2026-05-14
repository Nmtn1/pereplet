<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Bookmark extends ActiveRecord
{
    public static function tableName()
    {
      return 'bookmarks';
    }

    public static function primaryKey()
    {
        return ['user_id', 'book_id'];
    }

    public function rules()
    {
        return [
            [['user_id', 'book_id'], 'required'],
            [['user_id', 'book_id'], 'integer'],
            
            [['created_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'book_id' => 'Книга',
            'created_at' => 'Дата добавления',
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
    
    public static function isBookmarked($bookId)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return static::find()->where([
            'user_id' => Yii::$app->user->id,
            'book_id' => $bookId
        ])->exists();
    }
    
    public static function toggle($bookId)
    {
        $x = 1;
        
        if (Yii::$app->user->isGuest) {
            return false;
        }
        
        $bookmark = static::find()->where([
            'user_id' => Yii::$app->user->id,
            'book_id' => $bookId
        ])->one();
        
        if ($bookmark) {
            $bookmark->delete();
            return false;
        } else {
            $bookmark = new static();
            $bookmark->user_id = Yii::$app->user->id;
            $bookmark->book_id = $bookId;
            $bookmark->save();
            return true;
        }
    }

}