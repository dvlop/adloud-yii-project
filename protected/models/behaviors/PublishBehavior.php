<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 15.08.14
 * Time: 10:58
 */

namespace application\models\behaviors;

use application\models\Blocks;
use application\models\Categories;
use application\models\Country;
use application\models\Region;
use models\Publisher;
use application\models\Campaign;
use core\RedisIO;
use application\models\Ads;

/**
 * Class PublishBehavior
 * @package application\models\behaviors
 */
class PublishBehavior extends \CActiveRecordBehavior
{
    private static $_redisKeys = [
        'ads_shows' => 'ads-shows:',
        'block_shows' => 'block-shows:',
        'ads_clicks' => 'ads-clicks:',
        'block_clicks' => 'block-clicks:',
        'campaign_expenses' => 'campaign-expenses:'
    ];

    private $_cliks;
    private $_shows;
    private $_expenses;

    public $round = 3;

    /**
     * @return \application\models\Ads | \application\models\Campaign | \application\models\Blocks | \application\models\Sites
     */
    public function getOwner()
    {
        return parent::getOwner();
    }

    public function publish($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        if($name = $this->getOwnerName()){
            switch($name){
                case 'ads':
                    return $this->publishAds($id, $userId, $attr);
                    break;
                case 'campaign':
                    return $this->publishCampaign($id, $userId, $attr);
                    break;
                case 'block':
                    return $this->publishBlock($id, $userId, $attr);
                    break;
                case 'site':
                    return $this->publishSite($id, $userId, $attr);
                    break;
            }
        }

        return false;
    }

    public function unPublish($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        if($name = $this->getOwnerName()){
            switch($name){
                case 'ads':
                    return $this->unPublishAds($id, $userId, $attr);
                    break;
                case 'campaign':
                    return $this->unPublishCampaign($id, $userId, $attr);
                    break;
                case 'block':
                    return $this->unPublishBlock($id, $userId, $attr);
                    break;
                case 'site':
                    return $this->unPublishSite($id, $userId, $attr);
                    break;
            }
        }

        return false;
    }

    public function publishAds($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        $userId = intval($userId);
        if(!$userId)
            $userId = $this->getOwner()->getUserId();

        try{
            return Publisher::getInstance()->publishAds($id, $userId, true, \Yii::app()->user->isAdmin);
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
            return false;
        }
    }

    public function publishCampaign($id = null, $userId = null, $attr = null)
    {
        $owner = null;

        if($id === null){
            $id = $this->getOwner()->id;
        }

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        if(!$userId)
            $userId = $this->getOwner()->getUserId();

        try{
            return Publisher::getInstance()->publishCampaign($id, $userId);
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
            return false;
        }
    }

    public function publishBlock($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        if(!$userId)
            $userId = $this->getOwner()->getUserId();

        try{
            return Publisher::getInstance()->publishBlock($id);
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
            return false;
        }
    }

    public function publishSite($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        try{
            Publisher::getInstance()->publishSite($id);
            return true;
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
            return false;
        }
    }

    public function unPublishAds($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        try{
            return Publisher::getInstance()->unPublishAds($id);
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
            return false;
        }
    }

    public function unPublishCampaign($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        try{
            return Publisher::getInstance()->unPublishCampaign($id);
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
            return false;
        }
    }

    public function unPublishBlock($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        try{
            Publisher::getInstance()->unPublishBlock($id);
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
        }

        return false;
    }

    public function unPublishSite($id = null, $userId = null, $attr = null)
    {
        if($id === null)
            $id = $this->getOwner()->id;

        if(!is_array($id))
            $id = intval($id);

        if(!$id)
            return false;

        try{
            Publisher::getInstance()->unPublishSite($id);
            return true;
        }catch(\Exception $e){
            $this->getOwner()->addError(null, $e->getMessage());
            return false;
        }
    }

