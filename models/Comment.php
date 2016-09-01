<?php
namespace app\models;
use yii\base\Model;
 
class Comment extends \yii\db\ActiveRecord
  {   

    public static function tableName()
    {
        return 'comments';
    }
	
}
