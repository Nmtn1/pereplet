<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $code
 * @property string $discount_type
 * @property float $discount_value
 * @property float $min_order_amount
 * @property string $start_date
 * @property string $end_date
 * @property int $is_active
 *
 * @property Book[] $books
 */
class Promotion extends ActiveRecord
{
    const TYPE_PERCENT = 'percent';
    const TYPE_FIXED = 'fixed';

    public static function tableName()
    {
        return 'promotions';
    }

    public function rules()
    {
        return [
            [['name', 'discount_type', 'discount_value'], 'required'],
            [['name', 'code', 'discount_type'], 'string'],
            [['description'], 'string'],
            [['discount_value', 'min_order_amount'], 'number'],
            [['start_date', 'end_date'], 'safe'],
            [['is_active'], 'boolean'],
            [['code'], 'unique'],
            [['discount_type'], 'in', 'range' => [self::TYPE_PERCENT, self::TYPE_FIXED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'code' => 'Промокод',
            'discount_type' => 'Тип скидки',
            'discount_value' => 'Величина скидки',
            'min_order_amount' => 'Мин. сумма заказа',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'is_active' => 'Активна',
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('promotion_books', ['promotion_id' => 'id']);
    }
    
    public function isExpired()
    {
        $now = date('Y-m-d H:i:s');
        if ($this->end_date && $this->end_date < $now) {
            return true;
        }
        return false;
    }
    
    public function isActiveNow()
    {
        if (!$this->is_active) {
            return false;
        }
        
        $now = date('Y-m-d H:i:s');
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }
        if ($this->end_date && $this->end_date < $now) {
            return false;
        }
        return true;
    }
}