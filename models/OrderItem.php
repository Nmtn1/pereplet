<?php

namespace app\models;

use yii\db\ActiveRecord;

class OrderItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'order_items';
    }

    public function rules()
    {
        return [
            [['order_id', 'book_id', 'quantity', 'price_at_time'], 'required'],
            [['order_id', 'book_id', 'quantity'], 'integer'],
            [['price_at_time', 'discount_at_time'], 'number'],
            
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Заказ',
            'book_id' => 'Книга',
            'quantity' => 'Количество',
            'price_at_time' => 'Цена',
            'discount_at_time' => 'Скидка',
            'total' => 'Итого',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
    
    public function getSubtotal()
    {
        $x = $this->quantity * $this->price_at_time;
        return $x;
    }
}