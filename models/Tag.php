<?php
namespace app\models;

use yii\base\Model;
use app\modules\articles\models\Posts;
use app\modules\links\models\Links;
class Tag extends \yii\db\ActiveRecord
  {   

    public static function tableName()
    {
        return 'tags';
    }
	public function getPosts()
	{
		return $this->hasMany(Posts::className(),['id'=>'item_id'])->viaTable('items_tags',['tag_id'=>'id']);
	}
	public function getLinks()
	{
		return $this->hasMany(Links::className(),['id'=>'item_id'])->viaTable('links_tags',['tag_id'=>'id']);	
	}
	
}
