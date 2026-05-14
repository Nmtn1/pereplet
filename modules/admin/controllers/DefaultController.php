<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Order;
use app\models\User;
use app\models\Book;

class DefaultController extends Controller
{
    public $layout = 'main';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function($rule, $action) {
                            if (\Yii::$app->user->isGuest) {
                                return false;
                            }
                            $user = \Yii::$app->user->identity;
                            return in_array($user->role, ['admin', 'manager']);
                        },
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    \Yii::$app->session->setFlash('error', 'У вас нет доступа к админ-панели');
                    return \Yii::$app->response->redirect(['/site/index']);
                },
            ],
        ];
    }
    
    public function actionIndex()
    {
        $newOrders = Order::find()->where(['status' => 'new'])->count();
        $totalUsers = User::find()->count();
        $totalBooks = Book::find()->count();
        $totalRevenue = Order::find()->where(['payment_status' => 'paid'])->sum('total_amount') ?? 0;
        
        return $this->render('index', [
            'newOrders' => $newOrders,
            'totalUsers' => $totalUsers,
            'totalBooks' => $totalBooks,
            'totalRevenue' => $totalRevenue,
        ]);
    }
    
    public function actionError()
    {
        return $this->render('error');
    }
}