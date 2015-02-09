<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 22.04.14
 * Time: 16:49
 * @property GeoIpClass $worker
 */

class Geo extends \CComponent
{
    private $_service;

    public function init()
    {

    }

    /**
     * @return GeoIpClass|null
     */
    public function getWorker()
    {
        if($this->_service === null){
            $file = \Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'teaser'.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'geoIp'.DIRECTORY_SEPARATOR.'GeoIpClass.php';
            if(file_exists($file)){
                require_once($file);
                $this->_service = new GeoIpClass();
            }
        }
        return $this->_service;
    }
} 