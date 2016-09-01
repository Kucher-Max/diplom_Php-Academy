<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [       
            'cookieValidationKey' => '770HCgfWcj9czOYkf7EdVnwNnCxG2-8X',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['main/login']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'mail' => [
			'class' => 'yii\swiftmailer\Mailer',
			'useFileTransport' => false,
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'mx1.hostinger.com.ua',
				'username' => '',
				'password' => '',
				'port' => '110',
				'encryption' => 'tls',
				],
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'site/login',
            ],
        ],

        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl'  => '@web/assets'
        ],
    ],

		'modules' => [
			'articles' => [
				'class' => 'app\modules\articles\Module'],
			'links' => [
				'class' => 'app\modules\links\Module'],

			],
		'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
