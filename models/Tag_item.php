<?php
namespace app\models;
use yii\base\Model;
 
class Tag_item extends \yii\db\ActiveRecord
  {   

    public static function tableName()
    {
        return 'items_tags';
    }
	
}
