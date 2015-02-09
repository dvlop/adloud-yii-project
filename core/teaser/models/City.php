<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\CityDataSource;

/**
 * @property CityDataSource $nextLayer
 */
class City extends \MLF\layers\Logic implements CRUDInterface
{
    /*
     * @ var int
     */
    public $id;

    /*
     * @ var int
     */
    public $country_id;

    /*
     * @ var string
     */
    public $region_id;

    /*
     * @ var string
     */
    public $name;


    /**
     * @return \models\City
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

    public function delete($id = null)
    {
        return $this->nextLayer->delete($id);
    }

    public function getCitiesByCountriesId($attributes = [], $arrayOfCountriesIds = [])
    {
        return $this->nextLayer->getCitiesByCountriesId($attributes, $arrayOfCountriesIds);
    }

    public function getCitiesByRegionsId($attributes = [], $arrayOfRegionsIds = [])
    {
        return $this->nextLayer->getCitiesByRegionsId($attributes, $arrayOfRegionsIds);
    }

    public function getCitiesAndCountries($arrayOfCountriesIds = [])
    {
        return $this->nextLayer->getCitiesAndCountries($arrayOfCountriesIds);
    }

    public function getCitiesAndRegions($arrayOfRegionsIds = [])
    {
        return $this->nextLayer->getCitiesAndRegions($arrayOfRegionsIds);
    }

    public function getCityById($cityId = null)
    {
        return $this->nextLayer->getCityById($cityId);
    }

    public function getCitiesById($attributes = [], $arrayOfCitiesId = [])
    {
        return $this->nextLayer->getCitiesById($attributes, $arrayOfCitiesId);
    }

    public function getIpDiapasons($arrayOfCitiesId = [])
    {
        return $this->nextLayer->getIpDiapasons($arrayOfCitiesId);
    }
}