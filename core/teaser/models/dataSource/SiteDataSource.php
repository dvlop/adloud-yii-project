<?php
/**
 * Created by t0m
 * Date: 02.02.14
 * Time: 15:36
 */

namespace models\dataSource;


use core\Session;
use core\RedisIO;

class SiteDataSource extends DataSourceLayer{

    public function tableName()
    {
        return 'sites';
    }

    public function save(array $data){
        $sql = 'SELECT COUNT(*) FROM "sites" WHERE "url" = :url';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':url', $data['url'], \PDO::PARAM_STR);

        if(!$statement->execute()){
            throw new \exceptions\DataLayerException('error while updating site: '.$this->parseError($statement->errorInfo()));
        }



        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if(isset($result['count']) && $result['count'] > 0)
            throw new \exceptions\DataLayerException('site already exist');

        $sql = 'INSERT INTO "sites"
                    ("url", "mirror",
                    "user_id", "category",
                    "status",
                    "additional_category",
                    "banned_categories", "description",
                    "stats_url", "stats_login",
                    "stats_password", "contains_adult",
                    "allow_shock", "allow_adult",
                    "allow_sms", "allow_animation",
                    "create_date", "moderated")
                VALUES
                    (:url, :mirror,
                    :user_id, :category,
                    :status,
                    :additional_category,
                    :banned_categories, :description,
                    :stats_url, :stats_login,
                    :stats_password, :contains_adult,
                    :allow_shock, :allow_adult,
                    :allow_sms, :allow_animation,
                    \'now\', :moderated)';

        $statement = $this->pdoPersistent->prepare($sql);

        $bannedCategories = $data['bannedCategories'] ? $this->prepareArrayForInsert($data['bannedCategories'] ) : null;
        $moderated = isset($data['moderated']) ? $data['moderated'] : 0;
        $status = isset($data['status']) ? $data['status'] : 0;

        $statement->bindParam(':url', $data['url'], \PDO::PARAM_STR);
        $statement->bindParam(':user_id', $data['userId'], \PDO::PARAM_INT);
        $statement->bindParam(':mirror', $data['mirror'], \PDO::PARAM_STR);
        $statement->bindParam(':category', $data['category'], \PDO::PARAM_INT);
        $statement->bindParam(':additional_category', $data['additionalCategory'], \PDO::PARAM_INT);
        $statement->bindParam(':banned_categories', $bannedCategories);
        $statement->bindParam(':description', $data['description'], \PDO::PARAM_STR);
        $statement->bindParam(':stats_url', $data['statsUrl'], \PDO::PARAM_STR);
        $statement->bindParam(':stats_login', $data['statsLogin'], \PDO::PARAM_STR);
        $statement->bindParam(':stats_password', $data['statsPassword'], \PDO::PARAM_STR);
        $statement->bindParam(':contains_adult', $data['containsAdult'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_shock', $data['allowShock'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_adult', $data['allowAdult'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_sms', $data['allowSms'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_animation', $data['allowAnimation'], \PDO::PARAM_BOOL);
        $statement->bindParam(':moderated', $moderated, \PDO::PARAM_INT);
        $statement->bindParam(':status', $status, \PDO::PARAM_INT);
        //$statement->bindParam(':create_date', $data['date'], \PDO::PARAM_STR);


        if(!$statement->execute()){
            throw new \exceptions\DataLayerException('error while saving site: '.$this->parseError($statement->errorInfo()));
        }

        return $this->pdoPersistent->lastInsertId('sites_id_seq');
    }

    public function update(array $data)
    {
        $sql = 'UPDATE "sites" SET
                ("url", "mirror", "description", "category", "additional_category", "banned_categories", "stats_url", "stats_login", "stats_password", "contains_adult", "allow_shock", "allow_adult", "allow_sms", "allow_animation") =
                (:url, :mirror, :description, :category, :additional_category, :banned_categories, :stats_url, :stats_login, :stats_password, :contains_adult, :allow_shock, :allow_adult, :allow_sms, :allow_animation)
                WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $bannedCategories = $data['bannedCategories'] ? $this->prepareArrayForInsert($data['bannedCategories'] ) : null;

        $statement->bindParam(':id', $data['id'], \PDO::PARAM_INT);
        $statement->bindParam(':url', $data['url'], \PDO::PARAM_STR);
        $statement->bindParam(':mirror', $data['mirror'], \PDO::PARAM_STR);
        $statement->bindParam(':category', $data['category'], \PDO::PARAM_INT);
        $statement->bindParam(':additional_category', $data['additionalCategory'], \PDO::PARAM_INT);
        $statement->bindParam(':banned_categories', $bannedCategories);
        $statement->bindParam(':description', $data['description'], \PDO::PARAM_STR);
        $statement->bindParam(':stats_url', $data['statsUrl'], \PDO::PARAM_STR);
        $statement->bindParam(':stats_login', $data['statsLogin'], \PDO::PARAM_STR);
        $statement->bindParam(':stats_password', $data['statsPassword'], \PDO::PARAM_STR);
        $statement->bindParam(':contains_adult', $data['containsAdult'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_shock', $data['allowShock'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_adult', $data['allowAdult'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_sms', $data['allowSms'], \PDO::PARAM_BOOL);
        $statement->bindParam(':allow_animation', $data['allowAnimation'], \PDO::PARAM_BOOL);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while updating site: '.$this->parseError($statement->errorInfo()));

        return true;
    }

    public function initById($id){
        $sql = 'SELECT
                    "id",
                    "user_id" AS "userId",
                    "url",
                    "mirror",
                    "category",
                    "additional_category" AS "additionalCategory",
                    "banned_categories" AS "bannedCategories",
                    "description",
                    "stats_url" AS "statsUrl",
                    "stats_login" AS "statsLogin",
                    "stats_password" AS "statsPassword",
                    "contains_adult" AS "containsAdult",
                    "allow_shock" AS "allowShock",
                    "allow_adult" AS "allowAdult",
                    "allow_sms" AS "allowSms",
                    "allow_animation" AS "allowAnimation",
                    "moderated",
                    "status",
                    date("create_date") AS "date"
                FROM "sites"
                WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while initialising site: '.$this->parseError($statement->errorInfo()));

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if(!$row)
            return false;

        return $this->prepareRow($row, false);
    }

    public function getList(array $params){
        $sql = 'SELECT
                    "sites"."id",
                    "sites"."user_id" AS "userId",
                    "sites"."url",
                    "sites"."mirror",
                    "sites"."category",
                    "sites"."additional_category" AS "additionalCategory",
                    "sites"."banned_categories" AS "bannedCategories",
                    "category"."description" AS "categoryName",
                    "additionalCategory"."description" AS "additionalCategoryName",
                    "sites"."description",
                    "sites"."stats_url" AS "statsUrl",
                    "sites"."stats_login" AS "statsLogin",
                    "sites"."stats_password" AS "statsPassword",
                    "sites"."contains_adult" AS "containsAdult",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."moderated",
                    "sites"."status",
                    sum("blocks"."shows") AS "shows",
                    sum("blocks"."clicks") AS "clicks",
                    date("sites"."create_date") AS "date"
                FROM "sites"
                LEFT JOIN "categories" AS "category" ON "category"."id" = "sites"."category"
                LEFT JOIN "categories" AS "additionalCategory" ON "additionalCategory"."id" = "sites"."additional_category"
                LEFT JOIN "blocks" ON ("blocks"."site_id" = "sites"."id")
                WHERE "sites"."user_id" = :user_id';

        $limit = 100;
        $offset = 0;
        $startDate = null;
        $endDate = null;

        if(isset($params['limit']) && $params['limit'])
            $limit = $params['limit'];
        if(isset($params['offset']) && $params['offset'])
            $offset = $params['offset'];
        if(isset($params['startDate']) && $params['startDate'])
            $startDate = $params['startDate'];
        if(isset($params['endDate']) && $params['endDate'])
            $endDate = $params['endDate'];

        if($startDate !== null && $endDate !== null){
            $sql .= ' AND date("sites"."create_date") >= date(:start_date) AND  date("sites"."create_date") <= date(:end_date)';
        }

        $sql .= ' GROUP BY "sites"."id", "userId", "sites"."url", "sites"."mirror", "sites"."category", "additionalCategory", "bannedCategories", "categoryName", "additionalCategoryName",
                    "sites"."description", "statsUrl", "statsLogin", "statsPassword", "containsAdult", "allowShock", "allowAdult", "allowSms", "allowAnimation", "sites"."moderated", "date"
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['userId'], \PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);
        if($startDate !== null && $endDate !== null){
            $statement->bindParam(':start_date', $startDate, \PDO::PARAM_STR);
            $statement->bindParam(':end_date', $endDate, \PDO::PARAM_STR);
        }

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while reading sites: '.$this->parseError($statement->errorInfo()));

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(!$rows)
            return false;

        $result = array();

        foreach($rows as $row){
            $result[] = $this->prepareRow($row);
        }

        return $result;
    }

    public function getEmptyStatsList(array $params){
        $sql = 'SELECT
                    "sites"."id",
                    "sites"."user_id" AS "userId",
                    "sites"."url",
                    "sites"."mirror",
                    "sites"."category",
                    "sites"."additional_category" AS "additionalCategory",
                    "sites"."banned_categories" AS "bannedCategories",
                    "category"."description" AS "categoryName",
                    "additionalCategory"."description" AS "additionalCategoryName",
                    "sites"."description",
                    "sites"."stats_url" AS "statsUrl",
                    "sites"."stats_login" AS "statsLogin",
                    "sites"."stats_password" AS "statsPassword",
                    "sites"."contains_adult" AS "containsAdult",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."moderated",
                    "sites"."status"
                FROM "sites"
                LEFT JOIN "categories" AS "category" ON "category"."id" = "sites"."category"
                LEFT JOIN "categories" AS "additionalCategory" ON "additionalCategory"."id" = "sites"."additional_category"
                WHERE "sites"."user_id" = :user_id AND "sites".status ';
        $sql .= $params['status'] == 'archived' ? '= 500' : '<> 500';
        $sql .= ' ORDER BY "sites".id DESC
                LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':limit', $params['limit'], \PDO::PARAM_INT);
        $statement->bindParam(':offset', $params['offset'], \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while reading sites: '.$this->parseError($statement->errorInfo()));

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(!$rows)
            return false;

        $result = [];

        foreach($rows as $row){
            $result[] = $this->prepareEmptyRow($row);
        }

        return $result;
    }


    public function getStatsList(array $params){
        $sql = 'SELECT
                    "sites"."id",
                    "sites"."user_id" AS "userId",
                    "sites"."url",
                    "sites"."mirror",
                    "sites"."category",
                    "sites"."additional_category" AS "additionalCategory",
                    "sites"."banned_categories" AS "bannedCategories",
                    "category"."description" AS "categoryName",
                    "additionalCategory"."description" AS "additionalCategoryName",
                    "sites"."description",
                    "sites"."stats_url" AS "statsUrl",
                    "sites"."stats_login" AS "statsLogin",
                    "sites"."stats_password" AS "statsPassword",
                    "sites"."contains_adult" AS "containsAdult",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."moderated",
                    "sites"."status"
                FROM "sites"
                LEFT JOIN "categories" AS "category" ON "category"."id" = "sites"."category"
                LEFT JOIN "categories" AS "additionalCategory" ON "additionalCategory"."id" = "sites"."additional_category"
                WHERE "sites"."user_id" = :user_id AND ( "sites"."status" IS NULL OR "sites"."status" != :status_archived )
                ORDER BY "sites".id DESC
                LIMIT :limit OFFSET :offset';

        $archived = isset($params['status_archived']) ? $params['status_archived'] : 500;

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':status_archived', $archived, \PDO::PARAM_INT);
        $statement->bindParam(':limit', $params['limit'], \PDO::PARAM_INT);
        $statement->bindParam(':offset', $params['offset'], \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while reading sites: '.$this->parseError($statement->errorInfo()));

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(!$rows)
            return false;

        $result = [];

        foreach($rows as $row){
            $result[] = $this->prepareRow($row);
        }

        $beforePostgresStats = $this->getBeforeSitesStats($params);

        foreach($result as $key => $redis){
            foreach($beforePostgresStats as $postgres){
                if($postgres['id'] == $redis['id']){
                    $result[$key]['clicks'] = $redis['clicks'] - $postgres['clicks'];
                    $result[$key]['shows'] = $redis['shows'] - $postgres['shows'];
                    $result[$key]['income'] = $redis['income'] - $postgres['costs'];
                }
            }
        }

        return $result;
    }

    public function getSites(array $params){
        $allStats = $this->getEmptyStatsList($params);
        return $allStats;
    }

    public function getBeforeSitesStats(array $params){
        $sql = 'SELECT
                    webmaster_stats.site_id as "id",
                    sum(shows) as "shows",
                    sum(clicks) as "clicks",
                    sum(costs) as "costs",
                    "sites".description
                FROM
                    webmaster_stats
                     INNER JOIN "sites"
                        ON ("sites".id = "webmaster_stats"."site_id")
                WHERE
                    webmaster_stats.user_id = :user_id
                    AND date("date") < date(:start_date)
                GROUP BY "sites".description, webmaster_stats.site_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
    }

    public function delete($id){
        $sql = 'DELETE FROM "sites"
                WHERE "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while deleting sites: '.$this->parseError($statement->errorInfo()));

        return (bool)$statement->rowCount();
    }

    public function setStatus($id, $status)
    {
        $sql = 'UPDATE "sites" SET
                  "status" = :status
                WHERE
                  "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':status', $status, \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while change site status: ' . $this->parseError($statement->errorInfo()));

        return true;
    }

    public function setModerated($id, $status)
    {
        $sql = 'UPDATE "sites" SET
                  "moderated" = :moderated,
                  "status" = :status
                WHERE
                  "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':moderated', $status, \PDO::PARAM_INT);
        $statement->bindParam(':status', $status, \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while change site status: ' . $this->parseError($statement->errorInfo()));

        return true;
    }

    public function getStats($siteId){
        $blockDataSource = new BlockDataSource();
        $blocks = $blockDataSource->getList(array('siteId' => $siteId, 'userId' => Session::getInstance()->getUserId()));
        $income = 0;
        $clicks = 0;
        $shows = 0;
        foreach($blocks as $block){
            $income += $blockDataSource->getBlockIncome($block['id']);
            $clicks += $blockDataSource->getClicksCount($block['id']);
            $shows += $blockDataSource->getShowsCount($block['id']);
        }
        return ['clicks' => $clicks, 'shows' => $shows, 'income' => $income];
    }

    private function prepareRow($row, $withStats = true){
        if($withStats){
            $stats = $this->getStats($row['id']);

            $row['shows'] = isset($stats['shows']) && $stats['shows'] ? $stats['shows'] :  0;
            $row['clicks'] = isset($stats['clicks']) && $stats['clicks'] ? $stats['clicks'] :  0;
            $row['income'] = isset($stats['income']) && $stats['income'] ? $stats['income'] : 0;
        }
        $siteIdString = "site:{$row['id']}";
        $row['publish'] = (bool)RedisIO::get($siteIdString);
        $row['bannedCategories'] = $this->parseArrayFromDatabaseString($row['bannedCategories']);
        return $row;
    }

    private function prepareEmptyRow($row, $withStats = true){
        if($withStats){
            $row['shows'] = 0;
            $row['clicks'] = 0;
            $row['income'] = 0;
        }
        $siteIdString = "site:{$row['id']}";
        $row['publish'] = (bool)RedisIO::get($siteIdString);
        $row['bannedCategories'] = $this->parseArrayFromDatabaseString($row['bannedCategories']);
        return $row;
    }
}