<?php
namespace app\models;

use yii\db\ActiveRecord;

class Publisher extends ActiveRecord
{
    public static function tableName()
    {
        return 'publishers';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'email', 'website', 'phone'], 'string', 'max' => 255],
            [['address'], 'string'],
            [['email'], 'email'],
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['publisher_id' => 'id']);
    }
}