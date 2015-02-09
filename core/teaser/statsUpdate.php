<?php
namespace core;
use models\Ads;
use models\AdsStats;
use models\Block;
use models\BlocksStats;

include dirname(__FILE__)."/../AutoLoader.php";
\AutoLoader::register(false);

//while(true){
    runProcess();
    //sleep(60*60*24);
//}

function runProcess(){
    $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');
    $sql = "SELECT id FROM users";
    $statement = $db->prepare($sql);
    $statement->execute();
    $users = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $blocksStats = BlocksStats::getInstance();
    $block = Block::getInstance();

    $adsStats = AdsStats::getInstance();
    $ads = Ads::getInstance();

    foreach($users as $user){
        Session::getInstance()->setUserId($user['id']);

        updateWebmasterStats($blocksStats, $block, $db);
        updateAdvertiserStats($adsStats, $ads, $db);
    }
}

function updateWebmasterStats($blocksStats, $block, $db) {
    $stats = $blocksStats->getTodayBlocksStats([]);

    if(!$stats){
        $allStats = $block->getList([]);
        foreach($allStats as $stat){
            $stats[] = [
                'id' => $stat['id'],
                'siteId' => isset($stat['siteId']) ? $stat['siteId'] : null,
                'clicks' => isset($stat['clicks']) ? $stat['clicks'] : 0,
                'shows' => isset($stat['shows']) ? $stat['shows'] : 0,
                'costs' => isset($stat['income']) ? $stat['income'] : (isset($stat['blockIncome']) ? $stat['blockIncome'] : 0)
            ];
        }
    }

    if($stats){
        foreach($stats as $stat){
            insertWebmasterStats($stat, $db);
        }
    }
}

function updateAdvertiserStats($adsStats, $ads, $db) {
    $stats = $adsStats->getTodayAdsStats([]);

    if(!$stats){
        $allStats = $ads->getList([]);
        foreach($allStats as $stat){
            $stats[] = [
                'id' => $stat['id'],
                'campaignId' => isset($stat['campaignId']) ? $stat['campaignId'] : null,
                'clicks' => isset($stat['clicks']) ? $stat['clicks'] : 0,
                'shows' => isset($stat['shows']) ? $stat['shows'] : 0,
                'costs' => isset($stat['costs']) ? $stat['costs'] : 0
            ];
        }
    }

    if($stats){
        foreach($stats as $stat){
            insertAdvertiserStats($stat, $db);
        }
    }
}

function insertWebmasterStats($data, \PDO $db){
    $sql = 'INSERT INTO webmaster_stats (user_id, "date", block_id, site_id, shows, clicks, "costs")
            VALUES (:user_id, \'now\', :block_id, :site_id, :shows, :clicks, :costs)';

    $statement = $db->prepare($sql);
    $statement->execute(array(
        ':user_id'=> Session::getInstance()->getUserId(),
        ':block_id'=> $data['id'],
        ':site_id'=> $data['siteId'],
        ':shows'=> $data['shows'],
        ':clicks'=> $data['clicks'],
        ':costs'=> $data['costs'],
    ));
}

function insertAdvertiserStats($data, \PDO $db){
    $sql = 'INSERT INTO advertiser_stats (user_id, "date", ads_id, campaign_id, shows, clicks, "costs")
            VALUES (:user_id, \'now\', :ads_id, :campaign_id, :shows, :clicks, :costs)';

    $statement = $db->prepare($sql);
    $statement->execute(array(
        ':user_id'=> Session::getInstance()->getUserId(),
        ':ads_id'=> strval($data['id']),
        ':campaign_id'=> $data['campaignId'],
        ':shows'=> $data['shows'],
        ':clicks'=> $data['clicks'],
        ':costs'=> $data['costs'],
    ));
}