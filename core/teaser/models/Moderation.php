<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 23.03.14
 * Time: 14:40
 */

namespace models;
use core\Session;

/**
 * @property \models\dataSource\ModerationDataSource $nextLayer
 */
class Moderation extends \MLF\layers\Logic {

    const DEFAULT_TABLE_NAME = 'sites';

    /**
     * @return \models\Moderation
     * @throws \LogicException
     */
    public static function getInstance()
    {
        if(Session::getInstance()->getUserAccessLevel() !== User::ACCESS_ADMIN){
            throw new \LogicException('user is not an admin');
        }
        return parent::getInstance();
    }

    public function getSitesList($limit = 100, $offset = 0, $all = false){
        $status = $all ? null : Site::STATUS_NO_MODERATED;
        return $this->nextLayer->getSitesList($status, $limit, $offset);
    }

    public function getAdsList($limit = 100, $offset = 0){
        $statusIN = \models\Ads::STATUS_NO_MODERATED.', '.\models\Ads::STATUS_PROHIBITED;
        return $this->nextLayer->getAdsList($limit, $offset, $statusIN);
    }

    public function setSiteModerationState($siteId, $state = 1, $isAdmin = false){
        $result =  $this->nextLayer->setSiteModeratedState($siteId, $state);
        if($result && $state == Site::STATUS_PUBLISHED){
            $publisher = Publisher::getInstance();
            return $publisher->publishSite($siteId, $isAdmin);
        }
        return true;
    }

    public function setAdsModerationState($adsId, $state, $shockContent = false, $adultContent = false){
        $state = $state ? \models\Ads::STATUS_DISABLED : \models\Ads::STATUS_PROHIBITED;
        return $this->nextLayer->setAdsModeratedState($adsId, $state, $shockContent, $adultContent);
    }

    public function getCount($tableName = self::DEFAULT_TABLE_NAME)
    {
        return $this->count($tableName);
    }


    public function find($attributes = [], $tableName = self::DEFAULT_TABLE_NAME)
    {
        return $this->nextLayer->find($attributes, $tableName);
    }

    public function getAll($tableName = self::DEFAULT_TABLE_NAME)
    {
        return $this->nextLayer->getAll($tableName);
    }

    public function findAll($attributes = [], $tableName = self::DEFAULT_TABLE_NAME)
    {
        return $this->nextLayer->findAll($attributes, $tableName);
    }

    public function save($attributes = [], $tableName = self::DEFAULT_TABLE_NAME)
    {
        return $this->nextLayer->save($attributes, $tableName);
    }

} 