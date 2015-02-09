<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 29.07.14
 * Time: 13:46
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

    $today = new \DateTime();
    $today = $today->format('Y-m-d');

    $sql_actual = 'SELECT
                transactions.id,
                transactions.amount,
                transactions.timestamp,
                transactions.from,
                transactions.to,
                transactions.recipient_id,
                transactions.sender_id,
                transactions.description
            FROM
              transactions
            WHERE
                NOT (transactions.from = \'advertiser\' AND transactions.to = \'system\')
                AND transactions.timestamp > \'2014-07-28\'
            ORDER BY id DESC';

    $statement = $db_actual->prepare($sql_actual);
//    $statement->bindParam(':today', $today, \PDO::PARAM_STR);
    $statement->execute();
    $transactions = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $sql_persistent = 'SELECT
                            id
                       FROM
                          users';

    $statement = $db_persistent->prepare($sql_persistent);
    $statement->execute();
    $users = $statement->fetchAll(\PDO::FETCH_ASSOC);

    $webmaster_income = [];
    $advertiser_expense = [];
    $referal_payment = [];
    $system_in = [];
    $system_out = [];

    foreach($users as $user){
        foreach($transactions as $transaction){
            //count income for each webmaster
            if($user['id'] == $transaction['recipient_id'] && $transaction['from'] == 'advertiser' && $transaction['to'] == 'webmaster') {
                if(isset($webmaster_income[$user['id']])){
                    $webmaster_income[$user['id']]['amount'] += $transaction['amount'];
                } else {
                    $webmaster_income[$user['id']] = [
                        'user_id' => $user['id'],
                        'type' => 'webmaster_income',
                        'date' => $today,
                        'amount' => $transaction['amount']
                    ];
                }
            }
            //count expense for each advertiser
            if($user['id'] == $transaction['sender_id'] && $transaction['from'] == 'advertiser' && $transaction['to'] == 'webmaster') {
                if(isset($advertiser_expense[$user['id']])){
                    $advertiser_expense[$user['id']]['amount'] += $transaction['amount'];
                } else {
                    $advertiser_expense[$user['id']] = [
                        'user_id' => $user['id'],
                        'type' => 'advertiser_expenses',
                        'date' => $today,
                        'amount' => $transaction['amount']
                    ];
                }
            }
            //count referal payment for each user
            if($user['id'] == $transaction['recipient_id'] && $transaction['from'] == 'system' && $transaction['to'] == 'webmaster' && $transaction['description'] == 'referal payment') {
                if(isset($referal_payment[$user['id']])){
                    $referal_payment[$user['id']]['amount'] += $transaction['amount'];
                } else {
                    $referal_payment[$user['id']] = [
                        'user_id' => $user['id'],
                        'type' => 'referal_payment',
                        'date' => $today,
                        'amount' => $transaction['amount']
                    ];
                }
            }
            //count input money into system for each advertiser
            if($user['id'] == $transaction['recipient_id'] && $transaction['from'] == 'system' && $transaction['to'] == 'advertiser') {
                if(isset($system_in[$user['id']])){
                    $system_in[$user['id']]['amount'] += $transaction['amount'];
                } else {
                    $system_in[$user['id']] = [
                        'user_id' => $user['id'],
                        'type' => 'system_in',
                        'date' => $today,
                        'amount' => $transaction['amount']
                    ];
                }
            }
            //count output money from system for each user
            if($user['id'] == $transaction['recipient_id'] && $transaction['from'] == 'system' && $transaction['to'] == 'advertiser' && $transaction['amount'] < 0) {
                if(isset($system_out[$user['id']])){
                    $system_out[$user['id']]['amount'] += $transaction['amount'];
                } else {
                    $system_out[$user['id']] = [
                        'user_id' => $user['id'],
                        'type' => 'system_out',
                        'date' => $today,
                        'amount' => $transaction['amount']
                    ];
                }
            }
        }
    }

    $result = array_merge($webmaster_income, $advertiser_expense, $referal_payment, $system_in, $system_out);

    foreach($result as $res){
        addTransactionToStats($res, $db_persistent);
    }
}

function addTransactionToStats($transaction, $db_persistent){
    $sql = 'INSERT INTO transaction_stats (user_id, "date", comment, amount)
            VALUES (:user_id, :today, :comment, :amount)';

    $statement = $db_persistent->prepare($sql);
    $statement->bindParam(':user_id', $transaction['user_id'], \PDO::PARAM_STR);
    $statement->bindParam(':today', $transaction['date'], \PDO::PARAM_STR);
    $statement->bindParam(':comment', $transaction['type'], \PDO::PARAM_STR);
    $statement->bindParam(':amount', $transaction['amount'], \PDO::PARAM_STR);
    $statement->execute();

    return true;
}