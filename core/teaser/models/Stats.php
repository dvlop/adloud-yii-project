<?php
/**
 * Created by PhpStorm.
 * User: M-A-X
 * Date: 10.07.14
 * Time: 13:50
 */

namespace models;
use core\Session;
use models\dataSource\StatsDataSource;

/**
 * @property StatsDataSource $nextLayer
 */
class Stats extends \MLF\layers\Logic
{

    /**
     * @return \models\Stats
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }


    public function getSites($arParams = [])
    {
        if(Session::getInstance()->getUserAccessLevel() !== User::ACCESS_ADMIN){
            throw new \LogicException('user is not an admin');
        }

        return $this->nextLayer->getSites($arParams);
    }

    public function getBlocks($arParams = [])
    {
        if(Session::getInstance()->getUserAccessLevel() !== User::ACCESS_ADMIN){
            throw new \LogicException('user is not an admin');
        }

        return $this->nextLayer->getBlocks($arParams);
    }

}