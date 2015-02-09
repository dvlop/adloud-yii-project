<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 16.02.14
 * Time: 21:08
 */
//todo: normal namespaces
class AutoLoader {
    public static function register($initEnv = true){
        date_default_timezone_set('UTC');
        spl_autoload_register('self::autoLoad');
        if($initEnv){
            self::initEnvironment();
        }
    }

    public static function autoLoad($className)
    {
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

        $mlf = __DIR__  . DIRECTORY_SEPARATOR . $fileName;
        $teaser = __DIR__ . DIRECTORY_SEPARATOR. 'teaser' . DIRECTORY_SEPARATOR . $fileName;

        if(file_exists($mlf)){
            require $fileName;
        } elseif(file_exists($teaser)){
            require $teaser;
        }else{
            return false;
        }
        return false;
    }

    public static function initEnvironment(){
        define('DB_DATE_FORMAT', 'Y-m-d H:i:s');
        $key = "income-percent";
        $redis = \core\RedisConnection::getInstance()->getConnection($key);
        $redis->set($key, \config\Config::getInstance()->getIncomePercent());
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}