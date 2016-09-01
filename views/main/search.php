<?php
/**
 * @var $search string
 */
?>
<div class="row">
	<div class="col-md-12">
	<h2>Поиск: <strong><?php echo $key ?></strong></h2>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<?php if (isset($posts) &&count($posts)>0){ ?>
			<div class="row">
				<div class="col-md-12">
				<h4><a href="articles/"></a>Статьи:</h4>
				</div>
			</div>
			<?php foreach($posts as $post){ ?>
				<div class="row">
					<div class="col-md-12">
					<a href="/articles/default/view?id=<?php echo $post->id ?>"><?php echo $post->title ?></a>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<div class="col-md-3">
		<?php if (isset($links) &&count($links)>0){ ?>
			<div class="row">
				<div class="col-md-12">
				<h4><a href="articles/"></a>Ссылки:</h4>
				</div>
			</div>
			<?php foreach($links as $link){ ?>
				<div class="row">
					<div class="col-md-12">
					<a href="/links/default/view?id=<?php echo $link->id ?>"><?php echo $link->title ?></a>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<div class="col-md-3">
		<?php if (isset($tags) &&count($tags)>0){ ?>
			<div class="row">
				<div class="col-md-12">
				<h4><a href="/articles/"></a>Теги:</h4>
				</div>
			</div>
			<?php foreach($tags as $tag){ ?>
				<div class="row">
					<div class="col-md-12">
					<a href="/tag/view?id=<?php echo $tag->id ?>"><?php echo $tag->name ?></a>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<div class="col-md-3">
	<?php if (isset($users) &&count($users)>0){ ?>
	<div class="row">
		<div class="col-md-12">
		<h4><a href="articles/"></a>Пользователи:</h4>
		</div>
	</div>
	<?php foreach($users as $user){ ?>
		<div class="row">
			<div class="col-md-12">
			<a href="/user/view?id=<?php echo $user->id ?>"><?php echo $user->username ?></a>
			</div>
		</div>
	<?php } ?>
<?php } ?>
	</div>
</div>



