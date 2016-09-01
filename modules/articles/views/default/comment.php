
<?php
use app\models\Comment;
use app\models\User;
$this->registerJs("
	$('body').delegate('.likelink','click',function(e){
		var text=$(this).parent().children('span');
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:$(this).attr('href')+'/?id='+$(this).parent().parent().attr('id'),
			success:function(data){	
				$(text).text(data);
			},
			error:function(data){
				$('.alertform').html(data.responseText);
			}
		});
	})
	$('body').delegate('.hide-changeform','click',function(e){
		e.preventDefault();
		$(this).parent().parent().children('.maintext').show();
		$(this).parent().hide();
		
	})
	$('body').delegate('a#editcomment','click',function(e){
		e.preventDefault();
		var form=$(this).parent().parent().parent().find('form');
		$(this).parent().parent().parent().find('.maintext').hide();
		$(form).show();
		
	})
	$('body').delegate('form>button.save-changeform','click',function(e){
		e.preventDefault();
		var text=$(this).parent().children('textarea').val();
		var url=$(this).parent().parent().parent().parent().find('a#editcomment').attr('href');
		$('.comment-block').css('opacity','0.3');
		$.ajax({
			type:'POST',
			url:url,
			data:{
				text:text
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
	})
	$('body').delegate('a#deletecomment','click',function(e){
		e.preventDefault();
		$('.comment-block').css('opacity','0.3');
		$.ajax({
			type:'POST',
			url:$(this).attr('href'),
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
	})
");
 ?>
 <div style="margin-left:20px"> 
<?php foreach($comments as $comment){?>
<div style="margin-bottom:5px;<?php //echo $margin ?>" class="row">
	<div style="border:1px solid #ddd;border-radius:4px" class="col-md-7 ">
		<div class="row">
			<div class="col-md-10">
<?php $user=User::find()->where(['username'=>$comment->user_name])->one(); ?>
			<img style="width:50px" src="<?php echo $user->profile->avatar ?>" alt="<?php echo $user->username ?>">
			<strong><a href="/user/view/?id=<?php echo $user->id ?>"><?php echo $comment->user_name?></a></strong> 
			&#1074; <span style="color:#b5b5b5;">
				<?php echo $comment->created_at ?>
			</span>
			<?php 
if((!Yii::$app->user->isGuest)&&Yii::$app->user->identity->isadmin&&date('i',strtotime($comment->created_at))-date('i')!=0){?>
				[<a  id="editcomment" href="editcomment/?id=<?php echo $comment->id?>">&#1056;&#1077;&#1076;&#1072;&#1082;&#1090;&#1080;&#1088;&#1086;&#1074;&#1072;&#1090;&#1100;</a>]
			<?php 
			}
			if(User::find()->where(['id'=>Yii::$app->user->id])->one()->isadmin){
				echo '[<a id="deletecomment" href="deletecomment/?id='.$comment->id.'"> &#1059;&#1076;&#1072;&#1083;&#1080;&#1090;&#1100;</a>]';
			}?>
			</div>
			<?php if(!Yii::$app->user->isGuest){?>
			<div id="<?php echo $comment->id?>" class="col-md-2 text-right" style="right:0px">
				<span>
				<?php if(Yii::$app->user->identity!=null && Yii::$app->user->identity->id!=$user->id ){ ?>
					<a class="likelink" style="text-decoration:none;color:green;font-size:20px" href="like">+</a>
					<?php } ?>
					<span style="color:green"><?php echo$comment->likes ?> </span>
				</span>
				<span>
				<?php if(Yii::$app->user->identity!=null && Yii::$app->user->identity->id!=$user->id ){ ?>
					<a class="likelink" style="text-decoration:none;color:red;font-size:20px" href="unlike">-</a>	
<?php } ?>					
					<span style="color:red"> <?php echo$comment->unlikes ?></span>
				</span>
			</div>
			<?php }?>
		</div>
		<div style="padding:15px;border-top:1px solid #aaa;border-bottom:1px solid #aaa;margin:5px" class="row">
			<div class="col-md-12">
				<div class="maintext">
					<?php echo $comment->text?>
				</div>
				<form style="display:none" method="post">
					<textarea  name="text" class="form-control"  rows="3"><?php echo $comment->text?></textarea>
					<button class="btn btn-success btn-sm save-changeform"type="submit">&#1057;&#1086;&#1093;&#1088;&#1072;&#1085;&#1080;&#1090;&#1100;</button>
					<button class="btn btn-warning btn-sm hide-changeform">&#1054;&#1090;&#1084;&#1077;&#1085;&#1072;</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div  class="col-md-10">
				<a id="<?php echo $comment->id?>" class="toggle-form" style="cursor:pointer">Ответить</a>
			</div>
		</div>
	</div>
</div>
<div style="display:none" id="<?php echo $comment->id?>" class="row form">
	<div class="col-md-7">
		<form  action="answer" method="POST">
			<textarea  name="text" class="form-control"  rows="3"></textarea>
			<button type="submit" class="btn btn-success answer">Ответить</button>
		</form>
	</div>
</div>
<?php
echo $this->render('comment',['comments'=>Comment::find()->where(['parent_id'=>$comment->id])->orderBy('likes DESC')->all()]);
?>
<?php }?>

 </div>