<?php

date_default_timezone_set('UTC');
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('DB_DATE_FORMAT', 'Y-m-d H:i:s');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/config.default.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
//specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

Yii::createWebApplication($config)->run();
