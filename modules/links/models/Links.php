<?php

namespace app\modules\links\models;

use Yii;
use yii\web\UploadedFile;

use app\models\Tag;
use app\models\Tag_link;
use yii\base\ExitException;
/**
 * This is the model class for table "links".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $img
 */
class Links extends \yii\db\ActiveRecord
{
    public $image;
    public $filename;
    public $string;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['img'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'img' => 'Img',
        ];
    }
	public function getTags()
	{
return $this->hasMany(Tag::className(),['id'=>'tag_id'])->viaTable('links_tags',['item_id'=>'id']);
	}
    public function beforeSave($insert){
			if ($this->isNewRecord) {
				//generate & upload
				$this->string = substr(uniqid('img'), 0, 12); //imgRandomString
				$this->image = UploadedFile::getInstance($this, 'img');
				$this->filename = 'static/images/' . $this->string . '.' . $this->image->extension;
				if($this->image!=null){
				$this->image->saveAs($this->filename);
				//save
				$this->img = '/' . $this->filename;
				}
			}else{			
				$this->image = UploadedFile::getInstance($this, 'img');
				if($this->img!=''&&$this->image!=null)
				{
					$this->string = substr(uniqid('img'), 0, 12);
					$this->filename = 'static/images/' . $this->string . '.' . $this->image->extension;
					$this->image->saveAs($this->filename);
					$this->img = '/' . $this->filename;
				}elseif($this->img==''&&$this->image!=null)
				{
					$this->string = substr(uniqid('img'), 0, 12);
					$this->filename = 'static/images/' . $this->string . '.' . $this->image->extension;
					$this->image->saveAs($this->filename);
					$this->img = '/' . $this->filename;
				}
			}			
		
		return parent::beforeSave($insert);
}
}
