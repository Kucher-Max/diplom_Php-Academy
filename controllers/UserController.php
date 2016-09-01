<?php

namespace app\controllers;

use Yii;
use app\modules\articles\models\Posts;
use app\modules\articles\models\PostsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use app\models\Comment;
use app\models\User;
use yii\web\UploadedFile;
use app\models\Profile;
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
	public $avatar;
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','update','delete'],
                'rules' => [
                    [
                        'actions' => ['create','update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Posts models.
     * @return mixed
     */
    public function actionIndex(){

        $users = User::find();

        if ($users) {

            $pages = new Pagination(['totalCount' => $users->count(), 'pageSize' => 10]);
            $posts = $users->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
            return $this->render('index', ['users' => $posts, 'pages' => $pages]);
        }
    }

    /**
     * Displays a single Posts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
	{
		$user=User::findOne($id);
		$profile=$user->profile;
        return $this->render('view', [
            'model' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

	 
   public function actionUpdate($id)
   {
    $model = $this->findModel($id);
	$info=Yii::$app->request->post();
	if($info==null)
		return $this->render('update', ['model'=> $model,'profile'=>$model->profile]);
	$avatar=$model->avatar;
	if($info!=null&&$info['User']['avatar']!='')
		$avatar=$info['User']['avatar'];
	$model->avatar=$avatar;
	$model->username=(empty($info["User"]['username']))?$model->username:$info["User"]['username'];
	$model->first_name=(empty($info["User"]['first_name']))?$model->first_name:$info["User"]['first_name'];
	$model->middle_name=(empty($info["User"]['middle_name']))?$model->middle_name:$info["User"]['middle_name'];
	$model->second_name=(empty($info["User"]['second_name']))?$model->second_name:$info["User"]['second_name'];
	$model->gender=(empty($info["User"]['gender']))?$model->gender:$info["User"]['gender'];
	$model->save();
	return $this->redirect(array('user/view','id'=>$model->id));
}
    /**
     * Deletes an existing Posts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDel($id)
	{
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
	