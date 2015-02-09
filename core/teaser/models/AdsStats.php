<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 18.05.14
 * Time: 21:21
 */

namespace models;


use core\Session;
use MLF\layers\Logic;
use config\Config;

/**
 * @property dataSource\AdsStatsDataSource $nextLayer
 * @property \config\Config $config
 */
class AdsStats extends Logic {

    /**
     * @return \models\AdsStats
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function countByUserId($userId)
    {
        return $this->nextLayer->countByUserId($userId);
    }

    public function getAdsStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        return $this->nextLayer->getAdsStats($params);
    }

    public function getCampaignStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        return $this->nextLayer->getCampaignStats($params);
    }

    public function getRedisStats($params = []) {
        $type = 'ads';

        if(isset($params['type']) && $params['type']){
            $type = $params['type'];
            unset($params['type']);
        }

        $params['user_id'] = Session::getInstance()->getUserId();
        $stats = $this->nextLayer->getAdsAlltimeStats($params);

        switch ($type) {
            case 'ads':
                $method = 'getAdsRedisStats';
                break;
            case 'campaign':
                $method = 'getCampaignRedisStats';
                break;
            default:
                $method = 'getAdsRedisStats';
                break;
        }

        return $this->$method($stats);
    }

    public function getAdsRedisStats($stats) {
        $result = [];

        foreach($stats as $stat){
            $clicks = 0;
            $shows = 0;
            if (!\core\RedisIO::get("ads-shows:{$stat['id']}")){
                $ads = Ads::getInstance();
                $ads->initById($stat['id']);
                $shows = $ads->getShows();
                $clicks = $ads->getClicks();
            }
            $result[$stat['id']] = [
                'shows' => \core\RedisIO::get("ads-shows:{$stat['id']}") ? intval(\core\RedisIO::get("ads-shows:{$stat['id']}")) : (isset($shows) ? $shows : 0),
                'clicks' => \core\RedisIO::get("ads-clicks:{$stat['id']}") ? intval(\core\RedisIO::get("ads-clicks:{$stat['id']}")) : (isset($clicks) ? $clicks : 0),
                'costs' => floatval(\core\RedisIO::get("ads-expenses:{$stat['id']}")) ? floatval(\core\RedisIO::get("ads-expenses:{$stat['id']}")) : 0,
                'description' => isset($stat['description']) ? $stat['description'] : $stat['content']['caption'],
                'id' => intval($stat['id']),
                'campaignId' => $stat['campaign_id']
            ];
        }

        return $result;
    }

    public function getCampaignRedisStats($stats) {
        $result = [];

        foreach($stats as $stat){
            if (isset($result[$stat['campaign_id']])) {
                $result[$stat['campaign_id']]['shows'] += \core\RedisIO::get("ads-shows:{$stat['id']}");
                $result[$stat['campaign_id']]['clicks'] += \core\RedisIO::get("ads-clicks:{$stat['id']}");
                $result[$stat['campaign_id']]['costs'] += \core\RedisIO::get("ads-expenses:{$stat['id']}");
            } else {
                $result[$stat['campaign_id']] = [
                    'shows' => \core\RedisIO::get("ads-shows:{$stat['id']}"),
                    'clicks' => \core\RedisIO::get("ads-clicks:{$stat['id']}"),
                    'costs' => \core\RedisIO::get("ads-expenses:{$stat['id']}"),
                    'description' => $stat['camp_desc'],
                    'id' => intval($stat['id']),
                    'campaign_id' => $stat['campaign_id']
                ];
            }
        }

        return $result;
    }

    public function getBeforeCampaignStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        $result = [];

        $allTimeRedisStats = $this->getRedisStats($params);
//        $allTimePostgresStats = $this->nextLayer->getCampaignStats($params);
        $beforePostgresStats = $this->nextLayer->getBeforeCampaignStats($params);

        if(!$beforePostgresStats){
            foreach($allTimeRedisStats as $redis){
                if ($redis['shows'] > 0) {
                    $result[$redis['campaign_id']] = [
                        'description' => $redis['description'],
                        'clicks' => $redis['clicks'],
                        'shows' => $redis['shows'],
                        'costs' => $redis['costs'],
                        'id' => $redis['id'],
                        'userId' => $params['user_id']
                    ];
                }
            }
            return $result;
        }

        foreach($allTimeRedisStats as $redis){
            $found = false;
            foreach($beforePostgresStats as $postgres){
                if($postgres['id'] == $redis['campaign_id']){
                    if ($redis['shows'] > $postgres['shows']) {
                        $result[$redis['campaign_id']] = [
                            'description' => $postgres['description'],
                            'clicks' => $redis['clicks'] - $postgres['clicks'],
                            'shows' => $redis['shows'] - $postgres['shows'],
                            'costs' => $redis['costs'] - $postgres['costs'],
                            'id' => $redis['id'],
                            'userId' => $params['user_id']
                        ];
                    }
                    $found = true;
                    continue;
                }
            }
            if(!$found && $redis['shows'] > 0){
                $result[$redis['campaign_id']] = [
                    'description' => $redis['description'],
                    'clicks' => $redis['clicks'],
                    'shows' => $redis['shows'],
                    'costs' => $redis['costs'],
                    'userId' => Session::getInstance()->getUserId(),
                    'id' => $redis['id']
                ];
            }
        }

        return $result;
    }

    public function getBeforeAdsStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        $allTimeRedisStats = $this->getRedisStats($params);
//        $allTimePostgresStats = $this->nextLayer->getAdsStats($params);
        $beforePostgresStats = $this->nextLayer->getBeforeAdsStats($params);

        $result = [];

        if(!$beforePostgresStats){
            foreach($allTimeRedisStats as $redis){
                if ($redis['shows'] > 0) {
                    $result[$redis['id']] = [
                        'description' => $redis['description'],
                        'clicks' => $redis['clicks'],
                        'shows' => $redis['shows'],
                        'costs' => $redis['costs'],
                        'id' => $redis['id'],
                        'userId' => $params['user_id']
                    ];
                }
            }
            return $result;
        }

        foreach($allTimeRedisStats as $redis){
            $found = false;
            foreach($beforePostgresStats as $postgres){
                if($postgres['id'] == $redis['id']){
                    if ($redis['shows'] > $postgres['shows']) {
                        $result[$redis['id']] = [
                            'description' => $postgres['description'],
                            'clicks' => $redis['clicks'] - $postgres['clicks'],
                            'shows' => $redis['shows'] - $postgres['shows'],
                            'costs' => $redis['costs'] - $postgres['costs'],
                            'id' => $redis['id'],
                            'userId' => $params['user_id']
                        ];
                    }
                    $found = true;
                    continue;
                }
            }
            if(!$found){
                $result[$redis['id']] = [
                    'description' => $redis['description'],
                    'campaignId' => $redis['campaignId'],
                    'clicks' => $redis['clicks'],
                    'shows' => $redis['shows'],
                    'costs' => $redis['costs'],
                    'userId' => Session::getInstance()->getUserId(),
                    'id' => $redis['id']
                ];
            }
        }

        return $result;
    }

    public function getTodayAdsStats($params){
        $today = new \DateTime();
        $format = Config::getInstance()->getDateFormat();

        $params['user_id'] = \core\Session::getInstance()->getUserId();
        $params['startDate'] = $today->format($format);
        $params['endDate'] = $params['startDate'];

        $allTimeRedisStats = $this->getRedisStats($params);
        $allTimePostgresStats = $this->nextLayer->getBeforeAdsStats($params);

        if(!$allTimePostgresStats){
            return $allTimeRedisStats;
        }

        $result = [];
        foreach($allTimeRedisStats as $redis){
            $found = false;
            foreach($allTimePostgresStats as $postgres){
                if($postgres['id'] == $redis['id']){
                    $result[$redis['id']] = [
                        'description' => $postgres['description'],
                        'clicks' => $redis['clicks'] - $postgres['clicks'],
                        'shows' => $redis['shows'] - $postgres['shows'],
                        'costs' => $redis['costs'] - $postgres['costs'],
                        'id' => $redis['id'],
                        'campaignId' => $postgres['campaign_id'],
                        'userId' => $params['user_id']
                    ];
                    $found = true;
                    continue;
                }
            }
            if(!$found){
                $result[$redis['id']] = [
                    'description' => $redis['description'],
                    'campaignId' => $redis['campaignId'],
                    'clicks' => $redis['clicks'],
                    'shows' => $redis['shows'],
                    'costs' => $redis['costs'],
                    'userId' => Session::getInstance()->getUserId(),
                    'id' => $redis['id']
                ];
            }
        }

        return $result;
    }

    public function countStats($params = []) {
        switch($params['type']) {
            case 'ads':
                $method = 'countAds';
                break;
            case 'campaign':
                $method = 'countCampaigns';
                break;
            default:
                $method = 'countAds';
                break;
        }

        $args = [
            'user_id' => Session::getInstance()->getUserId(),
            'start_date' => $params['start_date'],
            'end_date' => $params['end_date'],
        ];

        return $this->nextLayer->$method($args);
    }
} 