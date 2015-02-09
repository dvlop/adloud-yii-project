<?php

// uncomment the following to define a path alias
// \Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

\Yii::setPathOfAlias('MLF', dirname(__FILE__).'/../../core/MLF');
\Yii::setPathOfAlias('core', dirname(__FILE__).'/../../core/teaser/core');
\Yii::setPathOfAlias('config', dirname(__FILE__).'/../../core/teaser/config');
\Yii::setPathOfAlias('models', dirname(__FILE__).'/../../core/teaser/models');
\Yii::setPathOfAlias('exceptions', dirname(__FILE__).'/../../core/teaser/exceptions');
\Yii::setPathOfAlias('ads', dirname(__FILE__).'/../../core/teaser/ads');
\Yii::setPathOfAlias('interfaces', dirname(__FILE__).'/../../core/teaser/interfaces');
\Yii::setPathOfAlias('templates', dirname(__FILE__).'/../../core/teaser/templates');
\Yii::setPathOfAlias('themes', dirname(__FILE__).'/../../themes');
\Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return [
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'AdLoud',
    'homeUrl' => '/index',
    'defaultController' => 'index',
    'sourceLanguage' => 'ru',
    'language' => 'ru',

    // preloading 'log' component
    'preload' =>['log'],

    // autoloading model and component classes
    'import' => [
        'application.forms.*',
        'application.extensions.*',
        'application.vendor.teaser.exceptions.*',
        'application.components.*',
    ],

    'modules' => require(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules.php'),
    'components' => require(dirname(__FILE__).DIRECTORY_SEPARATOR.'components.php'),
    'params' => require(dirname(__FILE__).DIRECTORY_SEPARATOR.'params.php'),
];