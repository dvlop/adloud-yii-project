<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\CountiriesDataSource;

/**
 * @property CountriesDataSource $nextLayer
 */
class Countries extends \MLF\layers\Logic implements CRUDInterface
{
    /*
     * @ var int
     */
    public $id;

    /*
     * @ var string
     */
    public $name_ru;

    /*
     * @ var string
     */
    public $name_en;

    /*
     * @ var string
     */
    public $code;

    /**
     * @return \models\Countries
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
        return Cities::getInstance()->getCitiesByCountriesId($attributes, $arrayOfIds);
    }

    public function getCitiesAndCountries($arrayOfIds = [])
    {
        return Cities::getInstance()->getCitiesAndCountries($arrayOfIds);
    }

    public function getIpDiapasons($arrayOfCountriesId = [])
    {
        return $this->nextLayer->getIpDiapasons($arrayOfCountriesId);
    }
}