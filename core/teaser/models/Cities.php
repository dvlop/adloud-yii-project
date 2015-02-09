<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\CitiesDataSource;

/**
 * @property CitiesDataSource $nextLayer
 */
class Cities extends \MLF\layers\Logic implements CRUDInterface
{
    public $id;
    public $country_id;
    public $name_ru;
    public $name_en;
    public $region;
    public $postal_code;
    public $latitude;
    public $longitude;



    /**
     * @return \models\Cities
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

    public function getCitiesAndCountries($arrayOfCountriesIds = [])
    {
        return $this->nextLayer->getCitiesAndCountries($arrayOfCountriesIds);
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