<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class UserBaseInfoDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'user_base_info';
    }

    public function save(array $data)
    {
        $sql = 'INSERT INTO
                    "user_base_info"
                    ("site_url", "desired_profit", "stat_link", "stat_login", "stat_password", "description", "user_id")
                VALUES
                    (:site_url, :desired_profit, :stat_link, :stat_login, :stat_password, :description, :user_id);';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':site_url', $data['siteUrl'], PDO::PARAM_STR);
        $statement->bindParam(':desired_profit', $data['desiredProfit'], PDO::PARAM_INT);
        $statement->bindParam(':stat_link', $data['statLink'], PDO::PARAM_STR);
        $statement->bindParam(':stat_login', $data['statLogin'], PDO::PARAM_STR);
        $statement->bindParam(':stat_password', $data['statPassword'], PDO::PARAM_STR);
        $statement->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $statement->bindParam(':user_id', $data['userId'], PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result)
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));

        return $this->pdoPersistent->lastInsertId('user_base_info_id_sec');
    }

    public function update(array $data, $id)
    {
        $fields = '';
        $params = [];
        foreach($data as $key => $value){
            $fields.="{$key} = :{$key},";
            $params[":{$key}"] = $value;
        }
        $fields[strlen($fields) - 1] = ' ';
        $sql = 'UPDATE "user_base_info" SET '.$fields.' WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $params[':id'] = $id;
        $result = $statement->execute($params);
        if(!$result)
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));

        return $result;
    }
}