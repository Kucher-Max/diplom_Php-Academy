<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use himiklab\thumbnail\EasyThumbnailImage;

/* @var $this yii\web\View */
/* @var $model app\modules\links\models\Links */
/* @var $form yii\widgets\ActiveForm */
$script=<<<JS
	function fillspan()
	{
		$('form>input[name="tags[]"]').remove();
		var str='';
		$.each(tagstack,function(i,val){
str+='<span style="font-size:15px;margin-right:10px" class="label label-info">'+val+'<span style="cursor:pointer" class="glyphicon glyphicon-remove removetag"></span> </span>';
		$('form').append('<input type="hidden" name="tags[]" value="'+val+'">');
		});
		
		$('.listtag').html('Tag: '+str);
		
	}
	 var tagstack=[];
	 $('input[name="tags[]"]').each(function(i,elem){
		tagstack.push($(this).val())
	 })
		$('body').delegate('.addtag','click',function(e){
			e.preventDefault();
			var tag=$('select').val();
			tagstack.push(tag);
			fillspan();
		})
		$('body').delegate('.removetag','click',function(){
			var tag=$(this).parent().text();
			tagstack.splice(tagstack.indexOf($.trim(tag)),1);
			fillspan();
		});
JS;
$this->registerJs($script,yii\web\View::POS_READY);
?>

<div class="links-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<?php if (isset($usestags)&& count($usestags)>0){ ?>
		<?php foreach($usestags as $t){ ?>
			<input type="hidden" name="tags[]" value="<?php echo $t->name ?>">
		<?php } ?>			
	<?php } ?>	
    <?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
<?php
	echo '<span class="listtag">Tag:';
		if (isset($usestags)&& count($usestags)>0)
		{
			foreach($usestags as $t)
			{ ?>
		<span style="font-size:15px;margin-right:10px" class="label label-info">
		<?php echo $t->name ?>
		<span style="cursor:pointer" class="glyphicon glyphicon-remove removetag">
		</span>
		</span>				
	<?php }
		}
	echo '</span><br>';
	if(isset($tags)&&count($tags)>0)
	{
		echo '<br><select name="tags" id="">';
		foreach($tags as $k=>$tag)
		{
			if($k==0)
				echo '<option selected value="'.$tag->name.'">'.$tag->name.'</option>';
			else
				echo '<option  value="'.$tag->name.'">'.$tag->name.'</option>';
		}	
		echo'</select>';
		echo '<button style="margin-left:15px;" class="addtag btn btn-default btn-sm">Add tag</button>';
	}
	?>
    <?= $form->field($model, 'img')->fileInput(['maxlength' => true]) ?>

    <?php if(!$model->isNewRecord){ ?>

        <div class="form-group">
            <?= EasyThumbnailImage::thumbnailImg(
                '@webroot'.$model->img,
                380,
                220,
                EasyThumbnailImage::THUMBNAIL_OUTBOUND,
                ['alt' => $model->title]
            );
            ?>
        </div>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
