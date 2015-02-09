<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 23.03.14
 * Time: 23:38
 */

namespace models\dataSource;


use core\MoneyTransaction;
use core\RedisIO;
use exceptions\DataLayerException;

class MoneyPayoutsDataSource extends DataSourceLayer{

    public function tableName()
    {
        return 'user_payout_request';
    }

    public function getUsersList($limit, $offset){
        $sql = '
                SELECT
                  "user_payout_request"."id",
                  "user_payout_request"."user_id",
                  "user_payout_request"."amount",
                  "user_payout_request"."date_time",
                  "user_payout_request"."status",
                  "user_payout_request"."actual_output",
                  "users"."full_name" AS "user_name",
                  "users"."email" AS "user_email"
                FROM
                  "user_payout_request"
                LEFT JOIN "users" ON("user_payout_request"."user_id" = "users"."id")
                WHERE "status" = 0
                LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new DataLayerException('cannot get user payout list' . var_export($statement->errorInfo() , 1));
        }
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTransactionLogTables(array $dates){
        $sql = 'SELECT "table_name" FROM "transaction_tables" WHERE "date" < \'now\'';
        $where = '';
        $params = [];
        if($dates){
            $where = ' AND "date" NOT IN (';
            foreach($dates as $key => $date){
                $params[] = $date;
                $where .= '?,';
            }
            $where[strlen($where) - 1] = ' ';
            $where .= ')';
        }
        $sql .= $where . ' ORDER BY "date" ASC';
        $statement = $this->pdoActual->prepare($sql);
        $result = $statement->execute($params);
        if(!$result){
            throw new DataLayerException('cannot getTransactionLogTables' . var_export($statement->errorInfo() , 1));
        }
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $result = [];
        foreach($data as $row){
            $result[] = $row['table_name'];
        }
        return $result;
    }

