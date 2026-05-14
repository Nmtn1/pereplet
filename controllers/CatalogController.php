<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Book;
use app\models\Category;
use app\models\Author;
use app\models\OrderItem;
use app\models\Reviews;
use app\models\Order;

class CatalogController extends Controller
{
    public function actionIndex()
    {
        $query = Book::find()->where(['>', 'stock', 0]);
        
        $searchQuery = Yii::$app->request->get('q');
        if ($searchQuery) {
            $query->leftJoin('authors', 'books.author_id = authors.id');
            $query->leftJoin('book_categories', 'books.id = book_categories.book_id');
            $query->leftJoin('categories', 'book_categories.category_id = categories.id');
            
            $query->andWhere([
                'or',
                ['like', 'books.title', $searchQuery],
                ['like', 'books.description', $searchQuery],
                ['like', 'books.isbn', $searchQuery],
                ['like', 'authors.last_name', $searchQuery],
                ['like', 'authors.first_name', $searchQuery],
                ['like', 'categories.name', $searchQuery],
            ]);
            
            $query->groupBy(['books.id']);
        }
        
        $categoryId = Yii::$app->request->get('category');
        if ($categoryId) {
            $query->andWhere(['books.category_id' => $categoryId]);
        }
        
        $priceMin = Yii::$app->request->get('price_min');
        $priceMax = Yii::$app->request->get('price_max');
        if ($priceMin && is_numeric($priceMin)) {
            $query->andWhere(['>=', 'price', $priceMin]);
        }
        if ($priceMax && is_numeric($priceMax)) {
            $query->andWhere(['<=', 'price', $priceMax]);
        }
        
        $sort = Yii::$app->request->get('sort', 'popular');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy(['price' => SORT_ASC]);
                break;
            case 'price_desc':
                $query->orderBy(['price' => SORT_DESC]);
                break;
            case 'new':
                $query->orderBy(['created_at' => SORT_DESC]);
                break;
            case 'title':
                $query->orderBy(['title' => SORT_ASC]);
                break;
            default:
                $query->orderBy(['sales_count' => SORT_DESC]);
        }
        
        $count = $query->count();
        $pageSize = 12;
        $pagination = new Pagination([
            'totalCount' => $count, 
            'pageSize' => $pageSize,
            'pageSizeParam' => false,
        ]);
        
        $books = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        
        $categories = Category::find()
            ->orderBy(['name' => SORT_ASC])
            ->all();
        
        $categoryName = null;
        if ($categoryId) {
            $category = Category::findOne($categoryId);
            $categoryName = $category ? $category->name : null;
        }
        
        return $this->render('index', [
            'books' => $books,
            'pagination' => $pagination,
            'categories' => $categories,
            'currentCategory' => $categoryId,
            'categoryName' => $categoryName,
            'searchQuery' => $searchQuery,
            'sort' => $sort,
        ]);
    }
    
    public function actionView($slug)
    {
        $book = Book::findOne(['slug' => $slug]);
        if (!$book) {
            throw new \yii\web\NotFoundHttpException('Книга не найдена');
        }
        
        $book->views_count++;
        $book->save(false);
        
        $related = Book::find()
            ->where(['category_id' => $book->category_id])
            ->andWhere(['>', 'stock', 0])
            ->andWhere(['<>', 'id', $book->id])
            ->limit(4)
            ->all();
        
        return $this->render('view', [
            'book' => $book,
            'related' => $related,
        ]);
    }
    
    public function actionCategory($slug)
    {
        $category = Category::findOne(['slug' => $slug]);
        if (!$category) {
            throw new \yii\web\NotFoundHttpException('Категория не найдена');
        }
        
        return $this->redirect(['index', 'category' => $category->id]);
    }
    
    public function actionSearch()
    {
        $q = Yii::$app->request->get('q');
        
        if (!$q) {
            return $this->redirect(['index']);
        }
        
        return $this->redirect(['index', 'q' => $q]);
    }
    
    public function actionAddReview($book_id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Войдите, чтобы оставить отзыв');
            return $this->redirect(['/site/login']);
        }
        
        $user = Yii::$app->user->identity;
        $book = Book::findOne($book_id);
        
        if (!$book) {
            throw new \yii\web\NotFoundHttpException('Книга не найдена');
        }
        
        $review = Reviews::find()
            ->where(['user_id' => $user->id, 'book_id' => $book_id])
            ->one();
        
        if (!$review) {
            $hasPurchased = OrderItem::find()
                ->joinWith('order')
                ->where([
                    'order_items.book_id' => $book_id,
                    'orders.user_id' => $user->id,
                ])
                ->andWhere(['in', 'orders.status', ['delivered', 'completed']])
                ->exists();
            
            if (!$hasPurchased) {
                Yii::$app->session->setFlash('error', 'Вы можете оставить отзыв только на купленные книги (заказ должен быть доставлен)');
                return $this->redirect(['catalog/view', 'slug' => $book->slug]);
            }
            
            $review = new Reviews();
            $review->user_id = $user->id;
            $review->book_id = $book_id;
            $isNew = true;
        } else {
            $isNew = false;
        }
        
        if ($review->load(Yii::$app->request->post())) {
            if ($isNew) {
                $review->is_approved = 0;
            }
            
            if ($review->save()) {
                $this->updateBookRating($book_id);
                
                if ($isNew) {
                    Yii::$app->session->setFlash('success', 'Спасибо за отзыв! Он будет опубликован после проверки.');
                } else {
                    Yii::$app->session->setFlash('success', 'Ваш отзыв успешно обновлён!');
                }
                return $this->redirect(['catalog/view', 'slug' => $book->slug]);
            } else {
                $errors = $review->getErrors();
                Yii::$app->session->setFlash('error', 'Ошибка при сохранении: ' . print_r($errors, true));
            }
        }
        
        return $this->redirect(['catalog/view', 'slug' => $book->slug]);
    }
    
    private function updateBookRating($bookId)
    {
        $book = Book::findOne($bookId);
        if ($book) {
            $avg = Reviews::find()
                ->where(['book_id' => $bookId, 'is_approved' => 1])
                ->average('rating');
            $count = Reviews::find()
                ->where(['book_id' => $bookId, 'is_approved' => 1])
                ->count();
            $book->rating = round($avg ?: 0, 2);
            $book->rating_count = $count;
            $book->save();
        }
    }
}