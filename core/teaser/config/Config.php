<?php
namespace config;
use \exceptions\ConfigException;

/**
 * Created by t0m
 * Date: 16.12.13
 * Time: 22:57
 */
class Config
{
    private $settings;
    private static $_instance;

    private function __construct($fileName)
    {
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($fileName)) {
            throw new \exceptions\ConfigException('settings file not found');
        }
        $json = file_get_contents($fileName);
        $parseResult = @json_decode($json, true);

        if (!$parseResult) {
            throw new \exceptions\ConfigException('incorrect settings file');
        }

        $this->settings = $parseResult;
    }

    public static function getInstance($fileName = 'settings.json')
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Config($fileName);
        }
        return self::$_instance;
    }

    public function getPostgresSettings($instanceName)
    {
        return $this->settings['endpoints']['postgres'][$instanceName];
    }

    public function getRedisSettings()
    {
        return $this->settings['endpoints']['redis'];
    }

    public function getDateFormat()
    {
        return $this->settings['dateFormat'];
    }

    public function getImageServersSettings()
    {
        return $this->settings['endpoints']['image'];
    }

    public function getBaseUrl(){
        return $this->settings['baseUrl'];
    }

    public function getHomeUrl(){
        return $this->settings['homeUrl'];
    }

    public function getIncomePercent(){
        return $this->settings['incomePercent'];
    }

}