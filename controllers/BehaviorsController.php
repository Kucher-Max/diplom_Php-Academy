<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\MyBehaviors;

class BehaviorsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['reg', 'login', 'activate-account'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['profile'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['logout'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['index', 'search', 'send-email', 'reset-password']
                    ],
                ]
            ],
			//убрать нижнее подчеркивание из запроса поиска по сайту
            'removeUnderscore' => [
                'class' => MyBehaviors::className(),
                'controller' => Yii::$app->controller->id,
                'action' => Yii::$app->controller->action->id,
                'removeUnderscore' => Yii::$app->request->get('search')
            ]
        ];
    }
}