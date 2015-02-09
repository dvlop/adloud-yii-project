<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 23.03.14
 * Time: 23:36
 */

namespace models;


use core\MoneyTransaction;
use core\Session;
use exceptions\DataLayerException;
use MLF\layers\Logic;

/**
 * Class MoneyPayouts
 * @property \models\dataSource\MoneyPayoutsDataSource $nextLayer
 * @package models
 */
class MoneyPayouts extends Logic{

    public $userId;
    public $timestamp;
    public $payoutDate;
    public $requestDate;
    public $requestTime;
    public $userEmail;
    public $userName;
    public $status;

    private $id;

    CONST STATUS_NOT_CONFIRMED = 0;
    CONST STATUS_CONFIRMED = 1;
    CONST STATUS_PAID = 2;
    CONST STATUS_REJECTED = 200;

    private static $_statuses = [
        self::STATUS_NOT_CONFIRMED => 'Не подтвержён',
        self::STATUS_CONFIRMED => 'Подтверждён (ждёт оплаты)',
        self::STATUS_PAID => 'Оплачен (закрыт)',
        self::STATUS_REJECTED => 'Отклонён',
    ];

    /**
     * @return \models\MoneyPayouts
     * @throws \exceptions\DataLayerException
     */
    public static function getInstance()
    {
        /*if(Session::getInstance()->getUserAccessLevel() != User::ACCESS_ADMIN){
            throw new DataLayerException('user is not admin');
        }*/
        return parent::getInstance();
    }

    public function getMoneyPayoutRequests($limit = 100, $offset = 0){
        return $this->nextLayer->getUsersList($limit, $offset);
    }

    public function getUserStats($userId){
        $alreadyCalculatedStatsData = $this->nextLayer->getUserAlreadyCalculatedStats($userId);
        $alreadyDates = [];
        $alreadyCalculatedStats = [];
        foreach($alreadyCalculatedStatsData as $stat){
            $alreadyDates[] = $stat['date'];
            $alreadyCalculatedStatsT['income'] = $stat['income'];
            $alreadyCalculatedStatsT['outcome'] = $stat['outcome'];
            $alreadyCalculatedStatsT['balance'] = $stat['balance'];
            $alreadyCalculatedStatsT['blockedIpsCount'] = $stat['blocked_ips_count'];
            $alreadyCalculatedStatsT['geoStats'] = $stat['geo_stats'];
            $alreadyCalculatedStatsT['clickTime'] = $stat['click_time'];
            $alreadyCalculatedStatsT['ctr'] = $stat['ctr'];
            $alreadyCalculatedStatsT['date'] = $stat['date'];
            $alreadyCalculatedStats[] = $alreadyCalculatedStatsT;
        }
        $tables = $this->nextLayer->getTransactionLogTables($alreadyDates);

        $newStats = [];
        if($tables){
            foreach($tables as $table){
                $statsData = $this->nextLayer->prepareUserStats($table, $userId);
                $newStats[] = $statsData;
                $this->nextLayer->saveUserStats($userId, $statsData);
            }
        }
        $stats = array_merge($alreadyCalculatedStats, $newStats);

        $lastTransaction = $this->nextLayer->getLastUserTransaction($userId, $tables);

        if(!$lastTransaction){
            return $stats;
        }

        $untrustedBalance = false;
        if($lastTransaction['recipient_id'] == $userId){
            $untrustedBalance = $lastTransaction['recipient_balance'];
        } elseif($lastTransaction['sender_id'] == $userId){
            $untrustedBalance = $lastTransaction['sender_balance'];
        }


        if($untrustedBalance !== false){
            $income = 0;
            $outcome = 0;
            foreach($stats as $stat){
                $income += $stat['income'];
                $outcome += $stat['outcome'];
            }
            $realBalance = $income - $outcome;
            $this->correctUserBalance($userId, $untrustedBalance, $realBalance);
        }

        return $stats;
}

    public function getTransactionStats($recipientId = null, $startDate = null, $endDate = null, $senderId = null, $blockId = null, $adsId = null, $siteId = null, $orderBy = '', $direction = ''){
    if($siteId){
        $site = Site::getInstance();
        $site->initById($siteId);
        $recipientId = $site->getUserId();
    }
    return $this->nextLayer->getTransactionStats($recipientId , $startDate , $endDate , $senderId , $blockId , $adsId, $orderBy, $direction);
}

    public function applyPayoutRequest($requestId, $userId, $amount, $comment){
        return $this->nextLayer->applyPayoutRequest($requestId, $userId, $amount, $comment);
    }

    private function correctUserBalance($userId, $untrustedBalance, $realBalance){
        $amount = $realBalance - $untrustedBalance;
        if($amount !== 0){
            $transaction = new MoneyTransaction();
            $transaction->setSender($userId);
            $transaction->setType(MoneyTransaction::WEBMASTER_TO_SYSTEM);
            $transaction->setDescription('correction transaction');
            $transaction->setAmount(-$amount);
            $transaction->execute();
        }

    }