    public function getUserAlreadyCalculatedStats($userId){
        $sql = 'SELECT * FROM "user_balance_stats" WHERE "user_id" = :user_id';

        $statement = $this->pdoPersistent->prepare($sql);
        $result = $statement->execute(array(':user_id' => $userId));
        if(!$result){
            throw new DataLayerException('cannot getUserAlreadyCalculatedStatsStats' . var_export($statement->errorInfo() , 1));
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function prepareUserStats($table, $userId){
        $income = $this->getUserIncome($userId, $table);
        $outcome = $this->getUserOutcome($userId, $table);
        $balance = $income - $outcome;
        $tmp = explode('_', $table);
        return [
            'income' => $income,
            'outcome' => $outcome,
            'balance' => $balance,
            'blockedIpsCount' => 0,
            'geoStats' => 0,
            'clickTime' => 0,
            'ctr' => 0,
            'date' => "{$tmp[1]}-{$tmp[2]}-{$tmp[3]}"
        ];
    }

    public function getLastUserTransaction($userId, array $tables){
        $result = false;
        foreach($tables as  $table){
            $sql = 'SELECT * FROM "'.$table.'" WHERE "recipient_id" = :user_id OR "sender_id" = :user_id ORDER BY "timestamp" DESC LIMIT 1';
            $statement = $this->pdoActual->prepare($sql);
            $result = $statement->execute(array(':user_id' => $userId));
            $data = $statement->fetch(\PDO::FETCH_ASSOC);
            if($data){
                $result = $data;
            }
        }
        return $result;
    }

    public function saveUserStats($userId, $stats){
        $sql = 'INSERT INTO "user_balance_stats"
                    (income, outcome, balance, user_id, "date")
                VALUES (:income, :outcome, :balance, :user_id, :date)';
        $statement = $this->pdoPersistent->prepare($sql);
        $result = $statement->execute([
            ':user_id' => $userId,
            ':income' => $stats['income'],
            ':outcome' => $stats['outcome'],
            ':balance' => $stats['balance'],
            ':date' => $stats['date'],
        ]);
        if(!$result){
            throw new DataLayerException('cannot saveUserStats'. var_export($statement->errorInfo() , 1));
        }
        return $result;
    }

    public function applyPayoutRequest($requestId, $userId, $amount, $comment){
        if(RedisIO::get("money:{$userId}") < $amount){
            throw new DataLayerException('not enough money');
        }
        $sql = 'UPDATE "user_payout_request"
                    SET
                        "status" = 1,
                        "actual_output" =:amount ,
                        "comment" = :comment
                WHERE "id" = :id';
        $statement = $this->pdoPersistent->prepare($sql);
        $result = $statement->execute([
            ':id' => $requestId,
            ':comment' => $comment,
            ':amount' => $amount
        ]);
        if(!$result){
            throw new DataLayerException('cannot applyPayoutRequest'. var_export($statement->errorInfo() , 1));
        }
        $transaction = new MoneyTransaction();
        $transaction->setType(MoneyTransaction::WEBMASTER_TO_SYSTEM);
        $transaction->setAmount($amount);
        $transaction->setDescription('money payout');
        $transaction->setSender($userId);
        $transaction->execute();
        return $result;
    }

    private function getUserIncome($userId, $table){
        $sql = 'SELECT SUM(amount) as "sum" FROM '.$table.' WHERE "recipient_id" = :user_id';
        $statement = $this->pdoActual->prepare($sql);
        $result = $statement->execute(array(':user_id' => $userId));
        if(!$result){
            throw new DataLayerException('cannot getUserIncome' . var_export($statement->errorInfo() , 1));
        }

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if(!$data){
           return 0;
        }
        return $data['sum'];

    }

    private function getUserOutcome($userId, $table){
        $sql = 'SELECT SUM(amount) as "sum" FROM '.$table.' WHERE "sender_id" = :user_id';
        $statement = $this->pdoActual->prepare($sql);
        $result = $statement->execute(array(':user_id' => $userId));
        if(!$result){
            throw new DataLayerException('cannot getUserIncome' . var_export($statement->errorInfo() , 1));
        }

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if(!$data){
           return 0;
        }
        return $data['sum'];

    }

    public function getList($limit = 100, $offset = 0, $filters = '', $userId)
    {
        $sql = 'SELECT
                    "'.$this->tableName().'"."id",
                    "'.$this->tableName().'"."user_id" AS "userId",
                    "'.$this->tableName().'"."date_time" AS "timestamp",
                    "'.$this->tableName().'"."payout_date" AS "payoutDate",
                    date("'.$this->tableName().'"."date_time") AS "requestDate",
                    "time"("'.$this->tableName().'"."date_time") AS "requestTime",
                    "'.$this->tableName().'"."status",
                    "users"."email" AS "userEmail",
                    "users"."full_name" AS "userName"
                FROM "'.$this->tableName().'"
                LEFT JOIN "users" ON("'.$this->tableName().'"."user_id" = "users"."id")';

        if($userId || $filters)
            $sql .= ' WHERE ';

        if($filters)
            $sql .= $filters;

        if($userId){
            if($filters)
                $sql .= ' AND';

            $sql .= ' "'.$this->tableName().'"."user_id" = :user_id';
        }

        $sql .= ' ORDER BY "'.$this->tableName().'"."id" DESC LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        if($userId)
            $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('cannot get list of user payment requests: '.$this->parseError($statement->errorInfo()));
        }

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function findById($id)
    {
        $sql = 'SELECT
                    "'.$this->tableName().'"."id",
                    "'.$this->tableName().'"."user_id" AS "userId",
                    "'.$this->tableName().'"."date_time" AS "timestamp",
                    "'.$this->tableName().'"."payout_date" AS "payoutDate",
                    date("'.$this->tableName().'"."date_time") AS "requestDate",
                    "time"("'.$this->tableName().'"."date_time") AS "requestTime",
                    "'.$this->tableName().'"."status",
                    "users"."email" AS "userEmail",
                    "users"."full_name" AS "userName"
                FROM "'.$this->tableName().'"
                LEFT JOIN "users" ON("'.$this->tableName().'"."user_id" = "users"."id")
                WHERE "'.$this->tableName().'"."id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('cannot get user payment request: '.$this->parseError($statement->errorInfo()));
        }

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getLastPrepayment($userId, $status)
    {
        $sql = 'SELECT * FROM "'.$this->tableName().'"
                WHERE "user_id" = :user_id AND "status" = :status
                ORDER BY "id" DESC
                LIMIT 1';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':status', $status, \PDO::PARAM_INT);

        $result = $statement->execute();

        if(!$result){
            throw new \exceptions\DataLayerException('cannot get user payment request: '.$this->parseError($statement->errorInfo()));
        }

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function addPrepayment(array $data)
    {
        $sql = 'INSERT INTO "'.$this->tableName().'"
                    ("user_id", "amount", "date_time")
                VALUES
                    (:user_id, :amount, :date_time)';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':amount', $data['amount'], \PDO::PARAM_INT);
        $statement->bindParam(':date_time', $data['date_time'], \PDO::PARAM_STR);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('cannot get set payment request: '.$this->parseError($statement->errorInfo()));
        }

        return $this->pdoPersistent->lastInsertId('user_payout_request_id_seq');
    }

    public function update($data)
    {
        $fields = '';
        $params = [];
        $id = $data['id'];
        unset($data['id']);

        foreach($data as $key => $value){
            $fields.="{$key} = :{$key},";
            $params[":{$key}"] = $value;
        }
        $fields[strlen($fields) - 1] = ' ';
        $sql = 'UPDATE "'.$this->tableName().'" SET '.$fields.' WHERE "id" = :id';

        $params[':id'] = $id;
        $statement = $this->pdoPersistent->prepare($sql);
        $result = $statement->execute($params);

        if(!$result){
            throw new \exceptions\DataLayerException('cannot get set payment request: '.$this->parseError($statement->errorInfo()));
        }

        return $result;
    }

    public function getTransactionStats($recipientId = null, $startDate = null, $endDate = null, $senderId = null, $blockId = null, $adsId = null, $orderBy = '', $direction = ''){
        $params = [];
        $sql = 'SELECT * FROM transactions WHERE ';

        if($recipientId){
            $sql .= 'recipient_id=:recipient_id';
            $params[':recipient_id'] = $recipientId;
        }

        if($startDate){
            $sql .= ($params ? ' AND' : '') . ' timestamp > :start_date';
            $params[':start_date'] = $startDate;
        }

        if($endDate){
            $sql .= ($params ? ' AND' : '') . ' timestamp < :end_date';
            $params[':end_date'] = $endDate;
        }

        if($senderId){
            $sql .= ($params ? ' AND' : '') . ' sender_id = :sender_id';
            $params[':sender_id'] = $senderId;
        }

        if($blockId){
            $sql .= ($params ? ' AND' : '') . ' block_id = :block_id';
            $params[':block_id'] = $blockId;
        }
        if($adsId){
            $sql .= ($params ? ' AND' : '') . ' ads_id = :ads_id';
            $params[':ads_id'] = $adsId;
        }
        if(in_array($orderBy,
                array(
                    'block_id',
                    'ads_id',
                    'ip',
                    'sender_id',
                    'end_date',
                    'start_date',
                    'recipient_id',
                    'timestamp',
                    'amount',
                    'sender_balance',
                    'recipient_balance',
                    'referer'
                )
            ) && in_array($direction,['DESC', 'ASC', ''])){
            $sql .= " ORDER BY $orderBy $direction";
        }

        $statement = $this->pdoActual->prepare($sql);
        $result = $statement->execute($params);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        //var_export($params);die($sql);
        if(!$result || !$data){
            return [];
        }
        return $data;
    }

    public function getStrangeClicks($userId, $data, $orderBy){

    }
} 