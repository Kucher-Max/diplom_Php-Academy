<?php

namespace app\modules\links\controllers;

use Yii;
use app\modules\links\models\Links;
use app\modules\links\models\LinksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Tag_link;
use yii\web\UploadedFile;
use app\models\Tag;
use app\models\UploadForm;
/**
 * DefaultController implements the CRUD actions for Links model.
 */
class DefaultController extends Controller
{

    public $image;
    public $filename;
    public $string;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Links models.
     * @return mixed
     */
    public function actionIndex()
    {
        $AllLinks = Links::find();

        if ($AllLinks) {
                $pages = new Pagination(['totalCount' => $AllLinks->count(), 'pageSize' => 3]);
                $links = $AllLinks->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();

                return $this->render('index', ['links' => $links, 'pages' => $pages]);
            }
    }


    /**
     * Displays a single Links model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$link=Links::findOne($id);
        return $this->render('view', [
            'model' =>$link,
			'tags'=>$link->tags
        ]);
    }


    /**
     * Creates a new Links model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Links();
		$r=(Yii::$app->request->post());
		$tags=Tag::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$tag=$r['tags'];
			if(count($tag)>1){
				foreach($tag as $t)
				{
					$ti=new Tag_link;
					$ti->tag_id=Tag::find()->where(['name'=>$t])->one()->id;
					$ti->item_id=$model->id;
					$ti->save();
				}
			}
			elseif(count($tag)!=0)
			{	
				$ti=$tag;
				$ti=new Tag_link;
				$ti->tag_id=Tag::find()->where(['name'=>$tag])->one()->id;
				$ti->item_id=$model->id;
				$ti->save();
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'tags'=>$tags
            ]);
        }
    }

    /**
     * Updates an existing Links model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$r=(Yii::$app->request->post());
		$oldimg='';		
		if($r!=null&&$r['Links']['img']==''){
			$oldimg=$model->img;
		}
        if ($model->load(Yii::$app->request->post()) && $model->save()) {			
			if($oldimg!='')
			{
				$model->img=$oldimg;
				$model->save();
			}
			$tag=$r['tags'];
			$oldtags=Tag_link::find()->where(['item_id'=>$id])->all();
			if(count($oldtags)>1){
				foreach($oldtags as $ot){
					$ot->delete();
				}
			}elseif(count($oldtags)!=0) $oldtags[0]->delete();
			if(count($tag)>1){
			foreach($tag as $t)
			{
				$ti=new Tag_link;
				$ti->tag_id=Tag::find()->where(['name'=>$t])->one()->id;
				$ti->item_id=$model->id;
				$ti->save();
			}
			}elseif(count($tag)!=0){
				$ti=new Tag_link;
				$ti->tag_id=Tag::find()->where(['name'=>$tag])->one()->id;
				$ti->item_id=$model->id;
				$ti->save();

}	
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'tags'=>Tag::find()->all(),
				'usestags'=>$model->tags
            ]);
        }
    }

    /**
     * Deletes an existing Links model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
$tags=Tag_link::find()->where(['item_id'=>$id])->all();
if(count($tags)>1)
foreach($tags as $tag){
	$tag->delete();
}
elseif(count($tags)!=0) $tags[0]->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Links model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Links the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Links::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
