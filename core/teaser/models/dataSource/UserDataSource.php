<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class UserDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'users';
    }

    public function save($data = array())
    {
        $sql = 'INSERT INTO
                    "users"
                    ("email", "password", "full_name", "register_date", "help_desc_password", "webmoney_wmz", "webmoney_wmr", "yandex_id", "qiwi_id", "isq", "skype", "invite", "access_level", "referer", "lang")
                VALUES
                    (:email, :password, :full_name, \'now\', :help_desc_password, :webmoney_wmz, :webmoney_wmr, :yandex_id, :qiwi_id, :isq, :skype, :invite, :access_level, :referer, :lang)';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $statement->bindParam(':password', $data['password'], PDO::PARAM_STR);
        $statement->bindParam(':full_name', $data['fullName'], PDO::PARAM_STR);
        $statement->bindParam(':help_desc_password', $data['helpDescPassword'], PDO::PARAM_STR);
        $statement->bindParam(':webmoney_wmz', $data['webmoney_wmz'], PDO::PARAM_STR);
        $statement->bindParam(':webmoney_wmr', $data['webmoney_wmr'], PDO::PARAM_STR);
        $statement->bindParam(':yandex_id', $data['yandex_id'], PDO::PARAM_STR);
        $statement->bindParam(':qiwi_id', $data['qiwi_id'], PDO::PARAM_STR);
        $statement->bindParam(':isq', $data['isq'], PDO::PARAM_STR);
        $statement->bindParam(':skype', $data['skype'], PDO::PARAM_STR);
        $statement->bindParam(':invite', $data['invite'], PDO::PARAM_STR);
        $statement->bindParam(':access_level', $data['access_level'], PDO::PARAM_INT);
        $statement->bindParam(':referer', $data['referer'], PDO::PARAM_INT);
        $statement->bindParam(':lang', $data['lang'], PDO::PARAM_STR);

        $result = $statement->execute();
        if (!$result)
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));

        return $this->pdoPersistent->lastInsertId('users_id_seq');
    }

    public function addMoneyRequest($amount, $userId){
        $sql = 'INSERT INTO
                    "user_add_money_request"
                    ("amount", "user_id")
                VALUES
                    (:amount, :user_id);';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':amount', $amount);
        $statement->bindParam(':user_id', $userId, PDO::PARAM_STR);

        $result = $statement->execute();
        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        return $this->pdoPersistent->lastInsertId('user_add_money_request_id_seq');
    }

    public function addMoneyBalance($amount, $description, $id)
    {
        $income = new MoneyTransaction();
        $income->setType(MoneyTransaction::SYSTEM_TO_ADVERTISER);
        $income->setAmount($amount);
        $income->setRecipient($id);
        $income->setDescription($description);

        $manager = new TransactionManager();
        $manager->register($income);

        return $manager->execute();
    }

    public function getById($id)
    {
        $sql = 'SELECT * FROM "users" WHERE "id" = :id';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $statement->execute();
        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getReferals($userId)
    {
        $sql = 'SELECT
                    users.register_date as "date",
                    sum(referal_stats.sum),
                    users.full_name as "name"
                FROM referal_stats
                INNER JOIN users ON (referal_stats.referal_id = users.id)
                WHERE referal_stats.referer_id = :user_id
                AND referal_stats.status = TRUE
                GROUP BY users.register_date, users.full_name';

        return $this->runQuery($sql, $this->pdoPersistent, [
           'user_id' => $userId
        ]);
    }

    public function getTransactionLog($params){
        $sql = 'SELECT
                    id,
                    "date",
                    comment,
                    amount
                FROM
                    transaction_stats
                WHERE
                    transaction_stats.user_id = :user_id
                ORDER BY id DESC';

        return $this->runQuery($sql, $this->pdoPersistent, [
            ':user_id' => $params['user_id']
        ]);
    }

    public function getByEmail($email)
    {
        $sql = 'SELECT * FROM "users" WHERE "email" = :email';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':email', $email, PDO::PARAM_INT);
        $result = $statement->execute();
        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getMoney($id)
    {
        $result = RedisIO::get("money:{$id}");
        return $result ? $result : 0;
    }

    public function update(array $data, $userId){
        $fields = '';
        $params = [];
        foreach($data as $key => $value){
            $fields.="{$key} = :{$key},";
            $params[":{$key}"] = $value;
        }
        $fields[strlen($fields) - 1] = ' ';
        $sql = 'UPDATE "users" SET '.$fields.' WHERE "id" = :id';

        $params[':id'] = $userId;
        $statement = $this->pdoPersistent->prepare($sql);

        $result = $statement->execute($params);

        return $result;
    }

    public function requestMoneyPayout($amount, $userId){
        $sql = 'INSERT INTO "user_payout_request"
                        ("user_id", "amount", "date_time")
                VALUES
                        (:user_id, :amount, \'now\')';
        $statement = $this->pdoPersistent->prepare($sql);
        $result = $statement->execute(array(':user_id' => $userId, ':amount' => $amount));

        if(!$result){
            throw new DataLayerException('cannot insert money payout request' . $this->parseError($statement->errorInfo()));
        }

        return $this->pdoPersistent->lastInsertId('user_payout_request_id_seq');
    }

    public function getMoneyPayoutRequestList($userId, $limit, $offset){

        $sql = 'SELECT
                    id,
                    user_id as "userId",
                    "comment",
                    actual_output,
                    status,
                    date_time as "dateTime",
                    date("date_time") as "date",
                    "time"("date_time") as "time",
                    amount
                FROM "user_payout_request"
                WHERE "user_id" = :user_id
                LIMIT :limit
                OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $result = $statement->execute();
        if (!$result) {
            throw new \exceptions\DataLayerException('cannot get user money payout request: '.$this->parseError($statement->errorInfo()));
        }

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function setRole($roleId, $userId)
    {
        $sql = 'UPDATE "users" SET "access_level" = :access_level WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':access_level', $roleId, PDO::PARAM_INT);
        $statement->bindParam(':id', $userId, PDO::PARAM_INT);

        $result = $statement->execute();
        if (!$result)
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));

        return $result;
    }

    public function setPassword($password, $hdPass, $userId)
    {
        $sql = 'UPDATE "users" SET ("password", "help_desc_password") = (:password, :help_desc_password) WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':password', $password, PDO::PARAM_STR);
        $statement->bindParam(':help_desc_password', $hdPass, PDO::PARAM_STR);
        $statement->bindParam(':id', $userId, PDO::PARAM_INT);

        $result = $statement->execute();
        if (!$result)
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));

        return $result;
    }

    public function activateUser($userId, $invite, $roleId)
    {
        $sql = 'UPDATE
                  "users"
                SET
                  "access_level" = :access_level, "invite" = :invite
                WHERE
                  "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':access_level', $roleId, PDO::PARAM_INT);
        $statement->bindParam(':invite', $invite, PDO::PARAM_STR);
        $statement->bindParam(':id', $userId, PDO::PARAM_INT);

        $result = $statement->execute();
        if (!$result)
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));

        return $result;
    }

    public function getStats($arParams = [])
    {
        // Получаем информацию о пользователях
        $sql = 'SELECT
                    users.id,
                    users.email,
                    users.full_name,
                    users.access_level,
                    users.invite,
                    users.lang,
                    coalesce(SUM(WS.shows), 0) as shows,
                    coalesce(SUM(WS.clicks), 0) as clicks,
                    ( CASE
                        WHEN coalesce(SUM(ws.shows), 0) > 0
                            THEN coalesce(SUM(ws.clicks), 0)/coalesce(SUM(WS.shows), 1)*100
                        ELSE 0
                    END ) AS ctr,
                    coalesce(SUM(WS.costs), 0) as costs,
                    ( SELECT COUNT (sites.id) FROM "sites" WHERE (users.id = sites.user_id) ) as sites_count
                FROM "users"
                LEFT JOIN "webmaster_stats" WS ON (users.id = WS.user_id)
                GROUP BY users.id';

		$arSort = [
			'id' => 'users.id',
			'email' => 'users.email',
			'sitesCount' => 'sites_count',
			'shows' => 'shows',
			'clicks' => 'clicks',
			'ctr' => 'ctr',
			'costs' => 'costs',
		];

		if($arParams['sortBy'] && isset($arSort[$arParams['sortBy']]))
		{
			$sortBy = $arSort[$arParams['sortBy']];			
		}else{
            $sortBy = 'id';
        }
		
		$sortOrder = $arParams['sortOrder'] ? $arParams['sortOrder'] : 'ASC';

		$sql.=" ORDER BY $sortBy $sortOrder";

        $statement = $this->pdoPersistent->prepare($sql);

        $result = $statement->execute();

        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            if($arParams['status'] == 'normal'){
                if($row['access_level'] != 300) {
                    $row['ctr'] = round($row['ctr'], 1);
                    $arResult[$row['id']] = json_decode(json_encode($row), FALSE);;
                    $arStat[$row['id']] = ['shows' => 0, 'clicks' => 0, 'costs' => 0];
                }
            } elseif($arParams['status'] == 'banned') {
                if($row['access_level'] == 300) {
                    $row['ctr'] = round($row['ctr'], 1);
                    $arResult[$row['id']] = json_decode(json_encode($row), FALSE);;
                    $arStat[$row['id']] = ['shows' => 0, 'clicks' => 0, 'costs' => 0];
                }
            }else{
                $row['ctr'] = round($row['ctr'], 1);
                $arResult[$row['id']] = json_decode(json_encode($row), FALSE);;
                $arStat[$row['id']] = ['shows' => 0, 'clicks' => 0, 'costs' => 0];
            }
        }

        if(isset($arResult) && is_array($arResult))
        {
            // Получаем ид блоков
            $arUserIds = array_keys($arResult);
            $arStat = isset($arStat) && $arStat ? $arStat : [];

            $sql = "SELECT
                blocks.id, sites.user_id
                FROM blocks
                JOIN sites ON (sites.id = blocks.site_id)
                WHERE blocks.site_id IN
                    (
                    SELECT
                    sites.id
                    FROM sites
                    WHERE sites.user_id IN (".implode(',', $arUserIds).")
                    )
                ";

            $statement = $this->pdoPersistent->prepare($sql);
            $result = $statement->execute();
            if (!$result) {
                throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
            }

            while($row = $statement->fetch(PDO::FETCH_ASSOC))
            {
                $blockId = $row['id'];
                $userId = $row['user_id'];

                // Получаем статистику в реальном времени
                $arStat[$userId]['shows'] += RedisIO::get("block-shows:{$blockId}");
                $arStat[$userId]['clicks'] += RedisIO::get("block-clicks:{$blockId}");
                $arStat[$userId]['costs'] += RedisIO::get("block-income:{$blockId}");
            }

            // Обновляем статистику
            foreach($arResult as $userId => $userInfo)
            {
                $userStat = $arStat[$userId];

                $ctr = $userStat['shows'] ? round($userStat['clicks'] / $userStat['shows'] * 100, 1) : 0;

                $userInfo->clicks = $userStat['clicks'];
                $userInfo->shows = $userStat['shows'];
                $userInfo->ctr = $ctr;
                $userInfo->costs = $userStat['costs'];

                $arResult[$userId ] = $userInfo;
            }
            
            if(isset($arParams['activity']) && $arParams['activity'] == 'active'){
                foreach($arResult as $userId => $userInfo)
                {
                    if($userInfo->shows == 0) {
                        unset($arResult[$userId]);
                    }
                }
            } elseif(isset($arParams['activity']) && $arParams['activity'] == 'passive') {
                foreach($arResult as $userId => $userInfo)
                {
                    if($userInfo->shows > 0) {
                        unset($arResult[$userId]);
                    }
                }
            }

            return $arResult;

        }


    }

}