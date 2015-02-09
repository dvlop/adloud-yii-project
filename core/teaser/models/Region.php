<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\RegionDataSource;

/**
 * @property RegionDataSource $nextLayer
 */
class Region extends \MLF\layers\Logic implements CRUDInterface
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
    * @ var int
    */
    public $country_id;

    /*
     * @ var string
     */
    public $name;

    /**
     * @return \models\Region
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

    public function getRegionsByCountriesId($attributes = [], $arrayOfCountriesIds = [])
    {
        return $this->nextLayer->getRegionsByCountriesId($attributes, $arrayOfCountriesIds);
    }

    public function getRegionsAndCountries($attributes = [], $arrayOfCountriesIds = [])
    {
        return $this->nextLayer->getRegionsAndCountries($attributes, $arrayOfCountriesIds);
    }

    public function getCities($attributes = [], $arrayOfRegionsIds = [])
    {
        return City::getInstance()->getCitiesByRegionsId($attributes, $arrayOfRegionsIds);
    }

    public function getCitiesAndRegions($arrayOfIds = [])
    {
        return City::getInstance()->getCitiesAndRegions($arrayOfIds);
    }

    public function getRegionsById($attributes = [], $arrayOfCitiesId = [])
    {
        $arrayOfCitiesId = array_filter($arrayOfCitiesId);
        return $this->nextLayer->getRegionsById($attributes, $arrayOfCitiesId);
    }
}