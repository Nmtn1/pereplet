<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $password;
    public $password_repeat;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['email', 'password_hash'], 'required'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['first_name', 'last_name', 'phone', 'avatar', 'role'], 'string', 'max' => 255],
            [['birth_date'], 'safe'],
            [['bonus_points'], 'integer'],
            [['is_active'], 'boolean'],
            [['role'], 'in', 'range' => ['user', 'manager', 'admin']],
            [['password', 'password_repeat'], 'safe'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'phone' => 'Телефон',
            'birth_date' => 'Дата рождения',
            'bonus_points' => 'Бонусные баллы',
            'role' => 'Роль',
            'is_active' => 'Активен',
        ];
    }


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['id' => $token]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; 
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getFullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getOrders()
    {
        return $this->hasMany(Order::class, ['user_id' => 'id']);
    }

    public function getReviews()
    {
        return $this->hasMany(Review::class, ['user_id' => 'id']);
    }

    public function getBookmarks()
    {
        return $this->hasMany(Bookmark::class, ['user_id' => 'id']);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }
}