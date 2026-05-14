<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Url;

class BookImage extends ActiveRecord
{
    public static function tableName()
    {
        return 'book_images';
    }

    public function rules()
    {
        return [
            [['book_id', 'image_path'], 'required'],
            [['book_id', 'sort_order'], 'integer'],
            [['is_main'], 'boolean'],
            [['image_path'], 'string', 'max' => 255],
        ];
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
    
    public function getImageUrl()
    {
        return $this->image_path;
    }
    
    public function afterDelete()
    {
        parent::afterDelete();
        $filePath = \Yii::getAlias('@webroot') . $this->image_path;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}