<?php

namespace models;
use ads\AdsStandard;
use core\Session;
use models\dataSource\AdsDataSource;

/**
 * @property \models\dataSource\AdsDataSource $nextLayer
 */
class Ads extends \MLF\layers\Logic implements \core\CRUDInterface
{
    const STATUS_DISABLED = 2;
    const STATUS_NO_MODERATED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_PROHIBITED = 200;
    const STATUS_ARCHIVED = 500;

    private static $statusesNames = [
        self::STATUS_DISABLED => 'Тизер выключен',
        self::STATUS_NO_MODERATED => 'Ждёт модерации',
        self::STATUS_PUBLISHED => 'Тизер опубликован',
        self::STATUS_PROHIBITED => 'Тизер отклонен модератором',
        self::STATUS_ARCHIVED => 'Тизер в архиве',
    ];

    private static $statusesClasses = [
        self::STATUS_DISABLED => 'switch',
        self::STATUS_NO_MODERATED => 'switch suspended',
        self::STATUS_PUBLISHED => 'switch',
        self::STATUS_PROHIBITED => 'switch stopped',
    ];

    private $id;
    private $imageLocation;
    private $shows;
    private $clicks;
    private $status;
    private $moderated;

    public $imageFile;
    public $imageUrl;
    public $imageClickUrl;
    public $campaignId;
    public $clickPrice;
    public $mainCategory;
    public $additionalCategories;
    public $maxClicks;
    public $geoCountries;
    public $geoRegions;
    public $blackList;
    public $whiteList;
    public $adult;
    public $shock;
    public $sms;
    public $animation;

    /**
     * @var \ads\AdsAbstract
     */
    public $adsContent;

    /**
     * @return \models\Ads
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getModerated($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;
        return $statusId == self::STATUS_DISABLED || $statusId == self::STATUS_PUBLISHED;
    }

    public function getIsEnabled($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;
        return $statusId !== self::STATUS_DISABLED;
    }

    public function getStatusName($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        if(isset(self::$statusesNames[$statusId])){
            return self::$statusesNames[$statusId];
        }else{
            return self::$statusesNames[self::STATUS_NO_MODERATED];
        }
    }

    public function getClicks(){
        return $this->clicks;
    }

    public function getShows(){
        return $this->shows;
    }

    public function getStatusClass($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        if(isset(self::$statusesClasses[$statusId])){
            return self::$statusesClasses[$statusId];
        }else{
            return self::$statusesClasses[self::STATUS_NO_MODERATED];
        }
    }

    public function getImageLocation(){
        return $this->imageLocation;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($id = null, $statusId = null)
    {
        if($id === null)
            $id = $this->id;
        if(!$id)
            return false;
        if($statusId === null)
            return false;
        return $this->nextLayer->setStatus($id, $statusId);
    }

    public function saveAds()
    {
        $minClickPrice = $this->nextLayer->getCategoryDefaultPrice($this->mainCategory);

        if(empty($this->clickPrice)){
            if(!$minClickPrice){
                throw new \exceptions\DataLayerException('unknown category');
            }
            $this->clickPrice = $minClickPrice;
        }else{
            if($this->clickPrice < $minClickPrice){
                throw new \exceptions\DataLayerException('not correct click price min prise: '.$minClickPrice);
            }
        }

        $data = array(
            'campaignId' => $this->campaignId,
            'clickPrice' => $this->clickPrice,
            'mainCategory' => $this->mainCategory,
            'additionalCategories' => $this->additionalCategories,
            'maxClicks' => $this->maxClicks,
            'geo_countries' => $this->geoCountries,
            'geo_regions' => $this->geoRegions,
            'type' => $this->adsContent->type(),
            'content' => $this->adsContent->getSerialized(),
            'statusId' => self::STATUS_DISABLED,
            'blackList' => $this->blackList ? $this->blackList : null,
            'whiteList' => $this->whiteList ? $this->whiteList : null,
            'adult' => (bool)$this->adult,
            'shock' => (bool)$this->shock,
            'sms' => (bool)$this->sms,
            'animation' => (bool)$this->animation,
        );

        $id = $this->nextLayer->save($data, Session::getInstance()->getUserId());
        $this->id = $id;

        return $this->id;
    }

    public function getList($campaignId = false, $limit = 100, $offset = 0){
        return $this->nextLayer->getList($campaignId, Session::getInstance()->getUserId(), $limit, $offset);
    }

    public function getStatsList($params)
    {
        $params['user_id'] = Session::getInstance()->getUserId();
        $params['adsDS'] = new AdsDataSource();
        return $this->nextLayer->getStatsList($params);
    }

    public function getAds($params){
        $params['user_id'] = Session::getInstance()->getUserId();
        return $this->nextLayer->getAds($params);
    }

    public function initById($id)
    {
        $data = $this->nextLayer->initById($id, Session::getInstance()->getUserId());

        $this->id = $data['id'];
        $this->shows = $data['shows'];
        $this->clicks = $data['clicks'];
        $this->campaignId = $data['campaignId'];
        $this->clickPrice = $data['clickPrice'];
        $this->additionalCategories = $data['additionalCategories'];
        $this->geoCountries = $data['geoCountries'];
        $this->geoRegions = $data['geoRegions'];
        $this->maxClicks = $data['maxClicks'];
        $this->status = $data['status'];
        $this->moderated = $this->getModerated($data['status']);
        $this->blackList = $data['blackList'];
        $this->whiteList = $data['whiteList'];
        $this->adsContent = new AdsStandard($data['content']);
        $this->imageLocation = $data['content']['imageUrl'];
        $this->imageFile = $data['content']['imageFile'];
        $this->adult = (bool)$data['adult'];
        $this->shock = (bool)$data['shock'];
        $this->sms = (bool)$data['sms'];
        $this->animation = (bool)$data['animation'];

        return true;
    }

    public function updateAds($id = null)
    {
        $data = array(
            'campaignId' => $this->campaignId,
            'clickPrice' => $this->clickPrice,
            'mainCategory' => $this->mainCategory,
            'additionalCategories' => $this->additionalCategories,
            'maxClicks' => $this->maxClicks,
            'geo_countries' => $this->geoCountries,
            'geo_regions' => $this->geoRegions,
            'type' => $this->adsContent->type(),
            'content' => $this->adsContent->getSerialized(),
            'blackList' => $this->blackList ? $this->blackList : null,
            'whiteList' => $this->whiteList ? $this->whiteList : null,
            'adult' => (bool)$this->adult,
            'shock' => (bool)$this->shock,
            'sms' => (bool)$this->sms,
            'animation' => (bool)$this->animation,
        );

        $id = $this->nextLayer->update($data, Session::getInstance()->getUserId(), $id ? $id : $this->id);

        if(\core\RedisIO::get("ads:{$id}")){
            $publisher = \models\Publisher::getInstance();
            try{
                $publisher->unPublishAds($id);
                $publisher->publishAds($id);
            }catch(\Exception $e){

            }
        }

        $this->id = $id;

        return true;
    }

    public function delete($id)
    {
        $publisher = Publisher::getInstance();
        return $publisher->unPublishDeletedAds($id);
    }

    public function save()
    {

    }

    public function update()
    {

    }

    public function setAnimations($ids, $animation = false)
    {
        return $this->nextLayer->setAnimations($ids, $animation);
    }
}