    public function outWebmasterMoney($userId, $amount)
    {
        if($amount !== 0){
            $transaction = new MoneyTransaction();
            $transaction->setSender($userId);
            $transaction->setType(MoneyTransaction::WEBMASTER_TO_OUT);
            $transaction->setDescription('webmaster money out');
            $transaction->setAmount($amount);
            $transaction->execute();
        }
    }

    public function initById($id = null)
    {
        if($id === null && $this->id === null)
            throw new \LogicException('not correct ID');
        if($id === null)
            $id = $this->id;

        $this->id = $id;

        $data = $this->nextLayer->findById($id);
        if(!$data)
            throw new \LogicException('not fined record ID: '.$id);

        if(isset($data[0]))
            $data = $data[0];

        return $this->init($data);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getList($limit = 100, $offset = 0, array $filters = [], $userId = null)
    {
        if($filters){
            $filter = '';
            $table = $this->nextLayer->tableName();

            foreach($filters as $name=>$value){
                $filter .= '"'.$table.'"."'.$name.'" = '.$value.' AND ';
            }

            $filters = substr($filter, 0, strlen($filter)-5);
        }else{
            $filters = '';
        }

        return $this->nextLayer->getList($limit, $offset, $filters, $userId);
    }

    public function getLastNotConfirmedPrepayment($userId = null)
    {
        if($userId === null)
            $userId = \core\Session::getInstance()->getUserId();

        $result = $this->nextLayer->getLastPrepayment($userId, self::STATUS_NOT_CONFIRMED);

        if($result)
            return $result[0];
        else
            return $result;
    }

    public function getLastConfirmedPrepayment($userId = null)
    {
        if($userId === null)
            $userId = \core\Session::getInstance()->getUserId();

        $result = $this->nextLayer->getLastPrepayment($userId, self::STATUS_CONFIRMED);

        if($result)
            return $result[0];
        else
            return $result;
    }

    public function getLastPrepayment($userId = null)
    {
        if(!$result = $this->getLastNotConfirmedPrepayment($userId))
            $result = $this->getLastConfirmedPrepayment($userId);

        return $result;
    }

    public function addPrepayment($userId = null)
    {
        if($userId === null){
            $userId = $this->userId ? $this->userId : \core\Session::getInstance()->getUserId();
        }

        $user = User::getInstance();

        if(!$user->initById($userId))
            throw new \LogicException('not correct user ID');

        if($this->getLastPrepayment($userId))
            throw new \LogicException('the automatic payment is already installed');

        if(!$amount = $user->getMoneyBalance())
            throw new \LogicException('no money in the account');

        $data = [
            'user_id' => $userId,
            'amount' => $amount,
            'date_time' => date('Y-m-d h:i:s'),
        ];

        return $this->nextLayer->addPrepayment($data);
    }

    public function getStatus($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        if(isset(self::$_statuses[$statusId]))
            return self::$_statuses[$statusId];
        else
            return self::$_statuses[0];
    }

    public function setPaymentDate($date = '', $id = null)
    {
        if($id === null && $this->id === null)
            throw new \LogicException('not correct ID');
        if($id === null)
            $id = $this->id;

        $data = [
            'payout_date' => $date,
            'id' => $id,
            'status' => self::STATUS_CONFIRMED,
        ];
        return $this->nextLayer->update($data);
    }

    public function activate($id = null, $dateFormat = null)
    {
        if(!$dateFormat)
            $dateFormat = $this->config->getDateFormat();
        if($id === null && $this->id === null)
            throw new \LogicException('not correct ID');
        if($id === null)
            $id = $this->id;

        $data = [
            'payout_date' => date($dateFormat),
            'id' => $id,
            'status' => self::STATUS_CONFIRMED,
        ];
        return $this->nextLayer->update($data);
    }

    public function deActivate($id = null)
    {
        if($id === null && $this->id === null)
            throw new \LogicException('not correct ID');
        if($id === null)
            $id = $this->id;

        $data = [
            'payout_date' => null,
            'id' => $id,
            'status' => self::STATUS_REJECTED,
        ];
        return $this->nextLayer->update($data);
    }

    public function update($data = [], $id = null)
    {
        if($id === null && $this->id === null)
            throw new \LogicException('not correct ID');
        if($id === null)
            $id = $this->id;

        if(!$data){
            if($this->userId)
                $data['user_id'] = $this->userId;
            if($this->timestamp)
                $data['date_time'] = $this->timestamp;
            if($this->payoutDate)
                $data['payout_date'] = $this->payoutDate;
            if($this->status !== null)
                $data['status'] = $this->status;
        }

        if(!$data)
            throw new \LogicException('empty data');

        if(!isset($data['id']))
            $data['id'] = $id;

        return $this->nextLayer->update($data);
    }

    private function init($data = [])
    {
        if($data){
            $this->userId = $data['userId'];
            $this->timestamp = $data['timestamp'];
            $this->payoutDate = $data['payoutDate'];
            $this->requestDate = $data['requestDate'];
            $this->requestTime = $data['requestTime'];
            $this->status = $data['status'];
            $this->userEmail = $data['userEmail'];
            $this->userName = $data['userName'];
            return true;
        }
        else
            return false;
    }
} 