<style>
.result-item{
    transition: 0.2s;
    padding-left:12px;
}
.search-result{
    position:absolute;
    display: block;
    width:200px;
    min-height: 100px;
    height:auto;
    top:200px;
    left:400px;
    background-color:white;
    z-index:9999;
    display:none;
  box-shadow:
   0 1px 4px rgba(0, 0, 0, .3),
   -23px 0 20px -23px rgba(0, 0, 0, .8),
   23px 0 20px -23px rgba(0, 0, 0, .8),
   0 0 40px rgba(0, 0, 0, .1) inset;
    
}
</style>
<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;

use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\blog\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
$script=<<<JS
	    $("#tagsearch").keyup(function(e){
        e.preventDefault();
        if(($(this).val().length)>2){
		
	var left=$('#tagsearch').offset().left;
	var top=$('#tagsearch').offset().top+$('#tagsearch').height()+20;
            var text=$(this).val();
			$.ajax({
				url:'/tag/search',
				type:'POST',
				data:{
					text:text
				},
				success:function(data){
				var items=data.split(';');
				console.log(items);
                var str='';
                for(i in items){
					var row=items[i].split('|');
                    str+='<div class="row"><div class="col-md-12 "><div class="result-item"><a href="/tag/view/?id='+row[1]+'">'+row[0]+'</a></div></div></div>';
                    
                    
                };
                $(".search-result").html(str);
if(items.lengrh!=1 && items[0]!=""){
	$(".search-result").offset({top:top,left:left}).show();	
	if($('.search-result').offset().left!=left ||$('.search-result').offset().top!=top)
		$(".search-result").offset({top:top,left:left});
}
else
	$('.search-result').html('<div class="result-item"><strong>&#1053;&#1077;&#1090; &#1088;&#1077;&#1079;&#1091;&#1083;&#1100;&#1090;&#1072;&#1090;&#1086;&#1074;</strong></div>');  
				},
				error:function(data){
					console.log(data.responseText);
				}
			});
        }
    });
  $(document).click(function(event) {
    if ($(event.target).closest(".search-result").length) return;
    if ($(event.target).closest("#tagsearch").length) return;
    $(".search-result").hide();
    event.stopPropagation();
  });
    $("#tagsearch").focusin(function(){
        if($(this).val().length>2){
			if($('.search-result').html()==''){
			  $('#tagsearch').triggerHandler('keyup');
			}else{
			
             $(".search-result").show();
			}
		}
	})
JS;
$this->registerJs($script,yii\web\View::POS_READY);
?>
<div class="search-result"></div>
<div class="row">
	<div class="text-right col-md-12">
		<form  class="form-inline">
  <div class="form-group">
    <label  for="tagsearch">&#1055;&#1086;&#1080;&#1089;&#1082; &#1087;&#1086; &#1090;&#1077;&#1075;&#1072;&#1084;: </label>
    <input type="text"  class="form-control" id="tagsearch" placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1085;&#1072;&#1079;&#1074;&#1072;&#1085;&#1080;&#1077; &#1090;&#1077;&#1075;&#1072;">
  </div>
  <button type="submit" class="btn btn-default">&#1055;&#1086;&#1080;&#1089;&#1082;</button>
</form>
	</div>
</div>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if(!Yii::$app->user->getisGuest()&&User::find()->where(['id'=>Yii::$app->user->id])->one()->isadmin){ 	?>
        <p>
            <?= Html::a('New Post', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php } ?>

    <div class="row">
        <?php foreach ($posts as $arr) { ?>

            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">

                    <?php
                    echo EasyThumbnailImage::thumbnailImg(
                        '@webroot'.$arr->img,
                        380,
                        220,
                        EasyThumbnailImage::THUMBNAIL_OUTBOUND,
                        ['alt' => $arr->title]
                    );
                    ?>

                    <div class="caption">
                        <h3><?= $arr->title ?></h3>
                        <p><?= $arr->text_preview ?></p>
                        <p><a href="/articles/default/view?id=<?= $arr->id ?>" class="btn btn-primary" role="button">Читать</a> </p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]); ?>
