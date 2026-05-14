<?php

namespace app\modules\admin;

use Yii;
use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $layout = 'main'; 
    
    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = 'admin/default/error';
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function() {
                            if (Yii::$app->user->isGuest) return false;
                            return in_array(Yii::$app->user->identity->role, ['admin', 'manager']);
                        },
                    ],
                ],
                'denyCallback' => function() {
                    Yii::$app->session->setFlash('error', 'Доступ запрещён');
                    return Yii::$app->response->redirect(['/site/index']);
                },
            ],
        ];
    }
}