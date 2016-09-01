<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\links\models\Links */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="links-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(!Yii::$app->user->getisGuest()&&User::find()->where(['id'=>Yii::$app->user->id])->one()->isadmin){ ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>

    <div class="panel panel-default">

        <div class="panel-body">
            <?= Html::decode($model->text) ?>
        </div>
    </div>
<div  class="row">
	<div style="margin:10px" class="col-md-12">
	<?php if(isset($tags)&&count($tags)>0){ ?>
		<span class="taglist">
			<?php foreach($tags as $tag){ ?>
				<span style="font-size:15px;margin-right:10px" class="label label-info">
					<a style="color:white;text-decoration:none" href="/tag/view?id=<?php echo $tag->id ?>">
						<?php echo $tag->name ?>
					</a>
				</span>
			<?php } ?>
		</span>
	<?php } ?>
	</div>
</div>
</div>
