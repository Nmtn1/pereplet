<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Dictionary extends ActiveRecord
{
    public static function tableName()
    {
        return 'dictionary';
    }
    
    public function rules()
    {
        return [
            [['term', 'definition'], 'required'],
            [['definition', 'usage_example'], 'string'],
            [['term'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'term' => 'Термин/слово',
            'definition' => 'Определение (что это)',
            'usage_example' => 'Как использовать (совет)',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}