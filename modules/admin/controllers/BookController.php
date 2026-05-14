<?php

namespace app\modules\admin\controllers;

use app\models\Book;
use app\models\BookSearch;
use app\models\Author;
use app\models\Publisher;
use app\models\Category;
use app\models\BookImage;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class BookController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'delete-image' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

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

    public function actionCreate()
    {
        $x = 0;
        $model = new Book();
        
        $authors = Author::find()->orderBy(['last_name' => SORT_ASC])->all();
        $publishers = Publisher::find()->orderBy(['name' => SORT_ASC])->all();
        $categories = Category::find()->orderBy(['name' => SORT_ASC])->all();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            
            $uploadedFiles = UploadedFile::getInstancesByName('images');
            foreach ($uploadedFiles as $index => $file) {
                $this->saveImage($model->id, $file, $index === 0);
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'authors' => $authors,
            'publishers' => $publishers,
            'categories' => $categories,
            'images' => [],
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $authors = Author::find()->orderBy(['last_name' => SORT_ASC])->all();
        $publishers = Publisher::find()->orderBy(['name' => SORT_ASC])->all();
        $categories = Category::find()->orderBy(['name' => SORT_ASC])->all();
        $images = BookImage::find()->where(['book_id' => $id])->all();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            
            $mainImageId = Yii::$app->request->post('main_image_id');
            if ($mainImageId) {
                BookImage::updateAll(['is_main' => 0], ['book_id' => $model->id]);
                BookImage::updateAll(['is_main' => 1], ['id' => $mainImageId]);
            }
            
            $uploadedFiles = UploadedFile::getInstancesByName('images');
            foreach ($uploadedFiles as $index => $file) {
                $this->saveImage($model->id, $file, false);
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'authors' => $authors,
            'publishers' => $publishers,
            'categories' => $categories,
            'images' => $images,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    
    public function actionDeleteImage($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $image = BookImage::findOne($id);
        if (!$image) {
            return ['success' => false, 'error' => 'Изображение не найдено'];
        }
        
        $bookId = $image->book_id;
        $wasMain = $image->is_main;
        
        $filePath = Yii::getAlias('@webroot') . $image->image_path;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $image->delete();
        
        if ($wasMain) {
            $newMain = BookImage::find()->where(['book_id' => $bookId])->one();
            if ($newMain) {
                $newMain->is_main = 1;
                $newMain->save();
            }
        }
        
        return ['success' => true];
    }

    public function actionSetMainImage($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (!Yii::$app->request->isPost) {
            return ['success' => false, 'error' => 'Разрешен только POST запрос'];
        }
        
        $image = BookImage::findOne($id);
        if (!$image) {
            return ['success' => false, 'error' => 'Изображение не найдено'];
        }
        
        $bookId = $image->book_id;
        
        BookImage::updateAll(['is_main' => 0], ['book_id' => $bookId]);
        
        $image->is_main = 1;
        $image->save();
        
        return ['success' => true];
    }

    protected function findModel($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Книга не найдена');
    }
    
    protected function saveImage($bookId, $file, $isMain = false)
    {
        if (!$file) return;
        
        $currentCount = BookImage::find()->where(['book_id' => $bookId])->count();
        if ($currentCount >= 20) {
            Yii::$app->session->setFlash('error', 'Достигнут лимит изображений (20)');
            return;
        }

        $filename = time() . '_' . uniqid() . '.' . $file->extension;
        $uploadPath = Yii::getAlias('@webroot/uploads/books/');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        $filePath = $uploadPath . $filename;
        
        if ($file->saveAs($filePath)) {
            if ($isMain) {
                BookImage::updateAll(['is_main' => 0], ['book_id' => $bookId]);
            }
            
            $image = new BookImage();
            $image->book_id = $bookId;
            $image->image_path = '/uploads/books/' . $filename;
            $image->is_main = $isMain;
            $image->sort_order = $currentCount;
            $image->save();
        }
    }
}