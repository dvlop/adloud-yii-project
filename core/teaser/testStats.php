<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 11.08.14
 * Time: 14:42
 * To change this template use File | Settings | File Templates.
 */

namespace core;
use models\Ads;
use models\Block;

include dirname(__FILE__)."/../AutoLoader.php";
\AutoLoader::register(false);

//dumpRandomStatsForUser(13,'2014-08-11');
//dumpRandomRedisForUser(13);

function dumpRandomStatsForUser($userId, $date){
    $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');
    $ads = Ads::getInstance();
    $blocks = Block::getInstance();

    Session::getInstance()->setUserId($userId);

//    updateTestAdvertiserStats($ads, $date, $db);
    updateTestWebmasterStats($blocks, $date, $db);
}

function dumpRandomRedisForUser($userId){
    $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');
    $ads = Ads::getInstance();
    $blocks = Block::getInstance();

    Session::getInstance()->setUserId($userId);

    $allStats = $ads->getList([]);
    foreach($allStats as $stat){
        $postgresStats = getAllStatsForAd($stat['id'], $db);

        $clicks = rand(5,25);
        $shows = rand(10000,50000);
        $postgresStats['shows'] += $shows;
        $postgresStats['clicks'] += $clicks;
        $postgresStats['costs'] += $clicks;

        RedisIO::set("ads-shows:{$stat['id']}", $postgresStats['shows']);
        RedisIO::set("ads-clicks:{$stat['id']}", $postgresStats['clicks']);
        RedisIO::set("ads-expenses:{$stat['id']}", $postgresStats['costs']);

        var_dump([$stat['id'] => $shows]);
    }

//    $blockStats = $blocks->getList([]);
//    foreach($blockStats as $stat){
//        $postgresStats = getAllStatsForBlock($stat['id'], $db);
//
//        $clicks = rand(5,25);
//        $shows = rand(10000,50000);
//        $postgresStats['shows'] += $shows;
//        $postgresStats['clicks'] += $clicks;
//        $postgresStats['costs'] += $clicks;
//
//        RedisIO::set("block-shows:{$stat['id']}", $postgresStats['shows']);
//        RedisIO::set("block-clicks:{$stat['id']}", $postgresStats['clicks']);
//        RedisIO::set("block-income:{$stat['id']}", $postgresStats['costs']);
//
//        var_dump([$stat['id'] => $shows]);
//    }
}

function getAllStatsForAd($adsId, $db){
    $sql = 'SELECT
                sum(shows) as "shows",
                sum(clicks) as "clicks",
                sum(costs) as "costs"
            FROM
                advertiser_stats
            WHERE
                ads_id = :id
            GROUP BY ads_id';

    $statement = $db->prepare($sql);
    $statement->execute(array(
        ':id'=> $adsId,
    ));

    return $statement->fetch(\PDO::FETCH_ASSOC);
}

function getAllStatsForBlock($blockId, $db){
    $sql = 'SELECT
                sum(shows) as "shows",
                sum(clicks) as "clicks",
                sum(costs) as "costs"
            FROM
                webmaster_stats
            WHERE
                block_id = :id
            GROUP BY block_id';

    $statement = $db->prepare($sql);
    $statement->execute(array(
        ':id'=> $blockId,
    ));

    return $statement->fetch(\PDO::FETCH_ASSOC);
}

function updateTestAdvertiserStats($ads, $date, $db) {
    $stats = [];

    if(!$stats){
        $allStats = $ads->getList([]);
        foreach($allStats as $stat){
            $clicks = rand(5,25);
            $stats[] = [
                'id' => $stat['id'],
                'campaignId' => isset($stat['campaignId']) ? $stat['campaignId'] : null,
                'clicks' => $clicks,
                'shows' => rand(10000,50000),
                'costs' => $clicks
            ];
        }
    }

    if($stats){
        foreach($stats as $stat){
            insertTestAdvertiserStats($stat, $date, $db);
        }
    }
}

function insertTestAdvertiserStats($data, $date, \PDO $db){
    $sql = 'INSERT INTO advertiser_stats (user_id, "date", ads_id, campaign_id, shows, clicks, "costs")
            VALUES (:user_id, :date, :ads_id, :campaign_id, :shows, :clicks, :costs)';

    $statement = $db->prepare($sql);
    $statement->execute(array(
        ':user_id'=> Session::getInstance()->getUserId(),
        ':ads_id'=> strval($data['id']),
        ':campaign_id'=> $data['campaignId'],
        ':shows'=> $data['shows'],
        ':clicks'=> $data['clicks'],
        ':costs'=> $data['costs'],
        ':date'=> $date
    ));
}

function updateTestWebmasterStats($blocks, $date, $db) {
    $stats = [];

    if(!$stats){
        $allStats = $blocks->getList([]);
        foreach($allStats as $stat){
            $clicks = rand(5,25);
            $stats[] = [
                'id' => $stat['id'],
                'siteId' => isset($stat['siteId']) ? $stat['siteId'] : null,
                'clicks' => $clicks,
                'shows' => rand(10000,50000),
                'costs' => $clicks
            ];
        }
    }

    if($stats){
        foreach($stats as $stat){
            insertTestWebmasterStats($stat, $date, $db);
        }
    }
}

function insertTestWebmasterStats($data, $date, \PDO $db){
    $sql = 'INSERT INTO webmaster_stats (user_id, "date", block_id, site_id, shows, clicks, "costs")
            VALUES (:user_id, :date, :block_id, :site_id, :shows, :clicks, :costs)';

    $statement = $db->prepare($sql);
    $statement->execute(array(
        ':user_id'=> Session::getInstance()->getUserId(),
        ':block_id'=> strval($data['id']),
        ':site_id'=> $data['siteId'],
        ':shows'=> $data['shows'],
        ':clicks'=> $data['clicks'],
        ':costs'=> $data['costs'],
        ':date'=> $date
    ));
}