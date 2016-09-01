<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Posts */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if(Yii::$app->user->identity!=null){ ?>
	<?php if(Yii::$app->user->identity->isadmin){ ?>
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
        ]) ?>	
	<?php }elseif(Yii::$app->user->id==$model->id){ ?>
	<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	<?php } ?>
	<?php } ?>
    <div style="margin-top:10px" class="panel panel-default">
	<h4>User:<?php echo $model->username ?></h4>
	<hr>
	<div class="row">
		<div class="col-md-12"> 
			<?php //if($profile!=null) {?>	
				
	<div class="row">
		<div class="col-md-5">
		<img src="<?php echo $model->avatar ?>" alt="<?php echo $model->username ?>">
		</div>
	</div>
			<label>Имя: </label><?php echo $model->first_name ?> <br>
			<label>Отчество: </label><?php echo $model->middle_name ?> <br>
			<label>Фамилия: </label><?php echo $model->second_name ?> <br>
			<label>Пол: </label><?php echo ($model->gender==0?'Женщина':'Мужчина') ?>
			<?php //} else {?>
		<!--	<label for="">Профиль отсутствует</label>-->
			<?php //} ?>
		</div>
	</div>
    </div>	



