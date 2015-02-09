<?php
/**
 * Created by t0m
 * Date: 12.01.14
 * Time: 20:14
 */

namespace core;


use config\Config;

class ImageServers {

    public static function getImageServerLocation(){
        $config = Config::getInstance()->getImageServersSettings();
        return array('image1', $config['image1']['address'], $config['image1']['port']);
    }
}