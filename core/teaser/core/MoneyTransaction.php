<?php
namespace core;
use DataLayerException;
use PDO;
use core\PostgreSQL;
use core\Session;

/**
 * Created by t0m
 * Date: 31.12.13
 * Time: 15:43
 */

class MoneyTransaction
{

    const SYSTEM_TO_ADVERTISER = 1;
    const SYSTEM_TO_WEBMASTER = 2;
    const ADVERTISER_TO_SYSTEM = 3;
    const WEBMASTER_TO_SYSTEM = 4;
    const WEBMASTER_TO_ADVERTISER = 5;
    const ADVERTISER_TO_WEBMASTER = 6;
    const WEBMASTER_TO_OUT = 7;

    private $type;
    private $recipientId;
    private $senderId;
    private $amount;
    private $description;
    private $blockId;
    private $adsId;
    private $ip;
    private $referer;

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @param mixed $referer
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
    }

    public function setType($type)
    {
        if (!is_int($type) || $type > 7 || $type < 1) {
            throw new \exceptions\DataLayerException('invalid transaction type');
        }
        $this->type = $type;
    }

    public function setRecipient($id)
    {
        $this->recipientId = $id;
    }

    public function setSender($id)
    {
        $this->senderId = $id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setAdsId($id)
    {
        $this->adsId = $id;
    }

    public function setBlockId($id)
    {
        $this->blockId = $id;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function execute()
    {
        $this->validate();
        switch ($this->type) {
            case 1:
                $sender = 'system';
                $recipient = 'advertiser';
                break;
            case 2:
                $sender = 'system';
                $recipient = 'webmaster';
                break;
            case 3:
                $sender = 'advertiser';
                $recipient = 'system';
                break;
            case 4:
                $sender = 'webmaster';
                $recipient = 'system';
                break;
            case 5:
                $sender = 'webmaster';
                $recipient = 'advertiser';
                break;
            case 6:
                $sender = 'advertiser';
                $recipient = 'webmaster';
                break;
            case 7:
                $sender = 'webmaster';
                $recipient = null;
                break;
            default:
                $sender = '';
                $recipient = '';
                break;
        }

        $dbName = 'transactions';

        $pdo = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'actual_data');

        $sql = 'INSERT INTO "'.$dbName.'"
                   ("amount", "description", "block_id", "ads_id", "from", "to", "recipient_id", "sender_id", "timestamp", "sender_balance", "recipient_balance", "ip", "referer")
                VALUES
                   (:amount, :description, :block_id, :ads_id, :from, :to, :recipient_id, :sender_id, \'now\', :sender_balance, :recipient_balance, :ip, :referer );';

        $blockId = !empty($this->blockId) ? $this->blockId : null;
        $adsId = !empty($this->adsId) ? $this->adsId : null;
        $senderId = !empty($this->senderId) ? $this->senderId : null;
        $recipientId = !empty($this->recipientId) ? $this->recipientId : null;
        $ip = !empty($this->ip) ? $this->ip : null;
        $referer = !empty($this->referer) ? $this->referer : null;

        $statement = $pdo->prepare($sql);
        $statement->bindParam(':amount', $this->amount);
        $statement->bindParam(':description', $this->description, PDO::PARAM_STR);
        $statement->bindParam(':block_id', $blockId, PDO::PARAM_INT);
        $statement->bindParam(':ads_id', $adsId, PDO::PARAM_INT);
        $statement->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
        $statement->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
        $statement->bindParam(':from', $sender, PDO::PARAM_INT);
        $statement->bindParam(':to', $recipient, PDO::PARAM_INT);
        $statement->bindParam(':ip', $ip);
        $statement->bindParam(':referer', $referer);

        $fromKey = "money:{$senderId}";
        $toKey = "money:{$recipientId}";

        $senderMoneyLeft = $senderId ? RedisIO::incrByFloat($fromKey, -floatval($this->amount)) : RedisIO::incrByFloat('money:system', -floatval($this->amount));
        $recipientMoneyLeft = $recipientId ? RedisIO::incrByFloat($toKey, floatval($this->amount)) : RedisIO::incrByFloat('money:system', -floatval($this->amount));
        if($this->type === self::WEBMASTER_TO_OUT)
            $recipientMoneyLeft = null;

        $transactionResult[] = array(
            $senderId => $senderMoneyLeft,
            $recipientId => $recipientMoneyLeft
        );
        $statement->bindParam(':sender_balance', $senderMoneyLeft);
        $statement->bindParam(':recipient_balance', $recipientMoneyLeft);



        if($statement->execute()){
            return $transactionResult;
        }

        $errors = $statement->errorInfo();
        if(isset($errors[2]))
            $error = $errors[2];
        else
            $error = implode(', ', $errors);

        throw new \exceptions\DataLayerException('transaction failed: '.$error);
    }

    private function validate()
    {
        if (empty($this->type)) {
            throw new \exceptions\DataLayerException('type is not set');
        }
        if (!isset($this->amount)) {
            throw new \exceptions\DataLayerException('amount is not set');
        }
        if (empty($this->description)) {
            throw new \exceptions\DataLayerException('description is not set');
        }

        if (($this->type == 1 && $this->type == 2) && empty($this->recipientId)) {
            throw new \exceptions\DataLayerException('recipientId is not set');
        }
        if (($this->type == 3 && $this->type == 4) && empty($this->senderId)) {
            throw new \exceptions\DataLayerException('senderId is not set');
        }
        if (($this->type == 5 && $this->type == 6) && (empty($this->senderId) || empty($this->recipientId))) {
            throw new \exceptions\DataLayerException('senderId or recipientId is not set');
        }
    }

    private function prepareTable(){
        $now = new \DateTime();
        $dbName = 'transactions_' . $now->format('Y_m_d');
//        if(!($now->format('H') == 0 && $now->format('i') < 2)){
//            return $dbName;
//        }

        $sql = 'CREATE TABLE IF NOT EXISTS '.$dbName.' (
                    amount real NOT NULL,
                    description character varying(512),
                    ip character varying(15),
                    referer character varying(400),
                    block_id bigint,
                    ads_id bigint,
                    id bigint NOT NULL DEFAULT nextval(\'transactions_id_seq\'::regclass),
                    recipient_id bigint,
                    sender_id bigint,
                    "timestamp" timestamp without time zone,
                    "from" transaction_type,
                    "to" transaction_type,
                    sender_balance double precision,
                    recipient_balance double precision
                );';

        $pdo = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'actual_data');
        $statement = $pdo->prepare($sql);

        if($statement->execute()){
            return $dbName;
        }
//        return false;
    }
}