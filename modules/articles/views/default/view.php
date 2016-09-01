<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Tag;
use app\models\Tag_item;
/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Posts */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-view">

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
<strong>&#1055;&#1088;&#1086;&#1089;&#1084;&#1086;&#1090;&#1088;&#1086;&#1074;:</strong> <?php echo ($model->views)?>
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
	<div class="row">
		<div class="col-md-12">
		<h4>&#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1072;&#1088;&#1080;&#1080; <strong>(<?php echo count($comments)?>)</strong></h4>
		</div>
	</div>
<?php  if(!Yii::$app->user->isGuest){ ?>
			<div style="display:none" class="alert alert-success alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<span class="text"></span>
</div>
		<h4>Add comment</h4>
<?php $this->registerJs("
$('form>div>button.comment').click(function(e){
	e.preventDefault();
	$('.comment-block').css('opacity','0.3');
	$.ajax({
		url:'addcomment',
		type:'POST',
		data:{
			item_id:".$model->id.",
			text:$('form>textarea').val(),
			parent_id:'null'
		},
		success:function(data){
		data=JSON.parse(data);
			$('div.comment-block').html(data['render']);
			$('.alert-success').show();
			
	$('.comment-block').css('opacity','1');
			$('.alert-success>span.text').text(data['message']);
		},
		error:function(data){
			$('.alertform').html(data.responseText);
			
	$('.comment-block').css('opacity','1');
		}
	})
});
$('body').delegate('a.toggle-form','click',function(e){
	e.preventDefault();
	var form=$(this).parent().parent().parent().parent().parent().children('#'+$(this).attr('id'));
	if($(form).css('display')=='block')
		$(form).hide();
	else 
	{
		$(form).show();
		$(form).children('textarea').focus();
	};
});
$('body').delegate('.row form>button.answer','click',function(e){
	e.preventDefault();		
	$('.comment-block').css('opacity','0.3');
	var href=$(this).parent().attr('action'),
		text=$(this).parent().children('textarea').val(),
		parent_id=$(this).parent().parent().parent().attr('id');
	$.ajax({
		type:'POST',
		url:'addcomment',
		data:
		{
		
			item_id:".$model->id.",
			text:text,
			parent_id:parent_id,	
		},
		success:function(data){
			data=JSON.parse(data);
			$('.comment-block').css('opacity','1');
			$('div.comment-block').html(data['render']);
			$('.alert-success').show();
			$('.alert-success>span.text').text(data['message']);
		},
		error:function(data){
			$('.comment-block').css('opacity','1');
			$('.alertform').html(data.responseText);
		}
	})
});
");
?>
<div class="alertform"></div>
<div class="row">
<div class="col-md-7">
<div class="notes-form">
<form action="view" method="post">
<textarea class="form-control" name="text" id="" cols="" rows="6"></textarea>

<div class="form-group">
<button class="btn btn-success comment" type="submit">Add</button>
</div>
</form>
</div>
</div>
</div>
<?php }?>
<div class="row comment-block"><div class="col-md-12">
	<?php echo $this->render('comment',array('comments'=>$comments));?>
	</div></div>
