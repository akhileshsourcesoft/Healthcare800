<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],

       'session' => [
			'name' => '_frontendSessionId', // unique for backend
			'savePath' => __DIR__ . '/../runtime', // a temporary folder on backend
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
	'bluepay' => [ 
		'class' => 'common\components\BluePay',
		'accountID' => '100382963925',
		'secretKey' => 'T/HDM18GBSJMUP9QKUXBGWGJ6LZBPPJS',
		'mode' => 'Test',
	
	],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request'=>[
			'class' => 'common\components\Request',
			'web'=> '/frontend/web'
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [              
              'page/<slug>' => 'page/slug',
              'contactprovider/<slug>' => 'contact-provider/slug',
              'pages/<slug>' => 'pages/slug',
              'defaultRoute' => '/site/index',
              // '<alias:slug>' => 'page/<alias>', 	
			  //'<alias:login|logout|register>' => 'account/<alias>',
          ],   
		],
    ],
        'modules' => [
    'gii' => [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.1.*', 'XXX.XXX.XXX.XXX'] // adjust this to your needs
    ],
    'gridview' =>  [
        'class' => '\kartik\grid\Module'
        // enter optional module parameters below - only if you need to  
        // use your own export download action or custom translation 
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
    ],
],
    'params' => $params,
];
