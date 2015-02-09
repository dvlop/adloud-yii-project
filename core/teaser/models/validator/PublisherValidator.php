<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 09.02.14
 * Time: 19:19
 */

namespace models\validator;

/**
 * @property \models\Publisher $nextLayer
 */
class PublisherValidator extends ValidatorLayer {

    public function publishAds($adsId, $userId = null, $updateRegions = true, $isAdmin = false){
        if (!filter_var($adsId, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('adsId is not set');
        }

        return $this->nextLayer->publishAds($adsId, $userId, $updateRegions, $isAdmin);
    }

    public function publishCampaign($campaignID, $userId = null){
        if (!filter_var($campaignID, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('campaignID is not set');
        }
        return $this->nextLayer->publishCampaign($campaignID, $userId);
    }

    public function unPublishAds($adsId){
        if (!filter_var($adsId, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('adsId is not set');
        }
        return $this->nextLayer->unPublishAds($adsId);
    }

    public function unPublishCampaign($campaignID){
        if (!filter_var($campaignID, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('campaignID is not set');
        }
        return $this->nextLayer->unPublishCampaign($campaignID);
    }
} 