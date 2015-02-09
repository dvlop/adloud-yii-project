<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 26.07.14
 * Time: 15:25
 * To change this template use File | Settings | File Templates.
 */

namespace core;

include "../AutoLoader.php";
\AutoLoader::register(false);

//while(true){
runProcess();
//sleep(60*60*24);
//}

function runProcess(){
    $db_persistent = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');
    $db_actual = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'actual_data');
    $sql = "SELECT
                id,
                referer
            FROM
                users
            WHERE
                referer IS NOT NULL";

    $statement = $db_persistent->prepare($sql);
    $statement->execute();
    $referals = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $today = new \DateTime();
    $today = $today->format('Y-m-d');

    foreach($referals as $ref){
        updateReferalStats($ref, $today, $db_actual, $db_persistent);
    }
}

function updateReferalStats($ref, $today, $db_actual, $db_persistent){
    $sum = getTodaySum($ref,$today,$db_actual);

    if(!$sum){
        return false;
    }

    $sum *= 0.05;

    $sum = round($sum,2);

    if($row = checkReferalRow($ref, $db_persistent)) {

        $sql = 'UPDATE
                    referal_stats
                SET
                    sum = :new_sum,
                    date = :today
                WHERE
                    referal_stats.referer_id = :referer_id
                AND referal_stats.referal_id = :referal_id
                AND referal_stats.status = FALSE';

        $newSum = $sum + $row;

        $statement = $db_persistent->prepare($sql);
        $statement->bindParam(':referer_id', $ref['referer'], \PDO::PARAM_STR);
        $statement->bindParam(':referal_id', $ref['id'], \PDO::PARAM_STR);
        $statement->bindParam(':today', $today, \PDO::PARAM_STR);
        $statement->bindParam(':new_sum', $newSum, \PDO::PARAM_STR);
        $statement->execute();

        return true;
    }

    $sql = 'INSERT INTO referal_stats (referer_id, referal_id, "date", "sum", "start_date")
            VALUES (:referer_id, :referal_id, :today, :amount, :today)';

    $statement = $db_persistent->prepare($sql);
    $statement->bindParam(':referer_id', $ref['referer'], \PDO::PARAM_STR);
    $statement->bindParam(':referal_id', $ref['id'], \PDO::PARAM_STR);
    $statement->bindParam(':today', $today, \PDO::PARAM_STR);
    $statement->bindParam(':amount', $sum, \PDO::PARAM_STR);
    $statement->execute();

    return true;
}

function checkReferalRow($ref,$db) {
    $sql = 'SELECT
                referal_stats.sum
            FROM
                referal_stats
            WHERE
                referal_stats.referer_id = :referer_id
            AND referal_stats.referal_id = :referal_id
            AND referal_stats.status = FALSE';

    $statement = $db->prepare($sql);
    $statement->bindParam(':referer_id', $ref['referer'], \PDO::PARAM_STR);
    $statement->bindParam(':referal_id', $ref['id'], \PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    return $result['sum'];
}

function getTodaySum($ref, $today, $db){
    $sql = "SELECT
                sum(transactions.amount)
            FROM
                transactions
            WHERE
                (transactions.recipient_id = :ref_id OR transactions.sender_id = :ref_id)
            AND transactions.from = 'advertiser'
            AND transactions.to = 'webmaster'
            AND transactions.timestamp > :today";

    $statement = $db->prepare($sql);
    $statement->bindParam(':ref_id', $ref['id'], \PDO::PARAM_STR);
    $statement->bindParam(':today', $today, \PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    return $result['sum'];
}