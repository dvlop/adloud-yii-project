<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.08.14
 * Time: 17:48
 */

date_default_timezone_set('UTC');
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('DB_DATE_FORMAT', 'Y-m-d H:i:s');

if(!isset($argv[1]) || !$argv[1])
    die('nothing to do');

switch($argv[1]){
    case 'set-payouts':
        $scriptName = 'SetAutoPayouts';
        break;
    case 'fix-all-clicks':
        $scriptName = 'FixAllClicks';
        break;
    case 'fix-block-sizes':
        $scriptName = 'FixBlockSizes';
        break;
    default:
        die('nothing to do');
        break;
}

$yii = dirname(__FILE__).'/../../framework/yii.php';
$config = dirname(__FILE__).'/../config/config.default.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

Yii::createWebApplication($config);

$scriptName = 'application\\console\\'.$scriptName;

if(!class_exists($scriptName))
    Yii::app()->end('Class '.$scriptName.' not found');

$params = [];
foreach($argv as $num => $arg){
    if($num > 1)
        $params[] = $arg;
}

$script = new $scriptName($params);

if(!$script->run()){
    foreach($script->getErrors() as $error){
        echo $error;
        echo '\n';
    }
}else{
    echo $script->getMessage();
}

Yii::app()->end();