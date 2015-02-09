<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 17.02.14
 * Time: 1:09
 */

namespace core;


use models\Campaign;
use models\Publisher;

class ClickHandler {

    public static function getBlockData($blockId){
        return unserialize(RedisIO::get("block:{$blockId}"));
    }
    public static function getAdsData($adsId){
        return unserialize(RedisIO::get("ads:{$adsId}"));
    }

    public static function handle($ads, $block){

        if(!$ads || !$block){
            return false;
        }

        $id = $ads['id'];
        $blockId = $block['id'];

        $campaignLimit = RedisIO::get("campaign-limit:{$ads['campaignId']}");
        $adsClicks = self::adsClickInc($id);
        self::blockClickInc($blockId);
        ViewerSession::getInstance()->addClickedAds($id);

        $ipWorker = new IpWorker();
        $ipWorker->incIpHitCount(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] :$_SERVER['REMOTE_ADDR']);

        $incomePercent = RedisIO::get('income-percent');

        RedisIO::incrByFloat("block-income:{$blockId}", $ads['clickPrice'] * (1 - $incomePercent));
        RedisIO::incrByFloat("campaign-expenses:{$ads['campaignId']}", $ads['clickPrice']);
        RedisIO::incrByFloat("ads-expenses:{$id}", $ads['clickPrice']);
        self::moneyTransaction($id, $blockId, $ads['userId'], $block['userId'], $ads['clickPrice'], $incomePercent);

        $money = RedisIO::get("money:{$ads['userId']}");

        $publisher = Publisher::getInstance();

        $campaignExpenses = RedisIO::get("campaign-expenses:{$ads['campaignId']}");

        if(($ads['maxClicks'] !== null && $adsClicks >= $ads['maxClicks'])
            || ($campaignLimit && ($campaignExpenses + $ads['clickPrice'])  >= $campaignLimit)){

            $publisher->unPublishAds($ads['id']);
        }

        //todo: do something better

        if($money < $ads['clickPrice']){
            $campaign = Campaign::getInstance();
            $list = $campaign->getList(null, null, $ads['userId']);
            foreach($list as $camp){
                $publisher->unPublishCampaign($camp['id']);
            }
        }

        return true;
    }

    private static function adsClickInc($id){
        $key = "ads-clicks:{$id}";
        return RedisIO::incr($key);
    }

    private static function blockClickInc($id){
        $key = "block-clicks:{$id}";
        return RedisIO::incr($key);
    }

    private static function moneyTransaction($adsId, $blockId, $sender, $recipient, $clickPrice, $incomePercent){
        $toWebmaster = new MoneyTransaction();
        $toWebmaster->setAdsId($adsId);
        $toWebmaster->setType(MoneyTransaction::ADVERTISER_TO_WEBMASTER);
        $toWebmaster->setBlockId($blockId);
        $toWebmaster->setSender($sender);
        $toWebmaster->setRecipient($recipient);
        $toWebmaster->setAmount($clickPrice * (1 - $incomePercent));
        $toWebmaster->setIp(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] :$_SERVER['REMOTE_ADDR']);
        $toWebmaster->setReferer($_SERVER['HTTP_REFERER']);
        $toWebmaster->setDescription('ads click');

        $toSystem = new MoneyTransaction();
        $toSystem->setAdsId($adsId);
        $toSystem->setSender($sender);
        $toSystem->setType(MoneyTransaction::ADVERTISER_TO_SYSTEM);
        $toSystem->setAmount($clickPrice * $incomePercent);
        $toSystem->setDescription('ads click');

        $transactionManager = new TransactionManager();
        $transactionManager->register($toWebmaster);
        $transactionManager->register($toSystem);
        $transactionManager->execute();
    }
} 