<?php

namespace models;
use core\Session;
use models\dataSource\AdsDataSource;
use models\dataSource\CampaignDataSource;
use models\Lists;

/**
 * @property CampaignDataSource $nextLayer
 */
class Campaign extends \MLF\layers\Logic implements \core\CRUDInterface
{

    const STATUS_DISABLED = 0;
    const STATUS_NO_MODERATED = 300;
    const STATUS_PUBLISHED = 1;
    const STATUS_PROHIBITED = 200;
    const STATUS_ARCHIVED = 500;

    private static $statusesNames = [
        self::STATUS_DISABLED => 'Кампания выключена',
        self::STATUS_NO_MODERATED => 'Ждёт модерации',
        self::STATUS_PUBLISHED => 'Кампания опубликована',
        self::STATUS_PROHIBITED => 'Заблокирована',
        self::STATUS_ARCHIVED => 'Кампания в архиве'
    ];

    private static $statusesClasses = [
        self::STATUS_DISABLED => 'switch',
        self::STATUS_NO_MODERATED => 'switch suspended',
        self::STATUS_PUBLISHED => 'switch',
        self::STATUS_PROHIBITED => 'switch stopped',
    ];

    public $description;
    public $clickPrice;
    public $categories;
    public $geo;
    public $startDate;
    public $stopDate;
    public $blackList;
    public $whiteList;
    public $limit;
    public $dailyLimit;
    public $publish;
    public $siteUrl;
    public $ageLimit;
    public $gender;
    public $subject;
    public $siteId;
    public $labelsId;

    private $id;
    private $shows;
    private $clicks;

