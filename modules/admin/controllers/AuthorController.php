<?php

namespace app\modules\admin\controllers;

use app\models\Author;
use app\models\AuthorSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class AuthorController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        $model = new Author();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->slug = $this->generateSlug($model->first_name . ' ' . $model->last_name);
            
            $photo = UploadedFile::getInstance($model, 'photo_file');
            if ($photo) {
                $model->photo = $this->savePhoto($photo);
            }
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($model);
        $oldPhoto = $model->photo;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->slug = $this->generateSlug($model->first_name . ' ' . $model->last_name, $model->id);
            
            $photo = UploadedFile::getInstance($model, 'photo_file');
            if ($photo) {
                $this->deletePhoto($oldPhoto);
                $model->photo = $this->savePhoto($photo);
            } else {
                $model->photo = $oldPhoto;
            }
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->deletePhoto($model->photo);
        $model->delete();
        
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Author::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Автор не найден');
    }

    private function generateSlug($name, $excludeId = null)
    {
        $slug = strtolower(trim(preg_replace('/[^a-zа-яё0-9-]+/iu', '-', $name), '-'));
        $slug = preg_replace('/-+/', '-', $slug);
        
        $query = Author::find()->where(['slug' => $slug]);
        if ($excludeId) {
            $query->andWhere(['not', ['id' => $excludeId]]);
        }
        
        $i = 1;
        $originalSlug = $slug;
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $i;
            $query = Author::find()->where(['slug' => $slug]);
            if ($excludeId) {
                $query->andWhere(['not', ['id' => $excludeId]]);
            }
            $i++;
        }
        
        return $slug;
    }

    private function savePhoto($file)
    {
        $uploadPath = Yii::getAlias('@webroot/uploads/authors/');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        $filename = time() . '_' . uniqid() . '.' . $file->extension;
        $file->saveAs($uploadPath . $filename);
        
        return '/uploads/authors/' . $filename;
    }

    private function deletePhoto($path)
    {
        if ($path) {
            $fullPath = Yii::getAlias('@webroot') . $path;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}