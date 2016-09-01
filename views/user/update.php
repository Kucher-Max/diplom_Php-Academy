<?php
use yii\helpers\Html;
try{ 

/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Posts */

$this->title = 'Update User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="posts-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
		'profile'=>$profile
    ]) ?>
<?php
}catch(Exception $e){
return 'hiupdate';
}
?>

</div>
	