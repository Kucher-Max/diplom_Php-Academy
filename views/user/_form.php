<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use himiklab\thumbnail\EasyThumbnailImage;

 try{
/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Posts */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="users-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<?php //if($profile!=null){ ?>
    <?php if(!$model->isNewRecord){ ?>
        <div class="form-group">
            <?= EasyThumbnailImage::thumbnailImg(
                '@webroot'.$model->avatar,
                380,
                220,
                EasyThumbnailImage::THUMBNAIL_OUTBOUND,
                ['alt' => $model->username]
            );
            ?>
        </div>
    <?php } ?>	

    <?php //} ?>		
    <?= $form->field($model, 'avatar')->fileInput() ?>	
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>	
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>		
    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>		
    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>	
    <?= $form->field($model, 'gender')->dropdownList(['1'=>'Мужчина','0'=>'Женщина']) ?>		
<?php }catch(Exception $e){ 

return 'hiform';
}
?>
<br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
