<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\CitiesIpDataSource;

/**
 * @property CitiesIpDataSource $nextLayer
 */
class CitiesIp extends \MLF\layers\Logic implements CRUDInterface
{
    /*
     * @ var int
     */
    public $city_id;

    /*
     * @ var int
     */
    public $begin_ip;

    /*
     * @ var string
     */
    public $end_ip;

    /**
     * @return \models\CitiesIp
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
        return $this->city_id;
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

    public function getDiapasonsByCitiesId($arrayOfCitiesId = [])
    {
        return $this->nextLayer->getDiapasonsByCitiesId($arrayOfCitiesId);
    }
}