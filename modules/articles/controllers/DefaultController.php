<?php

namespace app\modules\articles\controllers;

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
use app\models\Tag_item;
use app\models\Tag;
/**
 * DefaultController implements the CRUD actions for Posts model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
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

        $AllPosts = Posts::find();

        if ($AllPosts) {

            $pages = new Pagination(['totalCount' => $AllPosts->count(), 'pageSize' => 3]);
            $posts = $AllPosts->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
            return $this->render('index', ['posts' => $posts, 'pages' => $pages]);
        }
    }

    /**
     * Displays a single Posts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
	{
		$post=Posts::findOne($id);
		$post->views++;
		$post->save();
	
        return $this->render('view', [
            'model' => $post,
			'comments'=>Comment::find()->where(['item_id' =>$id ])->andWhere(['parent_id'=>0])->orderBy('likes DESC')->all(),
			'tags'=>$post->tags
        ]);
    }

    /**
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
        $model = new Posts();
		$r=(Yii::$app->request->post());
		$tags=Tag::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$tag=$r['tags'];
			if(count($tag)>1){
				foreach($tag as $t)
				{
					$ti=new Tag_item;
					$ti->tag_id=Tag::find()->where(['name'=>$t])->one()->id;
					$ti->item_id=$model->id;
					$ti->save();
				}
			}
			elseif(count($tag)!=0)
			{	
				$ti=new Tag_item;
				$ti->tag_id=Tag::find()->where(['name'=>$tag])->one()->id;
				$ti->item_id=$model->id;
				$ti->save();
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } 
		else {
            return $this->render('create', [
                'model' => $model,
				'tags'=>$tags
            ]);
        }
    }
	public function actionLike($id)
	{
		$comment=Comment::find()->where(['id'=>$id])->one();
		$comment->likes+=1;
		$comment->save();
		return $comment->likes;
	}
	public function actionUnlike($id)
	{
		$comment=Comment::find()->where(['id'=>$id])->one();
		$comment->unlikes+=1;
		$comment->save();
		return $comment->unlikes;
	}
	public function actionEditcomment($id)
	{
		$data=Yii::$app->request->post();
		$text=$data['text'];
		$comment=Comment::find()->where(['id'=>$id])->one();
		$comment->text=$text;
		$comment->save();
		$comments=Comment::find()->where(['item_id' =>$comment->item_id ])->andWhere(['parent_id'=>0])->orderBy('likes DESC')->all();
		$data['render']=$this->renderPartial('comment',['comments'=>$comments]);
		$data['message']='&#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1072;&#1088;&#1080;&#1081; &#1080;&#1079;&#1084;&#1077;&#1085;&#1077;&#1085; &#1091;&#1089;&#1087;&#1077;&#1096;&#1085;&#1086;';
		return json_encode($data);
	}
	public function actionDeletecomment($id)
	{
		$comment=Comment::find()->where(['id'=>$id])->one();
		$nodes=Comment::find()->where('rgt<=:right and lft>=:lft',[':right'=>$comment->rgt,':lft'=>$comment->lft])->all();
		foreach($nodes as $node){
			$node->delete();
		};
//UPDATE my_tree SET right_key = right_key – ($right_key - $left_key + 1)*** WHERE right_key > $right_key AND left_key < $left_key
		$nodes=Comment::find()->where('rgt>:right and lft<:lft',[':lft'=>$comment->lft,':right'=>$comment->rgt])->all();
		foreach($nodes as $node)
		{
			$node->rgt=$node->rgt-($comment->rgt-$comment->lft+1);
			$node->save();
			
		};
//UPDATE my_tree SET left_key = left_key – ($right_key - $left_key + 1), right_key = right_key – ($right_key - $left_key + 1) WHERE left_key > $right_key
		$nodes=Comment::find()->where('lft>:right',[':right'=>$comment->rgt])->all();
		foreach($nodes as $node)
		{
			$node->lft=$node->lft-($comment->rgt-$comment->lft+1);
			$node->rgt=$node->rgt-($comment->rgt-$comment->lft+1);
			$node->save();
		}
		
		
		
		
		
		$comments=Comment::find()->where(['item_id' =>$comment->item_id ])->andWhere(['parent_id'=>0])->orderBy('likes DESC')->all();
		$data['render']=$this->renderPartial('comment',['comments'=>$comments]);
		$data['message']='&#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1072;&#1088;&#1080;&#1081; &#1091;&#1076;&#1072;&#1083;&#1077;&#1085; &#1091;&#1089;&#1087;&#1077;&#1096;&#1085;&#1086;';
		return json_encode($data);
	}
	public function actionAddcomment()
	{
		$success=Yii::$app->request->post();
		$parent_id=$success['parent_id'];
		$item_id=$success['item_id'];
		$text=$success['text'];
		$comment=new Comment();
		$comment->item_id=$item_id;
		$comment->text=$text;
		$comment->likes=0;
		$comment->unlikes=0;
		if($parent_id=='null')
		{
			$max=Comment::find()->max('rgt');
			if($max=='')
			{
				$right=0+1;				
			}
			else{
				$right=$max+1;	
			}
			$level=0;
		}else
		{
			$parent=Comment::find()->where(['id'=>$parent_id])->one();
			$right=$parent->rgt;
			$level=$parent->level;
		}
		if($parent_id!='null')
		{
			$nodes=Comment::find()->where('lft>:right',[':right'=>$right])->all();
			if(count($nodes)>0)
			{
				foreach($nodes as $node)
				{
					$node->rgt+=2;
					$node->lft+=2;
					$node->save();
				}
			}
		}
		$nodes=Comment::find()->where('rgt>=:right',[':right'=>$right])
			->andWhere('lft<:right',[':right'=>$right])
			->all();
		if(count($nodes)>0)
		{
			foreach($nodes as $node)
			{
				$node->rgt+=2;
				$node->save();
			}
		}
		$comment->lft=$right;
		$comment->rgt=$right+1;
		$comment->level=$level+1;
		$comment->parent_id=$parent_id;
		$comment->user_name=User::find()->where(['id'=>Yii::$app->user->id])->one()->username;
		$comment->save();
		$comments=Comment::find()->where(['item_id' =>$comment->item_id ])->andWhere(['parent_id'=>0])->orderBy('likes DESC')->all();
		$data['render']=$this->renderPartial('comment',['comments'=>$comments]);
		$data['message']='&#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1072;&#1088;&#1080;&#1081; &#1076;&#1086;&#1073;&#1072;&#1074;&#1083;&#1077;&#1085; &#1091;&#1089;&#1087;&#1077;&#1096;&#1085;&#1086;';
		return json_encode($data);
		
	}
    /**
     * Updates an existing Posts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
	 
    public function actionUpdate($id){
        $model = $this->findModel($id);
		$r=(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$tag=$r['tags'];
			$oldtags=Tag_item::find()->where(['item_id'=>$id])->all();
			if(count($oldtags)>1){
			foreach($oldtags as $ot)
				$ot->delete();
				}elseif(count($oldtags)!=0){
				$oldtags[0]->delete();
				}
			if(count($tag)>1){
			foreach($tag as $t)
			{
				$ti=new Tag_item;
				$ti->tag_id=Tag::find()->where(['name'=>$t])->one()->id;
				$ti->item_id=$model->id;
				$ti->save();
			}	
			}
			else{
				
				$ti=new Tag_item;
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
     * Deletes an existing Posts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id){
       $this->findModel($id)->delete();
		$tags=Tag_item::find()->where(['item_id'=>$id])->all();
		if(count($tags)>1){
foreach($tags as $tag){
	$tag->delete();
}
}elseif(count($tags)!=0) $tags[0]->delete();
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
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
