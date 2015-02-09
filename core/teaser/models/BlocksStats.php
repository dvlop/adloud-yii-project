<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 17.06.14
 * Time: 19:18
 * @property \config\Config $config
 */

namespace models;

use config\Config;
use core\Session;
use MLF\layers\Logic;

/**
 * @property dataSource\BlocksStatsDataSource $nextLayer
 *
*/
class BlocksStats  extends Logic {

    /**
     * @return \models\BlocksStats
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getBlocksStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        return $this->nextLayer->getBlocksStats($params);
    }

    public function getSiteStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        return $this->nextLayer->getSiteStats($params);
    }

    public function getAdsInBlockStats(array $params){
        return $this->nextLayer->getAdsInBlockStats($params);
    }

    public function getRedisStats($params = []) {
        $type = 'blocks';

        if(isset($params['type']) && $params['type']){
            $type = $params['type'];
            unset($params['type']);
        }

        $params['user_id'] = Session::getInstance()->getUserId();
        $stats = $this->nextLayer->getBlocksAlltimeStats($params);

        switch ($type) {
            case 'blocks':
                $method = 'getBlocksRedisStats';
                break;
            case 'sites':
                $method = 'getSiteRedisStats';
                break;
            default:
                $method = 'getBlocksRedisStats';
                break;
        }

        return $this->$method($stats);
    }

    public function getBlocksRedisStats($stats) {
        $result = [];

        foreach($stats as $stat){
            $clicks = 0;
            $shows = 0;
            if (!\core\RedisIO::get("block-shows:{$stat['id']}")){
                $block = Block::getInstance();
                $block->initById($stat['id']);
                $shows = $block->shows;
                $clicks = $block->clicks;
            }
            $result[$stat['id']] = [
                'shows' => intval(\core\RedisIO::get("block-shows:{$stat['id']}")) ? intval(\core\RedisIO::get("block-shows:{$stat['id']}")) : (isset($shows) ? $shows : 0),
                'clicks' => intval(\core\RedisIO::get("block-clicks:{$stat['id']}")) ? intval(\core\RedisIO::get("block-clicks:{$stat['id']}")) : (isset($clicks) ? $clicks : 0),
                'costs' => floatval(\core\RedisIO::get("block-income:{$stat['id']}")) ? floatval(\core\RedisIO::get("block-income:{$stat['id']}")) : 0,
                'description' => isset($stat['description']) ? $stat['description'] : $stat['content']['caption'],
                'id' => intval($stat['id']),
                'siteId' => $stat['site_id']
            ];
        }

        return $result;
    }

    public function getSiteRedisStats($stats) {
        $result = [];

        foreach($stats as $stat){
            if (isset($result[$stat['site_id']])) {
                $result[$stat['site_id']]['shows'] += \core\RedisIO::get("block-shows:{$stat['id']}");
                $result[$stat['site_id']]['clicks'] += \core\RedisIO::get("block-clicks:{$stat['id']}");
                $result[$stat['site_id']]['costs'] += \core\RedisIO::get("block-income:{$stat['id']}");
            } else {
                $result[$stat['site_id']] = [
                    'shows' => \core\RedisIO::get("block-shows:{$stat['id']}"),
                    'clicks' => \core\RedisIO::get("block-clicks:{$stat['id']}"),
                    'costs' => \core\RedisIO::get("block-income:{$stat['id']}"),
                    'description' => $stat['site_desc'],
                    'id' => intval($stat['id']),
                    'siteId' => $stat['site_id']
                ];
            }
        }

        return $result;
    }

    public function getBeforeSiteStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        $result = [];

        $allTimeRedisStats = $this->getRedisStats($params);
//        $allTimePostgresStats = $this->nextLayer->getCampaignStats($params);
        $beforePostgresStats = $this->nextLayer->getBeforeSiteStats($params);

        if(!$beforePostgresStats){
            foreach($allTimeRedisStats as $redis){
                if ($redis['shows'] > 0) {
                    $result[$redis['siteId']] = [
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
                if($postgres['id'] == $redis['siteId']){
                    if ($redis['shows'] > $postgres['shows']) {
                        $result[$redis['siteId']] = [
                            'description' => $redis['description'],
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
                $result[$redis['siteId']] = [
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

    public function getBeforeBlocksStats($params = []){
        $params['user_id'] = Session::getInstance()->getUserId();

        $allTimeRedisStats = $this->getRedisStats($params);
//        $allTimePostgresStats = $this->nextLayer->getAdsStats($params);
        $beforePostgresStats = $this->nextLayer->getBeforeBlocksStats($params);

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
                    'siteId' => $redis['siteId'],
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

    public function getTodayBlocksStats($params){
        $today = new \DateTime();
        $format = Config::getInstance()->getDateFormat();

        $params['user_id'] = \core\Session::getInstance()->getUserId();
        $params['startDate'] = $today->format($format);
        $params['endDate'] = $params['startDate'];

        $allTimeRedisStats = $this->getRedisStats(['type' => 'blocks']);
        $allTimePostgresStats = $this->nextLayer->getBeforeBlocksStats($params);

        if(!$allTimePostgresStats){
            return $allTimeRedisStats;
        }
        
        $result = [];
        foreach($allTimeRedisStats as $redis){
            $found = false;
            foreach($allTimePostgresStats as $postgres){
                if($postgres['id'] == $redis['id']){
                    $result[$postgres['id']] = [
                        'description' => $postgres['description'],
                        'siteId' => $redis['siteId'],
                        'clicks' => $redis['clicks'] - $postgres['clicks'],
                        'shows' => $redis['shows'] - $postgres['shows'],
                        'costs' => $redis['costs'] - $postgres['costs'],
                        'userId' => Session::getInstance()->getUserId(),
                        'id' => $postgres['id']
                    ];
                    $found = true;
                    continue;
                }
                if(!$found){
                    $result[$redis['id']] = [
                        'description' => $redis['description'],
                        'siteId' => $redis['siteId'],
                        'clicks' => $redis['clicks'],
                        'shows' => $redis['shows'],
                        'costs' => $redis['costs'],
                        'userId' => Session::getInstance()->getUserId(),
                        'id' => $redis['id']
                    ];
                }
            }
        }

        return $result;
    }

    public function getTodaySiteStats($userId = null){
        $allTimeRedisStats = $this->getAllTimeStats(['type' => 'sites']);
        $allTimePostgresStats = $this->getSiteStats();
        
        if(!$allTimePostgresStats){
            return $allTimeRedisStats;
        }
        
        $result = [];
        foreach($allTimeRedisStats as $redis){
            $found = false;
            foreach($allTimePostgresStats as $postgres){
                if($postgres['id'] == $redis['id']){
                    $result[] = [
                        'description' => $postgres['description'],
                        'clicks' => $redis['clicks'] - $postgres['clicks'],
                        'shows' => $redis['shows'] - $postgres['shows'],
                        'costs' => $redis['costs'] - $postgres['costs'],
                        'id' => $redis['id'],
                        'userId' => $userId
                    ];
                    $found = true;
                    continue;
                }
                if(!$found){
                    $result[$redis['id']] = [
                        'description' => $redis['description'],
                        'clicks' => $redis['clicks'],
                        'shows' => $redis['shows'],
                        'costs' => $redis['costs'],
                        'userId' => $userId,
                        'id' => $redis['id']
                    ];
                }
            }
        }

        return $result;
    }
} 