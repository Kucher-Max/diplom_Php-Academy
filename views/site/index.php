<body data-spy="scroll" data-offset="0" data-target="#theMenu">

<!-- Menu -->


<section id="home" name="home"></section>
<div id="headerwrap">
    <div class="container">

    </div><! --/container -->
</div><! --/headerwrap -->


<! -- SERVICE SECTION -->
<section id="services" name="services"></section>


<div id="testimonials">
    <div class="container">
        <div class="row" >
            <div class="col-lg-8 col-lg-offset-2 mt">

                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                               <div class="carousel-inner" role="listbox">
<?php if(isset($comments)&&count($comments)>0){ ?>
	<?php foreach($comments as $k=>$comment) { ?>
		<?php if($k==0): ?>		
			<div  class="item active  ">
		<?php else: ?>	
			<div class="item  ">		
		<?php endif;?>	
      <nav style="width:800px;height:400px ">
      <div class="carousel-caption">
			<p>
				<img class="img-circle" src="<?php echo $comment['user']->profile->avatar ?>" width="80">
			</p>
		<h3> 
			<a href="/user/view/?id=<?php echo($comment['user']->id)?>">
				<?php echo $comment['comment']->user_name  ?>
			</a> 
		</h3>
		<p><?php echo $comment['comment']->text ?></p>
		<p>
			<a href="/articles/default/view/?id=<?php echo $comment['comment']->item_id ?>">
				<?php echo $comment['post']->title ?>
			</a>
		</p>
			
      </div>	

		</div>
	<?php } ?>
<?php } ?>

                    </div>
                    <!-- Indicators -->
                   <ol class="carousel-indicators">
<?php if(isset($comments)&&count($comments)>0){ ?>
	<?php foreach($comments as $key=>$comment) { ?>
		<?php if($key==0){ 	?>
			<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		<?php }else{ ?>
			<li data-target="#carousel-example-generic" data-slide-to="<?php echo $key ?>"></li>
		<?php } ?>
	<?php } ?>
<?php } ?>
                    </ol>
                </div>

            </div><! --/col-lg-8 -->
        </div><! --/row -->
    </div><! --/container -->
</div><! --/testimonials -->

<section id="contact" name="contact"></section>
<! -- CONTACT SEPARATOR -->
<div class="sep contact" data-stellar-background-ratio="0.5"></div>


<?php 


	$this->registerJsFile('/js/jquery.js');
	$this->registerJsFile('/js/main.js');
	$this->registerJsFile('js/classie.js');
	$this->registerJsFile('js/bootstrap.min.js');
	$this->registerJsFile('js/smoothscroll.js');
	$this->registerJsFile('js/jquery.stellar.min.js');
	$this->registerJsFile('js/fancybox/jquery.fancybox.js');
	$this->registerJs("
        jQuery.stellar({
            horizontalScrolling: false,
            verticalOffset: 40
        });
        //    fancybox
        jQuery('.fancybox').fancybox();
	");
 $this->registerCssFile('/js/fancybox/jquery.fancybox.css');
 ?>
</body>
</html>
