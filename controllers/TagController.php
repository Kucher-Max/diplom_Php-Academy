<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Tag;
use app\modules\articles\models\Posts;
use app\models\Tag_item;
use app\models\Tag_link;


class TagController extends Controller
{

    /**
     * @inheritdoc
     */


    /**
     * Displays homepage.
     *
     * @return string
     */
	public function actionView($id)
	{
		$tag=Tag::findOne($id);
		return $this->render('tag', [
			'tag'=>$tag,
			'posts'=>$tag->posts,
			'links'=>$tag->links
        ]);
	}
	public function actionSearch()
	{
		$r=(Yii::$app->request->post());
		$text=$r['text'];
		//return $text;
		$result=Tag::find()->where(['like','name',$text])->all();
		//$posts = Yii::$app->db->createCommand('SELECT * FROM tags where name like %:text%',[':text'=>$text])
        //    ->queryAll();
		//return var_dump($posts);
		//return json_encode($result);  НЕ РАБОТАЕТ
		$str='';
		foreach($result as $r)
		{
			$str.=$r->name.'|'.$r->id.';';
		}		
		return $str;
	}
}
