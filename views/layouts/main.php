<?php

use app\assets\AppAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\components\AlertWidget;
use yii\helpers\Url;

/* @var $content string
 * @var $this \yii\web\View */
AppAsset::register($this);
$this->beginPage();
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= Html::csrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">   
		<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
<?php

$this->registerCssFile('/css/bootstrap.css');
$this->registerCssFile('/css/style.css');
$this->registerCssFile('/css/site.css');
$this->registerCssFile('/css//font-awesome.min.css');
	
?>
    <link rel="shortcut icon" href="/assets/ico/favicon.ico">

    <title>Бюджетные путешествия!</title>
  
        <?php $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']); ?>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>

    <div class="wrap">
        <?php
        NavBar::begin(
            [
                'options' => [
                    'class' => 'navbar navbar-default',
                    'id' => 'main-menu'
                ],
                'renderInnerContainer' => true,
                'innerContainerOptions' => [
                    'class' => 'container'
                ],
                'brandLabel' => '<img src="'.\Yii::$app->request->BaseUrl.'/img/brand.gif"/>',
                'brandUrl' => [
                    '/'
                ],
                'brandOptions' => [
                    'class' => 'navbar-brand'
                ]
            ]
        );
        if (!Yii::$app->user->isGuest):
            ?>
            <div class="navbar-form navbar-right">
                <button class="btn btn-sm btn-default"
                        data-container="body"
                        data-toggle="popover"
                        data-trigger="focus"
                        data-placement="bottom"
                        data-title="<?= Yii::$app->user->identity['username'] ?>"
                        data-content="
                            <a href='<?= Url::to(['/user/view?id='.Yii::$app->user->id]) ?>' data-method='post'>Мой профиль</a><br>
                            <a href='<?= Url::to(['/main/logout']) ?>' data-method='post'>Выход</a>
                        ">
                    <span class="glyphicon glyphicon-user"></span>
                </button>
            </div>
            <?php
        endif;
        $menuItems = [
            [
                'label' => 'Статьи',
                'url' => ['/articles']
            ],
            [
                'label' => 'Ссылки',
                'url' => ['/links']
            ],
            [
                'label' => 'О сайте',
                'url' => [
                    '#'
                ],
                'linkOptions' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'style' => 'cursor: pointer; outline: none;'
                ],
            ],           
        ];
	
        if (Yii::$app->user->isGuest){
            $menuItems[] = [
                'label' => 'Регистрация',
                'url' => ['/main/reg']
            ];
            $menuItems[] = [
                'label' => 'Войти',
                'url' => ['/main/login']
            ];
		}elseif(Yii::$app->user->identity->isadmin)
		{
		
            $menuItems[] =[
                'label' => 'Пользователи',
                'url' => ['/user']
            ];
		}

        echo Nav::widget([
            'items' => $menuItems,
            'activateParents' => true,
            'encodeLabels' => false,
            'options' => [
                'class' => 'navbar-nav navbar-right'
            ]
        ]);

        Modal::begin([
            'header' => '<h2>"LowCoastTrip"</h2>',
            'id' => 'modal'
        ]);
        echo 'Мы научим Вас увидеть мир за копейки ! Поверьте - это ПРОСТО.';
        Modal::end();

        ActiveForm::begin(
            [
                'action' => ['/main/search'],
                'method' => 'get',
                'options' => [
                    'class' => 'navbar-form navbar-right'
                ]
            ]
        );
        echo '<div class="input-group input-group-sm">';
        echo Html::input(
            'type: text',
            'search',
            '',
            [
                'placeholder' => 'Поиск по сайту',
                'class' => 'form-control'
            ]
        );
        echo '<span class="input-group-btn">';
        echo Html::submitButton(
            '<span class="glyphicon glyphicon-search"></span>',
            [
                'class' => 'btn btn-info',
                'onClick' => 'window.location.href = this.form.action+"/?word="+this.form.search.value.replace(/[^\w\а-яё\А-ЯЁ]+/g, "_");'
            ]
        );
        echo '</span></div>';
        ActiveForm::end();
		
  
        NavBar::end();
        ?>
        <div class="container">
            <?= AlertWidget::widget() ?>
            <?= $content ?>
        </div>
    </div>
<div id="contactwrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <p class="pull-left">
				&copy; Created by Max Kucher 2016 <br>
				<a href="mailto:admin@lowcoasttrip.esy.es">admin@lowcoasttrip.esy.es</a>
				</p>
            </div>
        </div>
    </div>
</div>

    <?php


	$this->registerJsFile('/js/jquery.js');
	$this->registerJsFile('/js/main.js');
 $this->endBody(); ?>
    </body>
    </html>
<?php
$this->endPage();