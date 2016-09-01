<div class="row">
	<div class="col-md-10 col-md-offset-1">
	<h2>Тег: <strong><?php echo $tag->name ?></strong></h2>
	</div>
</div>
<?php if(isset($posts)&&count($posts)>0){ ?>
<div style="margin-top:40px" class="row">
	<div class="col-md-10 col-md-offset-1">
		<h4>Статьи с этим тегом:</h4>
		<?php foreach($posts as $post ){ ?>
		
			<div class="row">
				<div class="col-md-10 ">
					<a href="/articles/default/view?id=<?= $post->id ?>"><?php echo $post->title; ?></a>
				</div>
			</div>
		
		<?php } ?>
	</div>
</div>
<?php } else{ ?>
<div style="margin-top:40px" class="row">
	<div class="col-md-10 col-md-offset-1">
		<h4>Статей с этим тегом пока нет</h4>
	</div>
</div>
<?php } ?>
<?php if(isset($links)&&count($links)>0){ ?>
<div style="margin-top:40px" class="row">
	<div class="col-md-10 col-md-offset-1">
		<h4>Ссылки с этим тегом:</h4>
		<?php foreach($links as $link ){ ?>
		
			<div class="row">
				<div class="col-md-10 ">
					<a href="/links/default/view?id=<?= $link->id ?>"><?php echo $link->title; ?></a>
				</div>
			</div>
		
		<?php } ?>
	</div>
</div>
<?php } else{ ?>
<div style="margin-top:40px" class="row">
	<div class="col-md-10 col-md-offset-1">
		<h4>Ссылок с этим тегом пока нет</h4>
	</div>
</div>
<?php } ?>