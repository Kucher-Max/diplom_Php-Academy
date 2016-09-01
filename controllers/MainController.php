<?php
namespace app\controllers;

use Yii;
use app\models\RegForm;
use app\models\LoginForm;
use app\models\User;
use app\models\Profile;
use app\models\Tag;
use app\modules\articles\models\Posts;
use app\modules\links\models\Links;
use app\models\SendEmailForm;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AccountActivation;

class MainController extends BehaviorsController{

    public function actionProfile()
    {
        $model = ($model = Profile::findOne(Yii::$app->user->id)) ? $model : new Profile();

        if($model->load(Yii::$app->request->post()) && $model->validate()):
            if($model->updateProfile($model)):
                Yii::$app->session->setFlash('success', 'Профиль изменен');
            else:
                Yii::$app->session->setFlash('error', 'Профиль не изменен');
                Yii::error('Ошибка записи. Профиль не изменен');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'profile',
            [
                'model' => $model
            ]
        );
    }

    public function actionReg()
    {
        $emailActivation = Yii::$app->params['emailActivation'];
        $model = $emailActivation ? new RegForm(['scenario' => 'emailActivation']) : new RegForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            if ($user = $model->reg()):
                if ($user->status === User::STATUS_ACTIVE):
                    if (Yii::$app->getUser()->login($user)):
						$profile=new Profile();
						$profile->user_id=$model->id;
						$profile->save();
                        return $profile->id;
						return $this->goHome();
						
                    endif;
                else:
                    if($model->sendActivationEmail($user)):
                        Yii::$app->session->setFlash('success', 'Письмо с активацией отправлено на эл.почту <strong>'.Html::encode($user->email).'</strong> (проверьте папку СПАМ).');
                    else:
                        Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                        Yii::error('Ошибка отправки письма.');
                    endif;
                    return $this->refresh();
                endif;
            else:
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'reg',
            [
                'model' => $model
            ]
        );
    }

    public function actionActivateAccount($key)
    {
        try {
            $user = new AccountActivation($key);
        }
        catch(InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if($user->activateAccount()):
            Yii::$app->session->setFlash('success', 'Активация прошла успешно. <strong>'.Html::encode($user->username));
        else:
            Yii::$app->session->setFlash('error', 'Ошибка активации.');
            Yii::error('Ошибка при активации.');
        endif;

        return $this->redirect(Url::to(['/main/login']));
    }

    public function actionSearch($search)
    {
        //$search = Yii::$app->request->post();
        //$key=$search['search'];
	$key=$search;
		$posts=Posts::find()->where(['like','title',$key])
			->orWhere(['like','text',$key])
			->orWhere(['like','text_preview',$key])
			->all();
		$links=Links::find()->where(['like','title',$key])
			->orWhere(['like','text',$key])
			->all();
		$tags=Tag::find()->where(['like','name',$key])->all();
		$users=User::find()->where(['like','username',$key])->all();
	$profile=Profile::find()->where(['like','first_name',$key])
	->orWhere(['like','middle_name',$key])
	->orWhere(['like','second_name',$key])
	->all();
		
		// Yii::$app->session->remove('search');

        // if ($search):
            // Yii::$app->session->setFlash(
                // 'success',
                // 'Результат поиска'
            // );
        // else:
            // Yii::$app->session->setFlash(
                // 'error',
                // 'Не заполнена форма поиска'
            // );
        // endif;

        return $this->render(
            'search',
            [
                'posts' => $posts,
                'links' => $links,
                'tags' => $tags,
                'users' => $users,
                'profile' => $profile,
                'key' => $key,
            ]
        );
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest):
            return $this->goHome();
        endif;

        $loginWithEmail = Yii::$app->params['loginWithEmail'];

        $model = $loginWithEmail ? new LoginForm(['scenario' => 'loginWithEmail']) : new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()):
            return $this->goBack();
        endif;

        return $this->render(
            'login',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/main/login']);
    }

    public function actionSendEmail()
    {
        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->sendEmail()):
                    Yii::$app->getSession()->setFlash('warning', 'Проверьте эл.почту.');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', 'Нельзя сбросить пароль.');
                endif;
            }
        }

        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($key)
    {
        try {
            $model = new ResetPasswordForm($key);
        }
        catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Пароль изменен.');
                return $this->redirect(['/main/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
