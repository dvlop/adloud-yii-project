<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 23.03.14
 * Time: 14:43
 */

namespace models\dataSource;


use core\ImageWorker;
use core\RedisIO;
use exceptions\DataLayerException;

class ModerationDataSource extends DataSourceLayer {

    public function getSitesList($status, $limit, $offset){
        $sql = 'SELECT
                    "sites"."id",
                    "sites"."user_id" AS "userId",
                    "sites"."url",
                    "sites"."mirror",
                    "sites"."category",
                    "sites"."additional_category" AS "additionalCategory",
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
                    "sites"."status"
                FROM "sites"
                LEFT JOIN "categories" AS "category" ON "category"."id" = "sites"."category"
                LEFT JOIN "categories" AS "additionalCategory" ON "additionalCategory"."id" = "sites"."additional_category"';

        if($status !== null)
            $sql .= ' WHERE "status" = :status';

        $sql .= 'ORDER BY "id" DESC
                LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);
        if($status !== null)
            $statement->bindParam(':status', $status, \PDO::PARAM_INT);

        $result = $statement->execute();

        if(!$result){
            throw new DataLayerException(var_export($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function getAdsList($limit, $offset, $statusIN){
        $sql = 'SELECT
                   "content",
                    "clicks",
                    "shows",
                    "rating",
                    "ads"."click_price" as "clickPrice",
                    "max_clicks" as "maxClicks",
                    "campaign_id" as "campaignId",
                    "categories" as "additionalCategories",
                    "ads"."id"
                FROM "ads"
                WHERE "status" IN ('.$statusIN.') OR "status" IS NULL
                ORDER BY "ads"."id"
                LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $result = $statement->execute();

        if(!$result){
            throw new DataLayerException(var_export($statement->errorInfo()));
        }

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($result as $key => $row){
            $result[$key] = $this->prepareRow($row);
        }
        return $result;
    }

    public function setSiteModeratedState($siteId, $state){
        $sql = 'UPDATE "sites" SET "moderated" = :state, "status" = :state WHERE id = :id';
        $statement = $this->pdoPersistent->prepare($sql);
        $result =  $statement->execute([':state' => $state, ':id' => $siteId]);
        if(!$result){
            throw new DataLayerException(var_export($statement->errorInfo()));
        }
        return true;
    }

    public function setAdsModeratedState($siteId, $state, $shockContent, $adultContent){
        $sql = 'UPDATE "ads" SET "status" = :state, "shock" = :shock, "adult" = :adult WHERE id = :id';
        $statement = $this->pdoPersistent->prepare($sql);
        $result =  $statement->execute(array(
            ':state' => $state,
            ':id' => $siteId,
            ':shock' => $shockContent ? 't' : 'f',
            ':adult' => $adultContent ? 't' : 'f',
        ));
        if(!$result){
            throw new DataLayerException(var_export($statement->errorInfo()));
        }
        return true;
    }

    private function prepareRow($row){
        if(!is_array($row['content']))
            $row['content'] = json_decode($row['content'], 1);

        $clicksKey = "ads-clicks:{$row['id']}";
        $showsKey = "ads-shows:{$row['id']}";
        $clicks = RedisIO::get($clicksKey);
        $shows = RedisIO::get($showsKey);
        $row['shows'] = $shows ? $shows : $row['shows'];
        $row['content']['imageFile'] = $row['content']['imageUrl'];
        $row['content']['imageUrl'] = ImageWorker::buildAddress($row['content']['imageUrl']);
        $row['clicks'] = $clicks ? $clicks : $row['clicks'];
        $row['additionalCategories'] = $this->parseArrayFromDatabaseString($row['additionalCategories']);
        $adsKey = "ads:{$row['id']}";
        $row['status'] = (bool) RedisIO::get($adsKey);
        return $row;
    }
} 