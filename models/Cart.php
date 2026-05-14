<?php

namespace app\models;

use Yii;

class Cart
{
    const SESSION_KEY = 'cart';
    
    public static function add($bookId, $quantity = 1)
    {
        $cart = Yii::$app->session->get(self::SESSION_KEY, []);
        
        if (isset($cart[$bookId])) {
            $cart[$bookId] += $quantity;
        } else {
            $cart[$bookId] = $quantity;
        }
        
        Yii::$app->session->set(self::SESSION_KEY, $cart);
        return true;
    }
    
    public static function update($bookId, $quantity)
    {
        $cart = Yii::$app->session->get(self::SESSION_KEY, []);
        
        if ($quantity <= 0) {
            unset($cart[$bookId]);
        } else {
            $cart[$bookId] = $quantity;
        }
        
        Yii::$app->session->set(self::SESSION_KEY, $cart);
        return true;
    }
    
    public static function remove($bookId)
    {
        $cart = Yii::$app->session->get(self::SESSION_KEY, []);
        unset($cart[$bookId]);
        Yii::$app->session->set(self::SESSION_KEY, $cart);
        return true;
    }
    
    public static function clear()
    {
        Yii::$app->session->remove(self::SESSION_KEY);
        return true;
    }
    
    public static function getItems()
    {
        $session = Yii::$app->session;
        $cart = $session->get('cart', []);
        
        $items = [];
        $total = 0;
        $count = 0;
        
        foreach ($cart as $id => $quantity) {
            $book = Book::findOne($id);
            if ($book && $book->stock > 0) {
                $items[] = [
                    'book' => $book,
                    'quantity' => $quantity,
                ];
                $total += $book->price * $quantity;
                $count += $quantity;
            }
        }
        
        return [
            'items' => $items,
            'total' => $total,
            'count' => $count, 
        ];
    }
    
    public static function getTotal()
    {
        $data = self::getItems();
        return $data['total'];
    }
    
    public static function getCount()
    {
        $data = self::getItems();
        return $data['count'];
    }
}