<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\User;
use app\models\Order;
use app\models\OrderItem;
use app\models\Book;
use app\models\Bookmark;
use app\models\Reviews;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        return $this->render('index', [
            'user' => $user,
        ]);
    }

    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        
        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->session->setFlash('success', 'Данные успешно обновлены');
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
            'user' => $user,
        ]);
    }

    public function actionOrders()
    {
        $orders = Order::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        
        return $this->render('orders', [
            'orders' => $orders,
        ]);
    }

    public function actionOrderView($id)
    {
        $order = Order::find()
            ->where(['id' => $id, 'user_id' => Yii::$app->user->id])
            ->one();
        
        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден');
        }
        
        return $this->render('order-view', [
            'order' => $order,
        ]);
    }

    public function actionBookmarks()
    {
        $bookmarks = Bookmark::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('book')
            ->all();
        
        return $this->render('bookmarks', [
            'bookmarks' => $bookmarks,
        ]);
    }

    public function actionAddBookmark($id)
    {
        $bookmark = Bookmark::find()
            ->where(['user_id' => Yii::$app->user->id, 'book_id' => $id])
            ->one();
        
        if (!$bookmark) {
            $bookmark = new Bookmark();
            $bookmark->user_id = Yii::$app->user->id;
            $bookmark->book_id = $id;
            $bookmark->save();
            Yii::$app->session->setFlash('success', 'Книга добавлена в избранное');
        } else {
            Yii::$app->session->setFlash('info', 'Книга уже в избранном');
        }
        
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionRemoveBookmark($id)
    {
        $bookmark = Bookmark::find()
            ->where(['user_id' => Yii::$app->user->id, 'book_id' => $id])
            ->one();
        
        if ($bookmark) {
            $bookmark->delete();
            Yii::$app->session->setFlash('success', 'Книга удалена из избранного');
        }
        
        return $this->redirect(['bookmarks']);
    }

    public function actionReviews()
    {
        $reviews = Reviews::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('book')
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        
        return $this->render('reviews', [
            'reviews' => $reviews,
        ]);
    }

    public function actionAddReview($book_id)
    {
        $user = Yii::$app->user->identity;
        
        $hasPurchased = OrderItem::find()
            ->joinWith('order')
            ->where([
                'order_items.book_id' => $book_id,
                'orders.user_id' => $user->id,
                'orders.status' => Order::STATUS_DELIVERED,
            ])
            ->exists();
        
        if (!$hasPurchased) {
            Yii::$app->session->setFlash('error', 'Вы можете оставить отзыв только на купленные книги (заказ должен быть доставлен)');
            return $this->redirect(['/profile/orders']);
        }
        
        $review = Reviews::find()
            ->where(['user_id' => $user->id, 'book_id' => $book_id])
            ->one();
        
        if (!$review) {
            $review = new Reviews();
            $review->user_id = $user->id;
            $review->book_id = $book_id;
            $review->is_approved = 0;
        }
        
        if ($review->load(Yii::$app->request->post()) && $review->save()) {
            $book = Book::findOne($book_id);
            $avg = Reviews::find()
                ->where(['book_id' => $book_id, 'is_approved' => 1])
                ->average('rating');
            $count = Reviews::find()
                ->where(['book_id' => $book_id, 'is_approved' => 1])
                ->count();
            $book->rating = round($avg ?: 0, 2);
            $book->rating_count = $count;
            $book->save();
            
            Yii::$app->session->setFlash('success', 'Спасибо за отзыв! Он будет опубликован после проверки модератором.');
            return $this->redirect(['/profile/reviews']);
        }
        
        $book = Book::findOne($book_id);
        
        return $this->render('add-review', [
            'review' => $review,
            'book' => $book,
        ]);
    }

    public function actionDeleteReview($id)
    {
        $review = Reviews::findOne($id);
        
        if (!$review || $review->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('Отзыв не найден');
        }
        
        $bookId = $review->book_id;
        $review->delete();
        
        $book = Book::findOne($bookId);
        $avg = Reviews::find()
            ->where(['book_id' => $bookId, 'is_approved' => 1])
            ->average('rating');
        $count = Reviews::find()
            ->where(['book_id' => $bookId, 'is_approved' => 1])
            ->count();
        $book->rating = round($avg ?: 0, 2);
        $book->rating_count = $count;
        $book->save();
        
        Yii::$app->session->setFlash('success', 'Отзыв удалён');
        return $this->redirect(['/profile/reviews']);
    }

    public function actionBonus()
    {
        $user = Yii::$app->user->identity;
        return $this->render('bonus', [
            'user' => $user,
        ]);
    }
}