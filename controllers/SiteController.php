<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\RegForm;
use app\models\LoginForm;
use app\models\User;
use app\models\Comment;
use app\modules\articles\models\Posts;

class SiteController extends Controller{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions(){
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(){
        $comments=Comment::find()->orderBy('likes DESC')->limit(3)->all();
			foreach($comments as $k=>$comment)
			{
				$user=User::find()->where(['username'=>$comment->user_name])->one();
				$post=Posts::find()->where(['id'=>$comment->item_id])->one();
				$comments[$k]=['post'=>$post,'comment'=>$comment,'user'=>$user];
			}
			return $this->render('index',['comments'=>$comments]);
    }

    public function actionLogin(){
			if (!\Yii::$app->user->isGuest) {
				return $this->goHome();
			}

        $model = new LoginForm();
			if ($model->load(Yii::$app->request->post()) && $model->login()) {
				return $this->goBack();
			} else {
				return $this->render('login', [
					'model' => $model,
				]);
			}
    }

    public function actionLogout(){
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact(){
	try{
		$string='someemail'.rand(0,100).'@some.email';
		$to='admin@lowcoasttrip.esy.es';
			Yii::$app->mailer->compose()
               ->setTo($to)
               ->setFrom($string)
               ->setSubject('some subject'.rand(0,100))
               ->setTextBody('some fillezilla'.rand(0,100))
               ->send();
	Yii::$app->session->setFlash('contactFormSubmitted');
	return 'from: '.$string.' to: '.$to;
	}catch(Exception $e){
		return $e->getMessage();
	}
			// $model = new ContactForm();
			// if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
				 // Yii::$app->session->setFlash('contactFormSubmitted');
				// return $this->refresh();
			// }
			// return $this->render('contact', [
				// 'model' => $model,
			 // ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(){
        return $this->render('about');
    }
}
