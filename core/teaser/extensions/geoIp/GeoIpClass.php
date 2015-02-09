<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'geoipcity.inc';

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 22.04.14
 * Time: 10:26
 * @property GeoIp $gi
 * @property GeoIp $city
 * @property string $error
 */

class GeoIpClass
{
    public $ip;
    public $isTrial = true;

    private $_gi;
    private $_city;
    private $_errors = array();

    public function getGi()
    {
        if($this->_gi === null){
            $this->_gi = geoip_open(dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'GeoIp.dat', GEOIP_STANDARD);
        }

        return $this->_gi;
    }

    public function getCity()
    {
        if($this->_city === null){
            $this->_city = geoip_open(dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'GeoLiteCity.dat', GEOIP_STANDARD);
        }

        return $this->_city;
    }

    public function recordByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        return GeoIP_record_by_addr($this->getCity(), $ip);
    }

    public function countyCodeByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        return geoip_country_code_by_addr($this->getCity(), $ip);
    }

    public function countyNameByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        return geoip_country_name_by_addr($this->getCity(), $ip);
    }

    public function cityNameByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        $record = GeoIP_record_by_addr($this->getCity(), $ip);

        if($record)
            return $record->city;
        else
            return null;
    }

    public function regionCodeByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        if(!$this->isTrial)
            return geoip_region_by_addr($this->getCity(), $ip);
        else{
            $record = GeoIP_record_by_addr($this->getCity(), $ip);
            if($record)
                return $record->region;
        }

        return null;
    }

    public function regionNameByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        if(!$this->isTrial){
            $region = geoip_region_by_addr($this->getCity(), $ip);
        }else{
            $record = GeoIP_record_by_addr($this->getCity(), $ip);
            if($record)
                $region = $record->region;
            else
                $region = null;
        }

        if($region){
            $regions = require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'regions.php');

            if(isset($regions[$record->country_code][$region]))
                return $regions[$record->country_code][$region];
        }

        return null;
    }

    public function nameByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        return geoip_name_by_addr($this->getCity(), $ip);
    }

    public function orgByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        return geoip_org_by_addr($this->getCity(), $ip);
    }

    public function countryIdByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        return geoip_country_id_by_addr($this->getCity(), $ip);
    }

    public function countryCodeByIp($ip=null)
    {
        if($ip === null)
            $ip = $this->ip;

        if($ip === null){
            $this->error = 'Incorrect Ip address';
            return false;
        }

        return geoip_country_code_by_addr($this->getCity(), $ip);
    }

    public function getError()
    {
        return implode('; ', $this->_errors);
    }

    public function setError($error)
    {
        $this->_errors[] = $error;
    }

    public function close()
    {
        if($this->_gi !== null){
            geoip_close($this->_gi);
            unset($this->_gi);
        }

        if($this->_city !== null){
            geoip_close($this->_city);
            unset($this->_city);
        }
    }
}