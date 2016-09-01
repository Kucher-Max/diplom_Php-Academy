<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\links\models\Links */

$this->title = 'Update Links: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="links-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'tags'=>$tags,
		'usestags'=>$usestags
    ]) ?>

</div>
