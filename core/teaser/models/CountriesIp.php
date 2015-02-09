<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\CountriesIpDataSource;

/**
 * @property CountriesIpDataSource $nextLayer
 */
class CountriesIp extends \MLF\layers\Logic implements CRUDInterface
{
    /*
     * @ var int
     */
    public $country_id;

    /*
     * @ var int
     */
    public $begin_ip;

    /*
     * @ var string
     */
    public $end_ip;

    /**
     * @return \models\CountriesIp
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
        return $this->country_id;
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

    public function getDiapasonsByCountriesId($arrayOfCountriesId = [])
    {
        return $this->nextLayer->getDiapasonsByCountriesId($arrayOfCountriesId);
    }
}