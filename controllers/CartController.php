<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Cart;
use app\models\Book;
use app\models\Order;
use app\models\OrderItem;
use app\models\Promotion;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['checkout'],
                'rules' => [
                    [
                        'actions' => ['checkout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $cartData = Cart::getItems();
        
        return $this->render('index', [
            'items' => $cartData['items'],
            'total' => $cartData['total'],
            'count' => $cartData['count'],
        ]);
    }

    public function actionAdd($id)
    {
        $book = Book::findOne($id);
        if (!$book || $book->stock <= 0) {
            Yii::$app->session->setFlash('error', 'Товар недоступен');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
        
        Cart::add($id);
        Yii::$app->session->setFlash('success', 'Товар добавлен в корзину');
        
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionUpdate($id, $quantity)
    {
        Cart::update($id, $quantity);
        return $this->redirect(['index']);
    }

    public function actionRemove($id)
    {
        Cart::remove($id);
        Yii::$app->session->setFlash('success', 'Товар удалён из корзины');
        return $this->redirect(['index']);
    }

    public function actionClear()
    {
        Cart::clear();
        Yii::$app->session->setFlash('success', 'Корзина очищена');
        return $this->redirect(['index']);
    }

    public function actionCheckout()
    {
        $cartData = Cart::getItems();
        
        if (empty($cartData['items'])) {
            return $this->redirect(['index']);
        }
        
        $order = new Order();
        $order->user_id = Yii::$app->user->id;
        $order->total_amount = $cartData['total'];
        $order->status = Order::STATUS_NEW;
        $order->payment_status = Order::PAYMENT_PENDING;
        
        if ($order->load(Yii::$app->request->post()) && $order->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $order->save(false);
                
                foreach ($cartData['items'] as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->book_id = $item['book']->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->price_at_time = $item['book']->price;
                    $orderItem->discount_at_time = $item['book']->getDiscountPercent();
                    $orderItem->save();
                    
                    $book = $item['book'];
                    $book->stock -= $item['quantity'];
                    $book->sales_count += $item['quantity'];
                    $book->save();
                }
                
                Cart::clear();
                $transaction->commit();
                
                Yii::$app->session->setFlash('success', 'Заказ успешно оформлен!');
                return $this->redirect(['/profile/orders']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
            }
        }
        
        echo 1; 
        
        return $this->render('checkout', [
            'order' => $order,
            'items' => $cartData['items'],
            'total' => $cartData['total'],
            'count' => $cartData['count'],
        ]);
    }
}