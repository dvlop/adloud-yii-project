<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\CountryDataSource;

/**
 * @property CountryDataSource $nextLayer
 */
class Country extends \MLF\layers\Logic implements CRUDInterface
{
    /*
     * @ var int
     */
    public $id;

    /*
    * @ var int
    */
    public $city_id;

    /*
     * @ var string
     */
    public $name;

    /**
     * @return \models\Country
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function initById($id)
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function update()
    {
        //
    }

    public function save()
    {
        return $this->saveModel();
    }

    public function delete($id)
    {
        return true;
    }

    public function getCities($attributes = [], $arrayOfIds = [])
    {
        return City::getInstance()->getCitiesByCountriesId($attributes, $arrayOfIds);
    }

    public function getRegions($attributes = [], $arrayOfIds = [])
    {
        return Region::getInstance()->getRegionsByCountriesId($attributes, $arrayOfIds);
    }

    public function getCitiesAndCountries($arrayOfIds = [])
    {
        return City::getInstance()->getCitiesAndCountries($arrayOfIds);
    }

    public function getRegionsAndCountries($arrayOfIds = [])
    {
        return Region::getInstance()->getRegionsAndCountries($arrayOfIds);
    }

    public function getIpDiapasons($arrayOfCountriesId = [])
    {
        return $this->nextLayer->getIpDiapasons($arrayOfCountriesId);
    }

    public function getCountriesById($attributes = [], $arrayOfIds = [])
    {
        $arrayOfIds = array_filter($arrayOfIds);
        return $this->nextLayer->getCountriesById($attributes, $arrayOfIds);
    }
}