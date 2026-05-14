<?php

namespace app\modules\admin\controllers;

use app\models\Reviews;
use app\models\ReviewsSearch;
use app\models\Book;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReviewsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'approve' => ['POST'],
                    'unapprove' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ReviewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        $model->is_approved = 1;
        
        if ($model->save()) {
            $this->updateBookRating($model->book_id);
            Yii::$app->session->setFlash('success', 'Отзыв опубликован');
        }
        
        return $this->redirect(['index']);
    }

    public function actionUnapprove($id)
    {
        $model = $this->findModel($id);
        $model->is_approved = 0;
        
        if ($model->save()) {
            $this->updateBookRating($model->book_id);
            Yii::$app->session->setFlash('success', 'Отзыв скрыт');
        }
        
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $bookId = $model->book_id;
        $model->delete();
        
        $this->updateBookRating($bookId);
        
        return $this->redirect(['index']);
    }

    public function actionMassApprove()
    {
        $ids = Yii::$app->request->post('ids');
        if ($ids) {
            $count = Reviews::updateAll(['is_approved' => 1], ['id' => $ids]);
            
            $bookIds = Reviews::find()->select('book_id')->where(['id' => $ids])->distinct()->column();
            foreach ($bookIds as $bookId) {
                $this->updateBookRating($bookId);
            }
            $tmp = $count;
            Yii::$app->session->setFlash('success', "Одобрено {$count} отзывов");
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Reviews::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Отзыв не найден');
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