<?php

namespace app\modules\articles\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\BaseStringHelper;
use app\models\Tag;
use app\models\Tag_item;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $text_preview
 * @property string $img
 */
class Posts extends \yii\db\ActiveRecord
{
    public $image;
    public $filename;
    public $string;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'text_preview'], 'required'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['text_preview'], 'string', 'max' => 255],
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
            'text_preview' => 'Text Preview',
            'img' => 'Img',
        ];
    }
	public function getTags()
	{
		return $this->hasMany(Tag::className(),['id'=>'tag_id'])->viaTable('items_tags',['item_id'=>'id']);
	}

    public function beforeSave($insert){
        if ($this->isNewRecord) {
            //generate & upload
            $this->string = substr(uniqid('img'), 0, 12); //imgRandomString
            $this->image = UploadedFile::getInstance($this, 'img');
            $this->filename = 'static/images/' . $this->string . '.' . $this->image->extension;
            $this->image->saveAs($this->filename);

            $this->text_preview = BaseStringHelper::truncate($this->text, 250, '...');

            //save
            $this->img = '/' . $this->filename;
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
