<?php

namespace app\models;

use Yii;

use yii\web\UploadedFile;
/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string $avatar
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property integer $birthday
 * @property integer $gender
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
	
    public $image;
    public $filename;
    public $string;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday','gender'], 'integer'],
			[['avatar'], 'safe'], 
			[['avatar'], 'file', 'extensions'=>'jpg, gif, png'],           
            [['first_name', 'second_name', 'middle_name'], 'string', 'max' => 32],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'avatar' => 'Аватар',
            'first_name' => 'Имя',
            'second_name' => 'Фамилия',
            'middle_name' => 'Очество',
			'gender' => 'Пол',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function updateProfile()
    {
        $profile = ($profile = Profile::findOne(Yii::$app->user->id)) ? $profile : new Profile();
        $profile->user_id = Yii::$app->user->id;
		$profile->avatar = $this->avatar;
        $profile->first_name = $this->first_name;
        $profile->second_name = $this->second_name;
        $profile->middle_name = $this->middle_name;
        if($profile->save()):
            $user = $this->user ? $this->user : User::findOne(Yii::$app->user->id);
            $username = Yii::$app->request->post('User')['username'];
            $user->username = isset($username) ? $username : $user->username;
            return $user->save() ? true : false;
        endif;
        return false;
    }
     public function beforeSave($insert){
        // if ($this->isNewRecord) {
            //generate & upload
            $this->string = substr(uniqid('avatar'), 0, 12); //imgRandomString
            $this->image = UploadedFile::getInstance($this, 'avatar');
            $this->filename = 'static/images/' . $this->string . '.' . $this->image->extension;
            $this->image->saveAs($this->filename);            //save
            $this->avatar = '/' . $this->filename;
        // }else{
            // $this->image = UploadedFile::getInstance($this, 'avatar');
            // if($this->image){
				// if($this->$avatar!='')
					// $this->image->saveAs(substr($this->avatar, 1));
			// }

        return parent::beforeSave($insert);
		}
	
	
}
