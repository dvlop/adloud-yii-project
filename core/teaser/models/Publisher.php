<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 09.02.14
 * Time: 19:17
 */

namespace models;


use application\models\UserAgent;
use core\RatingManager;
use core\Session;
use MLF\layers\Decorable;
use MLF\layers\Logic;
use models\dataSource\AdsDataSource;
use models\dataSource\BlockDataSource;
use models\dataSource\CampaignDataSource;
use core\RedisIO;

/**
 * @property \models\dataSource\PublisherDataSource $nextLayer
 */
class Publisher extends Logic {

    private $adsDataSource;
    private $blockDataSource;
    private $campaignDataSource;
    /**
     * @return \models\Publisher
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function publishAds($adsId, $userId = null, $updateRegions = true, $updateUa = true, $updateTargets = true, $isAdmin = false){
        if($userId === null)
            $userId = Session::getInstance()->getUserId();

        $adsData = $this->adsDataSource->initById($adsId, $userId);

        if($adsData['status'] == Ads::STATUS_ARCHIVED)
            return true;

        if(!$adsData){
            throw new \LogicException('ads not found');
        }

        $adsData['userId'] = $userId;
        $ctr = $adsData['shows'] ? ($adsData['clicks']/ $adsData['shows']) : 0;
        if($ctr > 0.2){
            throw new \LogicException('strange ctr');
        }
        $user = User::getInstance();
        $user->initById($userId);

        if(!$isAdmin && $user->getMoneyBalance() < $adsData['clickPrice']){
            throw new \LogicException('not enough money');
        }

        $adsData['statusId'] = \models\Ads::STATUS_PUBLISHED;

        if($this->nextLayer->publishAds($adsData, new RatingManager())){
            $campaign = $this->campaignDataSource->getById(['userId' => $userId, 'id' => $adsData['campaignId']]);

            return $this->nextLayer->setCampaignLimit($campaign['limit'], $adsData['campaignId']);
        }

        return false;
    }

    public function publishCampaign($campaignID, $userId = null){

        if($userId === null)
            $userId = Session::getInstance()->getUserId();

        $campaignData = $this->adsDataSource->getList($campaignID, $userId);

        if(!$campaignData){
            if(!\models\Campaign::getInstance()->setStatus($campaignID, \models\Campaign::STATUS_PUBLISHED))
                throw new \LogicException('can not change campaign status');

            return true;
        }elseif(!\models\Campaign::getInstance()->getModerated($campaignData[0]['campaignStatus'])){
            throw new \LogicException('campaign is not moderated');
        }

        if(!\models\Campaign::getInstance()->setStatus($campaignID, \models\Campaign::STATUS_PUBLISHED))
            throw new \LogicException('can not change campaign status');

        $errors = false;
        $user = User::getInstance();
        $user->initById($userId);
        $adsModel = \models\Ads::getInstance();

        foreach($campaignData as $ads){
            $ads['userId'] = $userId;

            if($user->getMoneyBalance() < $ads['clickPrice']){
                throw new \LogicException('not enough money');
            }
            if(!$adsModel->getModerated($ads['status'])){
                continue;
            }

            $errors = [];

            try{
                $this->publishAds($ads['id']);
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }

        if($errors){
            throw new \LogicException(implode('; ', $errors));
        }

        $campaign = $this->campaignDataSource->getById(['userId' => Session::getInstance()->getUserId(), 'id' => $campaignID]);

        return $this->nextLayer->setCampaignLimit($campaign['limit'], $campaignID);
    }

    //todo: security fix
    public function unPublishAds($adsId){
        return $this->nextLayer->unPublishAds($adsId, \models\Ads::STATUS_DISABLED);
    }

    public function unPublishDeletedAds($adsId){
        return $this->nextLayer->unPublishAds($adsId, \models\Ads::STATUS_ARCHIVED);
    }

    public function unPublishCampaign($campaignID){
        if(!\models\Campaign::getInstance()->setStatus($campaignID, \models\Campaign::STATUS_DISABLED))
            throw new \LogicException('can not change campaign status');

        $arrayDisabledStatusses = [
            \models\Ads::STATUS_ARCHIVED,
            \models\Ads::STATUS_PROHIBITED,
            \models\Ads::STATUS_NO_MODERATED
        ];

        return $this->nextLayer->unPublishCampaign($campaignID, \models\Ads::STATUS_DISABLED, $arrayDisabledStatusses);
    }

    public function unPublishDeletedCampaign($campaignID){
        if(!\models\Campaign::getInstance()->setStatus($campaignID, \models\Campaign::STATUS_ARCHIVED))
            throw new \LogicException('can not change campaign status');

        return $this->nextLayer->unPublishCampaign($campaignID, \models\Ads::STATUS_ARCHIVED);
    }

    public function publishBlock($id, $siteModel = null, $isAdmin = false){
        if($isAdmin)
            $userId = null;
        else
            $userId = Session::getInstance()->getUserId();

        $blockData = $this->blockDataSource->initById($id, $userId, true);

        if($siteModel === null){
            $siteModel = Site::getInstance();
            if(!$siteModel->initById($blockData['siteId'])){
                throw new \LogicException('site dos`nt not exist');
            }
        }

        if(!$isAdmin && !$siteModel->getIsModerated())
            throw new \LogicException('site is not moderated or disabled');

        $blockData['userId'] = $userId;
        return $this->nextLayer->publishBlock($blockData);
    }

    public function unPublishBlock($id, $update = true){
        return $this->nextLayer->unPublishBlock($id, $update, Block::STATUS_DISABLED);
    }

    public function unPublishDeletedBlock($id, $update = true){
        return $this->nextLayer->unPublishBlock($id, $update, \models\Ads::STATUS_ARCHIVED);
    }

    public function unPublishSite($id){
        $blocks = $this->blockDataSource->getList([
            'userId' => Session::getInstance()->getUserId(),
            'siteId' => $id
        ]);
        foreach($blocks as $block){
            $this->unPublishBlock($block['id']);
        }

        $siteIdString = "site:{$id}";
        RedisIO::delete($siteIdString);
        return true;
    }

    public function unPublishDeletedSite($id){
        if(!\models\Site::getInstance()->setStatus(\models\Site::STATUS_ARCHIVED, $id))
            throw new \LogicException('can not change campaign status');

        $blocks = $this->blockDataSource->getList([
            'userId' => Session::getInstance()->getUserId(),
            'siteId' => $id
        ]);
        foreach($blocks as $block){
            $this->unPublishDeletedBlock($block['id']);
        }

        $siteIdString = "site:{$id}";
        RedisIO::delete($siteIdString);
        return true;
    }

    public function publishSite($id, $isAdmin = false){
        $siteModel = Site::getInstance();
        if(!$siteModel->initById($id))
            throw new \LogicException('site dos`nt not exist');
        if(!$isAdmin && !$siteModel->getIsModerated())
            throw new \LogicException('site is not moderated or disabled');

        $blocks = $this->blockDataSource->getList([
            'userId' => Session::getInstance()->getUserId(),
            'siteId' => $id
        ]);

        try{
            \models\Site::getInstance()->setStatus(\models\Site::STATUS_PUBLISHED, $id);
        }catch(\Exception $e){

        }

        foreach($blocks as $block){
            $this->publishBlock($block['id'], $siteModel, $isAdmin);
        }

        return true;
    }

    protected function __construct(Decorable $nextLayer = null){
        parent::__construct($nextLayer);

        $this->adsDataSource = new AdsDataSource();
        $this->blockDataSource = new BlockDataSource();
        $this->campaignDataSource = new CampaignDataSource();
    }
}