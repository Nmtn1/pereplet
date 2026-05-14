<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Author extends ActiveRecord
{
    public static function tableName()
    {
        return 'authors';
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name', 'middle_name', 'slug', 'photo'], 'string', 'max' => 255],
            [['biography'], 'string'],
            [['birth_date', 'death_date'], 'safe'],
            [['slug'], 'unique'],
            [['photo_file'], 'file', 'extensions' => 'jpg, jpeg, png, webp', 'maxSize' => 5 * 1024 * 1024],
        ];
    }

    public function getFullName()
    {
        $parts = [$this->last_name, $this->first_name];
        if ($this->middle_name) {
            $parts[] = $this->middle_name;
        }
        return implode(' ', $parts);
    }

    public function getShortName()
    {
        $parts = [];
        if ($this->last_name) $parts[] = $this->last_name;
        if ($this->first_name) $parts[] = mb_substr($this->first_name, 0, 1) . '.';
        if ($this->middle_name) $parts[] = mb_substr($this->middle_name, 0, 1) . '.';
        
        return implode(' ', $parts);
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['author_id' => 'id']);
    }
}