<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 13.08.14
 * Time: 11:15
 * To change this template use File | Settings | File Templates.
 */

namespace core;
use models\Ads;
use models\Block;

include dirname(__FILE__)."/../AutoLoader.php";
\AutoLoader::register(false);

cleanIrrelevantAdvertiserStats();
cleanIrrelevantWebmasterStats();

function cleanIrrelevantAdvertiserStats(){
    $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');

    $ads = getAds($db);
    $dumps = getAdsDumps($db);

    $diff = array_diff($dumps,$ads);

    foreach($diff as $d){
        deleteAdvertiserDump(intval($d), $db);
    }
}

function getAds($db){
    $sql = 'SELECT
                id
            FROM
                ads
            GROUP BY id';

    $statement = $db->prepare($sql);
    $statement->execute([]);

    $stats = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $ads = [];

    foreach($stats as $stat){
        $ads[] = $stat['id'];
    }

    return $ads;
}

function getAdsDumps($db){
    $sql = 'SELECT
                ads_id as "id"
            FROM
                advertiser_stats
            GROUP BY ads_id';

    $statement = $db->prepare($sql);
    $statement->execute([]);

    $stats = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $dumps = [];

    foreach($stats as $stat){
        $dumps[] = $stat['id'];
    }

    return $dumps;
}

function deleteAdvertiserDump($id, $db){
    $sql = 'DELETE FROM advertiser_stats WHERE ads_id = :id';

    $statement = $db->prepare($sql);
    $statement->execute([
        ':id' => $id
    ]);

    echo "DELETED ADS ID ".$id."\n";
}

function cleanIrrelevantWebmasterStats(){
    $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');

    $blocks = getBlocks($db);
    $dumps = getBlocksDumps($db);

    $diff = array_diff($dumps,$blocks);

    foreach($diff as $d){
        deleteWebmasterDump(intval($d), $db);
    }
}

function getBlocks($db){
    $sql = 'SELECT
                id
            FROM
                blocks
            GROUP BY id';

    $statement = $db->prepare($sql);
    $statement->execute([]);

    $stats = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $blocks = [];

    foreach($stats as $stat){
        $blocks[] = $stat['id'];
    }

    return $blocks;
}

function getBlocksDumps($db){
    $sql = 'SELECT
                block_id as "id"
            FROM
                webmaster_stats
            GROUP BY block_id';

    $statement = $db->prepare($sql);
    $statement->execute([]);

    $stats = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $dumps = [];

    foreach($stats as $stat){
        $dumps[] = $stat['id'];
    }

    return $dumps;
}

function deleteWebmasterDump($id, $db){
    $sql = 'DELETE FROM webmaster_stats WHERE block_id = :id';

    $statement = $db->prepare($sql);
    $statement->execute([
        ':id' => $id
    ]);

    echo "DELETED BLOCK ID ".$id."\n";
}