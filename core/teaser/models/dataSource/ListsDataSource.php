<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 09.07.14
 * Time: 12:24
 */

namespace models\dataSource;

use exceptions\DataLayerException;

class ListsDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'lists';
    }

    public function save($data)
    {
        $data['sites'] = $this->checkSitesId($data['sites']);

        $sql = 'INSERT INTO "lists" (
                    "name",
                    "type",
                    "sites",
                    "campaigns",
                    "user_id",
                    "description"
                ) VALUES (
                    :name,
                    :type,
                    :sites,
                    :campaigns,
                    :user_id,
                    :description
                )';

        $statement = $this->pdoPersistent->prepare($sql);

        $sites = $this->prepareArrayForInsert($data['sites']);
        $campaigns = $this->prepareArrayForInsert($data['campaigns']);
        $description = isset($data['description']) && $data['description'] ? $data['description'] : null;

        $statement->bindParam(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindParam(':type', $data['type'], \PDO::PARAM_INT);
        $statement->bindParam(':sites', $sites);
        $statement->bindParam(':campaigns', $campaigns);
        $statement->bindParam(':user_id', $data['userId'], \PDO::PARAM_INT);
        $statement->bindParam(':description', $description, \PDO::PARAM_STR);

        if(!$statement->execute()){
            throw new DataLayerException($this->parseError($statement->errorInfo()));
        }

        return $this->pdoPersistent->lastInsertId('lists_id_seq');
    }

    public function update($data)
    {
        $data['sites'] = $this->checkSitesId($data['sites']);

        $sql = 'UPDATE "lists" SET
                    "name" = :name,
                    "type" = :type,
                    "sites" = :sites,
                    "campaigns" = :campaigns,
                    "user_id" = :user_id,
                    "description" = :description
                    WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $sites = $this->prepareArrayForInsert($data['sites']);
        $campaigns = $this->prepareArrayForInsert($data['campaigns']);
        $description = isset($data['description']) && $data['description'] ? $data['description'] : null;

        $statement->bindParam(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindParam(':type', $data['type'], \PDO::PARAM_INT);
        $statement->bindParam(':sites', $sites);
        $statement->bindParam(':campaigns', $campaigns);
        $statement->bindParam(':user_id', $data['userId'], \PDO::PARAM_INT);
        $statement->bindParam(':description', $description, \PDO::PARAM_STR);
        $statement->bindParam(':id', $data['id'], \PDO::PARAM_INT);

        if(!$statement->execute()){
            throw new DataLayerException($this->parseError($statement->errorInfo()));
        }

        return true;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM "lists" WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result)
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        return $result;
    }

    public function getById($params)
    {
        $sql = 'SELECT
                    "id",
                    "name",
                    "type",
                    "sites",
                    "campaigns",
                    "user_id" AS "userId",
                    "description"
                 FROM "lists"
                 WHERE
                    "id" = :id AND "user_id" = :user_id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':id', $params['id'], \PDO::PARAM_INT);
        $statement->bindParam(':user_id', $params['userId'], \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result)
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        return $this->prepareRow($statement->fetch(\PDO::FETCH_ASSOC));
    }

    public function getAll($params)
    {
        $sql = 'SELECT
                    "id",
                    "name",
                    "type",
                    "sites",
                    "campaigns",
                    "user_id" AS "userId",
                    "description"
                 FROM "lists"
                 WHERE
                    "user_id" = :user_id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':user_id', $params['userId'], \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result)
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $key => $list){
            $data[$key] = $this->prepareRow($list);
        }

        return $data;
    }

    public function getAllCampaigns()
    {
        $sql = 'SELECT "campaigns" FROM "lists"';

        $statement = $this->pdoPersistent->prepare($sql);

        $result = $statement->execute();
        if(!$result)
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(!$data)
            return false;

        $result = [];
        foreach($data as $campaigns){
            $result = array_merge($result, $this->parseArrayFromDatabaseString($campaigns['campaigns']));
        }

        return $result;
    }

    private function prepareRow($row)
    {
        if(!$row)
            return [];

        $row['name'] = trim($row['name']);
        $row['description'] = $row['description'] ? trim($row['description']) : null;
        $row['sites'] = $this->parseArrayFromDatabaseString($row['sites']);
        $row['campaigns'] = $this->parseArrayFromDatabaseString($row['campaigns']);

        return $row;
    }

    private function checkSitesId(array $sitesIds)
    {
        $sitesInSql = implode(',', $sitesIds);

        $sql = 'SELECT "id" FROM "sites" WHERE "id" IN ('.$sitesInSql.')';

        $statement = $this->pdoPersistent->prepare($sql);
        $result = $statement->execute();

        if(!$result)
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(!$data)
            throw new DataLayerException('there is no such site');

        if(count($sitesIds) != count($data)){
            $sitesRealIds = [];
            foreach($data as $site){
                $sitesRealIds[] = $site['id'];
            }

            $notFoundedIds = [];

            foreach($sitesIds as $id){
                if(!in_array($id, $sitesRealIds))
                    $notFoundedIds[] =  $id;
            }

            return array_diff($sitesIds, $notFoundedIds);
        }

        return $sitesIds;
    }
}