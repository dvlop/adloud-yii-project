<?php
/**
 * Created by t0m
 * Date: 16.12.13
 * Time: 22:57
 */

namespace MLF;


use MLF\exceptions\FileNotFoundException;

class LayersConfig {

    private $settings;
    private static $_instance;

    private function __construct($fileName){
        $fileName = __DIR__ . DIRECTORY_SEPARATOR. 'settings' . DIRECTORY_SEPARATOR . $fileName;
        if(!file_exists($fileName)){
            throw new FileNotFoundException('settings file not found');
        }
        $json = file_get_contents($fileName);
        $parseResult = @json_decode($json, true);

        if(!$parseResult){
            throw new FileNotFoundException('incorrect settings file');
        }

        $this->settings = $parseResult;
    }

    public static function getInstance($fileName = 'layerSettings.json'){
        if(!isset(self::$_instance)){
            self::$_instance = new LayersConfig($fileName);
        }
        return self::$_instance;
    }

    public function getLayersName(){
        return $this->settings['layers'];
    }

}