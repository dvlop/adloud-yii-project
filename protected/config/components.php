<?php

return [
    'assetManager' => [
        'class' => 'application.components.AssetManager',
    ],

    'user' => [
        'class' => 'application.components.WebUser',
        'loginUrl' => ['/index/login'],
        'logoutUrl' => ['/index/logout'],
        'allowAutoLogin' => true,
    ],

    'urlManager' => [
        'class'=>'application.components.UrlManager',
        'urlFormat' => 'path',
        'urlSuffix' => '/',
        'showScriptName' => false,
        'caseSensitive' => false,
        'rules' => require(dirname(__FILE__).DIRECTORY_SEPARATOR.'rules.php'),
    ],

    'errorHandler' => [
//			 use 'index/error' action to display errors
//			'errorAction'=>'index/error',
    ],

    'db'            => [
        'class'=>'CDbConnection',
        'connectionString'=>'pgsql:host=cp1;port=5432;dbname=persistent_data',
        'username'=>'web',
        'password'=>'4igir-Web',
        'emulatePrepare'=>true,
    ],
    'dbActual'      => [
        'class'=>'CDbConnection',
        'connectionString'=>'pgsql:host=db1;port=5432;dbname=actual_data',
        'username'=>'web',
        'password'=>'4igir-Web',
        'emulatePrepare'=>true,
    ],

    'log' => [
        'class' => 'CLogRouter',
        'routes' => [
            [
                'class'=>'CFileLogRoute',
                'levels'=>'error, warning',
            ],
        ],
    ],

    'mail' => [
        'class' => 'ext.yii-mail.YiiMail',
        'transportType' => 'smtp',
        'transportOptions' => [
            'host' => 'smtp.mailgun.org',
            'username' => 'postmaster@sandboxf8f99cbf38d64e5c9be14245e3ff22e1.mailgun.org',
            'password' => '4tmguif4jur5',
            //'port' => '465',
            //'encryption' => 'ssl',
        ],
        'viewPath' => 'views.mail',
    ],

    'test' => [
        'class' => 'application.components.Test',
    ],

    'clientScript' => [
        'scriptMap' => [
            //'jquery.js' => 'public/assets/plugins/jquery-1.11.0.min.js',
            //'jquery.min.js' => 'public/assets/plugins/jquery-1.11.0.min.js',
            //'jquery-ui.min.js' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js',
        ],
    ],

    'geo' => [
        'class' => 'ext.geo.Geo',
    ],

    'curl' => [
        'class' => 'application\modules\payment\extensions\curl\Curl',
    ],

    'currency' => [
        'class' => 'application\modules\payment\extensions\currency\Currency',
    ],

    'pageElements' => [
        'class' => 'application.components.PageElements',
    ],

    'mailchimp' => [
        'class' => 'ext.mailchimp.Mailchimp',
        'apiKey' => '193af18f0acd4c2113573362fd9a0c53-us8',
        'listName' => '5aa9495a5e',
        'askUser' => false,
    ],

    'image' => [
        'class' => 'ext.image.ImageClass',
        'defaultExt' => '.jpg',
    ],

    'bootstrap' => [
        'class' => 'bootstrap.components.Bootstrap',
    ],
];