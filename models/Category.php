<?php
namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['slug'], 'unique'],
            [['parent_id', 'sort_order'], 'integer'],
            [['name', 'slug', 'icon'], 'string', 'max' => 255],
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['category_id' => 'id']);
    }

    public function getBookCount()
    {
        return $this->getBooks()->count();
    }
    
}