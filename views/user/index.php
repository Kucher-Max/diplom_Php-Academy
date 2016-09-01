<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\blog\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php foreach ($users as $user) { ?>
<div class="row">
	<div class="col-md-12">
	<div class="row">
		<div class="col-md-2">
			<a href="/user/view/?id=<?php echo $user->id ?>">
				<strong>
					<?php echo $user->username ?>
				</strong>
			</a>
		</div>
<?php if(Yii::$app->user->identity!=null && Yii::$app->user->identity->isadmin){ ?>
		<div class="col-md-10">
		[<a href="/user/update/?id=<?php echo $user->id ?>"> Edit </a>]
		[<a href="/user/del/?id=<?php echo $user->id ?>"> Delete </a>]
		</div>
<? } ?>
	</div>	
	</div>
</div>
    <?php } ?>

</div>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]); ?>
