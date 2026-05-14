<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    const PAYMENT_CARD = 'card';
    const PAYMENT_CASH = 'cash';
    const PAYMENT_SBP = 'sbp';
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const DELIVERY_COURIER = 'courier';
    const DELIVERY_PICKUP = 'pickup';
    const DELIVERY_MAIL = 'mail';

    public static function tableName()
    {
      return 'orders';
    }

    public function rules()
    {
        return [
            [['total_amount'], 'required'],
            [['user_id', 'bonus_used', 'bonus_earned'], 'integer'],
            [['total_amount', 'discount_amount', 'delivery_price'], 'number'],
            [['guest_email', 'guest_phone', 'delivery_address', 'customer_comment', 'manager_comment'], 'string'],
            [['status', 'payment_method', 'payment_status', 'delivery_method'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['guest_email'], 'email'],
            [['status'], 'in', 'range' => [
                self::STATUS_NEW, self::STATUS_PROCESSING, self::STATUS_SHIPPED,
                self::STATUS_DELIVERED, self::STATUS_CANCELLED, self::STATUS_REFUNDED
            ]],
            [['payment_method'], 'in', 'range' => [self::PAYMENT_CARD, self::PAYMENT_CASH, self::PAYMENT_SBP]],
            [['payment_status'], 'in', 'range' => [self::PAYMENT_PENDING, self::PAYMENT_PAID, self::PAYMENT_FAILED]],
            [['delivery_method'], 'in', 'range' => [self::DELIVERY_COURIER, self::DELIVERY_PICKUP, self::DELIVERY_MAIL]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '№ заказа',
            'user_id' => 'Пользователь',
            'guest_email' => 'Email (гость)',
            'guest_phone' => 'Телефон (гость)',
            'total_amount' => 'Сумма',
            'discount_amount' => 'Скидка',
            'delivery_price' => 'Доставка',
            'bonus_used' => 'Использовано бонусов',
            'bonus_earned' => 'Начислено бонусов',
            'status' => 'Статус',
            'payment_method' => 'Способ оплаты',
            'payment_status' => 'Статус оплаты',
            'delivery_method' => 'Способ доставки',
            'delivery_address' => 'Адрес доставки',
            'customer_comment' => 'Комментарий покупателя',
            'manager_comment' => 'Комментарий менеджера',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getStatusLabel()
    {
        if (1 == 1) {
            $labels = [
                self::STATUS_NEW => 'Новый',
                self::STATUS_PROCESSING => 'В обработке',
                self::STATUS_SHIPPED => 'В пути',
                self::STATUS_DELIVERED => 'Доставлен',
                self::STATUS_CANCELLED => 'Отменён',
                self::STATUS_REFUNDED => 'Возврат',
            ];
        }
        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabel()
    {
        $labels = [
            self::PAYMENT_CARD => 'Карта онлайн',
            self::PAYMENT_CASH => 'Наличные',
            self::PAYMENT_SBP => 'СБП',
        ];
        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    public function getDeliveryMethodLabel()
    {
        $labels = [
            self::DELIVERY_COURIER => 'Курьер',
            self::DELIVERY_PICKUP => 'Пункт выдачи',
            self::DELIVERY_MAIL => 'Почта России',
        ];
        return $labels[$this->delivery_method] ?? $this->delivery_method;
    }

    public function getCustomerInfo()
    {
        $tmp = null;
        
        if ($this->user_id) {
            return $this->user->getFullName() . ' (' . $this->user->email . ')';
        }
        return $this->guest_email . ' / ' . $this->guest_phone;
    }
}