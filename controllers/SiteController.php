<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\Book;
use app\models\Category;
use app\models\Promotion;
use app\models\OrderItem; 
use app\models\Reviews;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $popular = Book::find()
            ->where('stock > 0')
            ->orderBy(['sales_count' => SORT_DESC])
            ->limit(5)
            ->all();
            
        $newBooks = Book::find()
            ->where(['is_new' => 1])
            ->andWhere('stock > 0')
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
            
        $saleBooks = Book::find()
            ->where('stock > 0')
            ->andWhere('old_price > 0 OR discount_percent > 0')
            ->orderBy(['discount_percent' => SORT_DESC])
            ->limit(5)
            ->all();
            
        $categories = Category::find()
            ->where(['parent_id' => null])
            ->limit(6)
            ->all();
        
        return $this->render('index', [
            'popular' => $popular,
            'newBooks' => $newBooks,
            'saleBooks' => $saleBooks,
            'categories' => $categories,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Регистрация прошла успешно! Теперь вы можете войти.');
            return $this->redirect(['login']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionPromotions()
    {
        $now = date('Y-m-d H:i:s');
        
        $activePromotions = Promotion::find()
            ->where(['is_active' => 1])
            ->andWhere(['or',
                ['end_date' => null],
                ['>=', 'end_date', $now]
            ])
            ->orderBy(['end_date' => SORT_ASC])
            ->all();
        
        $mainPromotion = Promotion::find()
            ->where(['is_active' => 1])
            ->andWhere(['or',
                ['end_date' => null],
                ['>=', 'end_date', $now]
            ])
            ->orderBy(['discount_value' => SORT_DESC])
            ->limit(1)
            ->one();
        
        return $this->render('promotions', [
            'promotions' => $activePromotions,
            'mainPromotion' => $mainPromotion,
        ]);
    }

    public function actionSales()
    {
        $saleBooks = Book::find()
            ->where('stock > 0')
            ->andWhere('old_price > 0 OR discount_percent > 0')
            ->orderBy(['discount_percent' => SORT_DESC])
            ->all();
        
        return $this->render('sales', [
            'saleBooks' => $saleBooks,
        ]);
    }

    public function actionAbout()
{
    return $this->render('about');
}

public function actionContacts()
{
    return $this->render('contacts');
}
}