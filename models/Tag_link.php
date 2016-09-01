<?php
namespace app\models;
use yii\base\Model;
 
class Tag_link extends \yii\db\ActiveRecord
  {   

    public static function tableName()
    {
        return 'links_tags';
    }
	
	
}