    public function updateAdsInfo(Campaign $owner = null, $id = null)
    {
        if($owner === null)
            $owner = $this->getOwner()->campaign;

        if(!$owner){
            $this->getOwner()->addError(null, 'Не удалось найти кампанию');
            return false;
        }

        if($id === null){
            $where = 'campaign_id = :campaign_id';
            $attributes = [
                'campaign_id' => $owner->id,
            ];
        }else{
            $where = 'id = :id';
            $attributes = [
                'id' => $id,
            ];
        }

        $columns = [];

        $columns['campaign_id'] = $owner->id;
        if($geo = $owner->getGeography()){
            $countries = [];
            $regions = [];

            if(isset($geo['country']) && $geo['country']){
                foreach($geo['country'] as $id){
                    if($country = Country::model()->findByPk($id))
                        $countries[] = $country->code;
                }
            }

            if(isset($geo['region']) && $geo['region']){
                foreach($geo['region'] as $id){
                    if($region = Region::model()->findByPk($id))
                        $regions[] = $region->code;
                }
            }

            $columns['geo_countries'] = $countries ? $owner->parseDbString($countries) : null;
            $columns['geo_regions'] = $regions ? $owner->parseDbString($regions) : null;
        }

        $cats = [];
        foreach(Categories::model()->findAll() as $cat){
            $cats[] = $cat->id;
        }
        $columns['categories'] = $this->getOwner()->parseDbString($cats);

        $columns['white_list'] = $owner->getWhiteList() ? $owner->getWhiteList()->sites : null;
        $columns['black_list'] = $owner->getBlackList() ? $owner->getBlackList()->sites : null;

        try{
            $command = $this->getOwner()->getDbConnection()->createCommand();
            $command->update(Ads::model()->tableName(), $columns, $where, $attributes);
            $command->execute();
        }catch(\Exception $e){

        }

        $columns['user_id'] = $owner->getUserId();

        $ads = Ads::model()->findAll($where, $attributes);

        if($ads === null){
            try{
                $this->getOwner()->setActualConnection();
                $command = $this->getOwner()->getDbConnection()->createCommand();
                $command->delete(Ads::model()->tableName(), $where, $attributes);
                $command->execute();
                $this->getOwner()->setPersistentConnection();
            }catch (\Exception $e){

            }

            $owner->addError(null, 'Не удалось найти тизеры для публикации');
            return false;
        }

        try{
            $this->getOwner()->setActualConnection();

            foreach($ads as $ad){
                if($ad->status == Ads::STATUS_PUBLISHED || $ad->status == Ads::STATUS_DISABLED || $ad->status == Ads::STATUS_MODERATED){
                    if(!$actualAd = Ads::model()->findByPk($ad->id))
                        $actualAd = new Ads();

                    $actualAd->setAttributes(array_merge($columns, [
                        'click_price' => $ad->click_price,
                        'rating' => $ad->rating,
                        'clicks' => $this->getRedisValue('ads_clicks'),
                        'shows' => $this->getRedisValue('ads_shows'),
                        'id' => $ad->id,
                        'content' => $ad->content,
                        'type' => $ad->type,
                        'adult' => $ad->adult !== null ? $ad->adult : false,
                        'shock' => $ad->shock !== null ? $ad->shock : false,
                        'sms' => $ad->sms !== null ? $ad->sms : false,
                        'animation' => $ad->animation !== null ? $ad->animation : false,
                        'banned_blocks' => $ad->banned_blocks,
                    ]));

                    $actualAd->categories = $columns['categories'];
                    $actualAd->save(false);
                }
            }

            $this->getOwner()->setPersistentConnection();
            return true;
        }catch(\Exception $e){
            $owner->addError(null, $e->getMessage());
            return false;
        }
    }

    public function getClicks()
    {
        if($this->_cliks === null){
            $this->_cliks = (int)$this->getRedisValue('clicks');
        }

        return $this->_cliks;
    }

    public function getShows()
    {
        if($this->_shows === null){
            $this->_shows = (int)$this->getRedisValue('shows');
        }

        return $this->_shows;
    }

    public function getCtr($round = null)
    {
        if($round === null)
            $round = $this->round;

        $round = intval($round);

        if($round == 0)
            return 0;

        if($this->getShows() == 0)
            return 0;
        else
            return round($this->getClicks()/$this->getShows(), $round);
    }

    public function getCosts()
    {
        return 0;
    }

    public function getExpenses()
    {
        if($this->_expenses === null){
            $this->_expenses = floatval($this->getRedisValue('expenses'));
        }

        $this->_expenses;
    }

    private function getOwnerName()
    {
        $name = get_class($this->getOwner());

        if(strpos($name, 'Ads') !== false)
            return 'ads';
        if(strpos($name, 'Campaign') !== false)
            return 'campaign';
        if(strpos($name, 'Blocks') !== false)
            return 'block';
        if(strpos($name, 'Sites') !== false)
            return 'site';

        return null;
    }

    private function getRedisValue($name)
    {
        if(strpos($name, '_') === false){
            if(!$this->getOwner()->id)
                return null;

            $redisName = $this->getOwnerName().'_'.$name;
        }else{
            $redisName = $name;
        }

        if(isset(self::$_redisKeys[$redisName]))
            return RedisIO::get(self::$_redisKeys[$redisName].$this->getOwner()->id);
        else
            return null;
    }
}