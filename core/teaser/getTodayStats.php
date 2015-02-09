<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 21.08.14
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */

namespace core;

use models\AdsStats;
use models\BlocksStats;

include dirname(__FILE__)."/../AutoLoader.php";
\AutoLoader::register(false);

$adsModel = AdsStats::getInstance();
$blocksModel = BlocksStats::getInstance();

$stats = [];

$type = $argv[1];
$userId = $argv[2];
$groupId = $argv[3];

Session::getInstance()->setUserId($userId);

if(!$groupId){
    if($type == 'ads'){
        $stats = $adsModel->getTodayAdsStats([]);
    } else {
        $stats = $blocksModel->getTodayBlocksStats([]);
    }
} else {
    if($type == 'ads'){
        $stats = $adsModel->getTodayAdsStats([]);
    } else {
        $stats = $blocksModel->getTodayBlocksStats([]);
    }

    foreach($stats as $key => $stat){
        if($stat['campaignId'] != $groupId){
            unset($stats[$key]);
        }
    }
}

echo PHP_EOL;
echo PHP_EOL;
echo 'User ID: '.$userId.PHP_EOL;
echo 'Type: '.$type.PHP_EOL;
echo $type == 'ads' ? 'Campaign ID: '.($groupId ? $groupId : 'ALL').PHP_EOL : 'Site ID: '.($groupId ? $groupId : 'ALL').PHP_EOL;
echo PHP_EOL;

printf('%7s| %10s| %10s|'.PHP_EOL, "ID", "Clicks", "Shows");

foreach($stats as $stat){
    printf('%7s %10s %10s'.PHP_EOL, $stat['id'], $stat['clicks'], $stat['shows']);
}