    /**
     * @return \models\Campaign
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function initById($id){
        $data = $this->getById($id);

        if(!$data){
            return false;
        }
        $this->id = $id;
        $this->description = $data['description'];
        $this->clickPrice = $data['clickPrice'];
        $this->categories = $data['categories'];
        $this->geo = $data['geo'];
        $this->startDate =$data['startDate'];
        $this->stopDate = $data['stopDate'];
        $this->blackList = $data['blackList'];
        $this->whiteList = $data['whiteList'];
        $stats = $this->nextLayer->getCampaignStats($id, Session::getInstance()->getUserId(),new AdsDataSource());
        $this->shows = $stats['shows'];
        $this->clicks = $stats['clicks'];
        $this->siteId = $data['siteId'];
        $this->limit = $data['limit'];
        $this->dailyLimit = $data['dailyLimit'];
        $this->publish = isset($data['publish']) ? $data['publish'] : 0;
        $this->siteUrl = isset($data['site_url']) ? $data['site_url'] : '';
        $this->ageLimit = isset($data['age_limit']) ? $data['age_limit'] : '';
        $this->gender = isset($data['gender']) ? $data['gender'] : 0;
        $this->subject = isset($data['subject']) ? $data['subject'] : [];
        $this->labelsId = isset($data['labelsId']) ? $data['labelsId'] : null;
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getShows(){
        return $this->shows;
    }

    public function getClicks(){
        return $this->clicks;
    }

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

    public function getAdsStatus($id){
        return $this->nextLayer->getAdsStatus($id);
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

    public function getGeoCodes($id = null, $regions = null)
    {
        if($id === null)
            $id = $this->id;

        if(!$id)
            throw new \LogicException('needs campaign ID');

        $countryCodes = [];
        $regionCodes = [];

        if(!$geo = $regions){
            $regions = $this->findById(['geo'], $id);
            if($regions && $regions->geo)
                $geo = $regions->geo;
        }

        if($geo){
            if(is_string($geo))
                $geo = json_decode($geo, 1);

            $countries = isset($geo['country']) && $geo['country'] ? $geo['country'] : [];
            $regions = isset($geo['region']) && $geo['region'] ? $geo['region'] : [];

            if($countries){
                $codes = \models\Country::getInstance()->getCountriesById(['code'], $countries);
                if($codes){
                    foreach($codes as $code){
                        $countryCodes[] = $code->code;
                    }
                }
            }

            if($regions){
                $codes = \models\Region::getInstance()->getRegionsById(['code'], $regions);
                if($codes){
                    foreach($codes as $code){
                        $regionCodes[] = $code->code;
                    }
                }
            }
        }

        return (object)[
            'countries' => $countryCodes,
            'regions' => $regionCodes
        ];
    }

    public function save()
    {
        $data = array(
            'description'=> htmlspecialchars($this->description),
            'userId'=> Session::getInstance()->getUserId(),
            'clickPrice'=> $this->clickPrice,
            'categories'=> $this->categories,
            'geo'=> $this->geo,
            'startDate'=> $this->startDate,
            'endDate'=>  $this->stopDate,
            'blackList'=> $this->blackList,
            'dailyLimit'=> $this->dailyLimit,
            'limit'=> $this->limit,
            'site_url' => $this->siteUrl,
            'age_limit' => $this->ageLimit,
            'gender' => $this->gender,
            'siteId' => $this->siteId,
            'subject' => $this->subject,
            'publish' => self::STATUS_DISABLED,
        );
        $this->id = $this->nextLayer->save($data);
        return $this->id;
    }

    public function getList($limit = 100, $offset = 0, $userId = null)
    {
        return $this->nextLayer->getList($userId ? $userId : Session::getInstance()->getUserId(), new AdsDataSource(), $limit, $offset);
    }

    public function getStatsList($params)
    {
        $params['user_id'] = Session::getInstance()->getUserId();
        $params['adsDS'] = new AdsDataSource();
        return $this->nextLayer->getStatsList($params);
    }

    public function getCampaigns($params){
        $params['user_id'] = Session::getInstance()->getUserId();
        return $this->nextLayer->getCampaigns($params);
    }

    public function getBlackAndWhiteLists($id = null)
    {
        if($id === null)
            $id = $this->id;
        return $this->nextLayer->getBlackAndWhiteLists($id);
    }

    public function getById($id = null, $userId = null)
    {
        if($userId == null)
            $userId = Session::getInstance()->getUserId();

        $data = array(
            'id'=> $id,
            'userId'=> $userId,
        );

        return $this->nextLayer->getById($data);
    }

    public function setStatus($id = null, $statusId = self::STATUS_NO_MODERATED)
    {
        return $this->nextLayer->setStatus($id, $statusId);
    }

    public function setLists($listData, $campaignsToClean = [])
    {
        if(!isset($listData['type']) || !$listData['type'])
            throw new \LogicException('no list type given');
        if(!isset($listData['sites']) || !$listData['sites'])
            throw new \LogicException('no sites id given');
        if(!isset($listData['campaigns']) || !$listData['campaigns'])
            throw new \LogicException('no campaigns id given');

        if($listData['type'] == Lists::BLACK_LIST){
            $listData['type'] = CampaignDataSource::BLACK_LIST_NAME;
            $listData['alterType'] = CampaignDataSource::WHITE_LIST_NAME;
        }elseif($listData['type'] == Lists::WHITE_LIST){
            $listData['type'] = CampaignDataSource::WHITE_LIST_NAME;
            $listData['alterType'] = CampaignDataSource::BLACK_LIST_NAME;
        }else
            throw new \LogicException('not correct list type given');

        return $this->nextLayer->setLists($listData, $campaignsToClean);
    }

    public function removeLists(Lists $list)
    {
        if(!$list && !$list->sites)
            throw new \LogicException('no sites list data given');

        return $this->nextLayer->removeLists($list->campaigns);
    }

    public function update($id = null)
    {
        if($id == null)
            $id = $this->id;
        if(!$id)
            throw new \LogicException('needs campaign ID');

        $geo = $this->getGeoCodes($id, $this->geo);

        $data = array(
            'description'=> htmlspecialchars($this->description),
            'userId'=> Session::getInstance()->getUserId(),
            'clickPrice'=> $this->clickPrice,
            'categories'=> $this->categories,
            'geo'=> $this->geo,
            'startDate'=>  $this->startDate,
            'endDate'=> $this->stopDate,
            'blackList'=> $this->blackList,
            'whiteList' => $this->whiteList,
            'dailyLimit'=> $this->dailyLimit,
            'limit'=> $this->limit,
            'site_url' => $this->siteUrl,
            'age_limit' => $this->ageLimit,
            'gender' => $this->gender,
            'siteId' => $this->siteId,
            'subject' => $this->subject,
            'countries' => $geo->countries,
            'regions' => $geo->regions,
        );

        return $this->nextLayer->update($id ? $id : $this->id ,$data, Session::getInstance()->getUserId());
    }

    public function delete($id = null)
    {
        if(!$id)
            $id = $this->id;

        if(!$id)
            return false;

        $publisher = Publisher::getInstance();

        if(!$publisher->unPublishDeletedCampaign($id)){
            return false;
        }

        return true;
    }
}