<?php


namespace models\dataSource;

use core\ImageWorker;
use core\ImageServers;
use core\RatingManager;
use core\RedisConnection;
use exceptions\DataLayerException;
use models\Ads;
use models\dataSource\CountryDataSource;
use models\dataSource\RegionDataSource;
use core\RedisIO;

class AdsDataSource extends DataSourceLayer
{

    public function tableName()
    {
        return 'ads';
    }

    public function checkCampaignBelongs($userId, $campaignId){
        $sql = 'SELECT count(*) FROM "campaign" WHERE user_id = :user_id AND id = :id';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':id', $campaignId, \PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch();
        return $data['count'] == true;
    }

    public function getCategoryDefaultPrice($id){
        $sql = 'SELECT "min_click_price" as "minClickPrice" FROM "categories" WHERE "id" = :id;';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        return isset($data['minClickPrice']) ? $data['minClickPrice'] : false;
    }

    public function save($data = [], $userId = '')
    {
        $validCampaign = $this->checkCampaignBelongs($userId, $data['campaignId']);
        if(!$validCampaign){
            throw new \exceptions\DataLayerException('campaign does not belong to user');
        }

        $sql = 'INSERT INTO "ads"
                    ("content", "click_price", "max_clicks", "campaign_id", "categories", "rating", "type", "moderated", "geo_countries", "geo_regions", "status", "black_list", "white_list", "adult", "shock", "sms", "animation")
                VALUES
                    (:content, :click_price, :max_clicks, :campaign_id, :categories, :rating, :type, 1, :geo_countries, :geo_regions, :status, :black_list, :white_list, :adult, :shock, :sms, :animation)';

        $countries = isset($data['geo_countries']) && $data['geo_countries'] ? $this->prepareArrayForInsert($data['geo_countries']) : null;
        $regions = isset($data['geo_regions']) && $data['geo_regions'] ? $this->prepareArrayForInsert($data['geo_regions']) : null;

        $additionalCategories = $data['additionalCategories'] ? $this->prepareArrayForInsert($data['additionalCategories'] ) : null;
        $blackList = isset($data['blackList']) ? $this->prepareArrayForInsert($data['blackList']) : null;
        $whiteList = isset($data['whiteList']) ? $this->prepareArrayForInsert($data['whiteList']) : null;

        $maxClicks = !empty($data['maxClicks']) ? $data['maxClicks'] : null;
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':click_price', $data['clickPrice']);
        $statement->bindParam(':max_clicks', $maxClicks);
        $statement->bindParam(':campaign_id', $data['campaignId'], \PDO::PARAM_INT);
        $statement->bindParam(':categories', $additionalCategories);
        $statement->bindParam(':rating', $rating);
        $statement->bindParam(':content', $data['content']);
        $statement->bindParam(':type', $data['type']);
        $statement->bindParam(':geo_countries', $countries);
        $statement->bindParam(':geo_regions', $regions);
        $statement->bindParam(':status', $data['statusId']);
        $statement->bindParam(':black_list', $blackList);
        $statement->bindParam(':white_list', $whiteList);
        $statement->bindParam(':adult', $data['adult'], \PDO::PARAM_BOOL);
        $statement->bindParam(':shock', $data['shock'], \PDO::PARAM_BOOL);
        $statement->bindParam(':sms', $data['sms'], \PDO::PARAM_BOOL);
        $statement->bindParam(':animation', $data['animation'], \PDO::PARAM_BOOL);

        $result = $statement->execute();

        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }
        return $this->pdoPersistent->lastInsertId('ads_id_seq');
    }

    public function update($data, $userId, $id){
        $sql = 'UPDATE
                  "ads"
                SET
                    "click_price" = :click_price,
                    '.(!empty($data['maxClicks']) ? '"max_clicks" = :max_clicks,' : '').'
                    "campaign_id" = :campaign_id,
                    "categories" = :categories,
                    "moderated" = 0,
                    "content" = :content,
                    "geo_countries" = :geo_countries,
                    "geo_regions" = :geo_regions,
                    "black_list" = :black_list,
                    "adult" = :adult,
                    "shock" = :shock,
                    "sms" = :sms,
                    "animation" = :animation
                FROM "campaign", "users"
                WHERE "ads"."id" = :id
                    AND "campaign"."id" = "ads"."campaign_id"
                    AND "campaign"."user_id" = :user_id';

        $countries = isset($data['geo_countries']) && $data['geo_countries'] ? $this->prepareArrayForInsert($data['geo_countries']) : null;
        $regions = isset($data['geo_regions']) && $data['geo_regions'] ? $this->prepareArrayForInsert($data['geo_regions']) : null;

        $blackList = $data['blackList'] ? $this->prepareArrayForInsert($data['blackList']) : null;
        $additionalCategories = $data['additionalCategories'] ? '{'. implode(',', $data['additionalCategories']) . '}' : null;
        $maxClicks = isset($data['maxClicks']) ? $data['maxClicks'] : null;

        $statement = $this->pdoPersistent->prepare($sql);
        if(!empty($data['maxClicks'])){
            $statement->bindParam(':max_clicks', $maxClicks, \PDO::PARAM_INT);
        }

        $statement->bindParam(':click_price', $data['clickPrice']);

        $statement->bindParam(':campaign_id', $data['campaignId'], \PDO::PARAM_INT);
        $statement->bindParam(':categories', $additionalCategories);
        $statement->bindParam(':user_id', $userId);
        $statement->bindParam(':content', $data['content']);
        $statement->bindParam(':black_list', $blackList);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':geo_countries', $countries);
        $statement->bindParam(':geo_regions', $regions);
        $statement->bindParam(':adult', $data['adult'], \PDO::PARAM_BOOL);
        $statement->bindParam(':shock', $data['shock'], \PDO::PARAM_BOOL);
        $statement->bindParam(':sms', $data['sms'], \PDO::PARAM_BOOL);
        $statement->bindParam(':animation', $data['animation'], \PDO::PARAM_BOOL);

        $result = $statement->execute();

        if(!$result){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        return $id;
    }

    public function updateAdsInfo($data, $ids)
    {
        if(!$data || !$ids)
            return false;

        $geoData = isset($data['geo']) ? $data['geo'] : [];
        $categories = isset($data['categories']) && $data['categories'] ? $data['categories'] : null;

        if(!$geoData && !$categories)
            return false;

        $countries = isset($geoData['country']) && $geoData['country'] ? $geoData['country'] : null;
        $regions = isset($geoData['region']) && $geoData['region'] ? $geoData['region'] : null;

        $terms = $this->getInSql($ids);
        if(!$terms)
            return array();

        $countries = $countries ? '\''.$this->prepareArrayForInsert($countries).'\'' : 'null';
        $regions = $regions ? '\''.$this->prepareArrayForInsert($regions).'\'' : 'null';
        $categories = $categories ? '\''.$this->prepareArrayForInsert($categories).'\'' : 'null';

        $conditions = ' "geo_countries" = '.$countries.', "geo_regions" = '.$regions.', "categories" = '.$categories;

        $sql = 'UPDATE "ads" SET '.$conditions.' WHERE '.$terms;
        $this->pdoActual->beginTransaction();
        $this->pdoPersistent->beginTransaction();

        try{
            $statement = $this->pdoActual->prepare($sql);
            if(!$statement->execute()){
                $this->pdoPersistent->rollBack();
                $this->pdoActual->rollBack();
                throw new DataLayerException($this->parseError($statement->errorInfo()));
            }

            $statement = $this->pdoPersistent->prepare(str_replace('categories', 'categories', $sql));
            if(!$statement->execute()){
                $this->pdoPersistent->rollBack();
                $this->pdoActual->rollBack();
                throw new DataLayerException($this->parseError($statement->errorInfo()));
            }

            $this->pdoActual->commit();
            $this->pdoPersistent->commit();
        }catch(\Exception $e){
            $this->pdoPersistent->rollBack();
            $this->pdoActual->rollBack();
            throw $e;
        }

        return true;
    }

    public function getList($campaignId, $userId, $limit = 1000, $offset = 0){
        $sql = 'SELECT
                    "clicks",
                    "shows",
                    "rating",
                    "shock",
                    "adult",
                    "ads"."click_price" AS "clickPrice",
                    "max_clicks" AS "maxClicks",
                    "campaign_id" AS "campaignId",
                    "ads"."geo_countries" AS "geoCountries",
                    "ads"."geo_regions" AS "geoRegions",
                    "ads"."ua_device" AS "uaDevice",
                    "ads"."ua_device_model"  AS "uaDeviceModel",
                    "ads"."ua_os" AS "uaOs",
                    "ads"."ua_os_ver"  AS "uaOsVer",
                    "ads"."ua_browser" AS "uaBrowser",
                    "ads"."targets" AS "targetList",
                    "campaign"."categories" AS "additionalCategories",
                    "campaign"."geo" AS "geo",
                    "campaign"."site_id" AS "siteId",
                    "campaign"."publish" AS "campaignStatus",
                    "ads"."id",
                    "ads"."content",
                    "ads"."status",
                    "ads"."type",
                    "ads"."black_list" AS "blackList",
                    "ads"."white_list" AS "whiteList",
                    "ads"."shock",
                    "ads"."adult",
                    "ads"."sms",
                    "ads"."animation"
                FROM "ads"
                    INNER JOIN "campaign"
                        ON ("campaign"."id" = "ads"."campaign_id")
                WHERE '.($campaignId ?  '"campaign"."id" = :id AND' : '').' "campaign"."user_id" = :user_id
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset;
                ';

        $statement = $this->pdoPersistent->prepare($sql);
        if($campaignId){
            $statement->bindParam(':id', $campaignId, \PDO::PARAM_INT);
        }

        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('cannot get ads list: ' . $this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $key => $row){
            $data[$key] = $this->prepareRow($row);
        }

        return $data;
    }

    public function getStatsList($params){
        $sql = 'SELECT
                    "clicks",
                    "shows",
                    "rating",
                    "shock",
                    "adult",
                    "ads"."click_price" AS "clickPrice",
                    "max_clicks" AS "maxClicks",
                    "campaign_id" AS "campaignId",
                    "campaign"."description",
                    "ads"."geo_countries" AS "geoCountries",
                    "ads"."geo_regions" AS "geoRegions",
                    "ads"."ua_device" AS "uaDevice",
                    "ads"."ua_device_model"  AS "uaDeviceModel",
                    "ads"."ua_os" AS "uaOs",
                    "ads"."ua_os_ver"  AS "uaOsVer",
                    "ads"."ua_browser" AS "uaBrowser",
                    "ads"."targets" AS "targetList",
                    "campaign"."categories" AS "additionalCategories",
                    "campaign"."geo" AS "geo",
                    "campaign"."site_id" AS "siteId",
                    "campaign"."publish" AS "campaignStatus",
                    "ads"."id",
                    "ads"."content",
                    "ads"."status",
                    "ads"."type",
                    "ads"."black_list" AS "blackList",
                    "ads"."white_list" AS "whiteList",
                    "ads"."shock",
                    "ads"."adult",
                    "ads"."sms",
                    "ads"."animation"
                FROM "ads"
                    INNER JOIN "campaign"
                        ON ("campaign"."id" = "ads"."campaign_id")
                WHERE '.($params['campaignId'] ?  '"campaign"."id" = :id AND' : '').' "campaign"."user_id" = :user_id AND "ads"."status" <> :status
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset;
                ';

        $archived = Ads::STATUS_ARCHIVED;

        $statement = $this->pdoPersistent->prepare($sql);
        if($params['campaignId']){
            $statement->bindParam(':id', $params['campaignId'], \PDO::PARAM_INT);
        }

        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':limit', $params['limit'], \PDO::PARAM_INT);
        $statement->bindParam(':offset', $params['offset'], \PDO::PARAM_INT);
        $statement->bindParam(':status', $archived, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('cannot get ads list: ' . $this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $key => $row){
            $data[$key] = $this->prepareRow($row);
        }

        $beforePostgresStats = $this->getBeforeAdsStats($params);

        if(!$beforePostgresStats){
            return $data;
        }

        foreach($data as $key => $redis){
            foreach($beforePostgresStats as $postgres){
                if($postgres['id'] == $redis['id']){
                    $data[$key]['clicks'] = $redis['clicks'] - $postgres['clicks'];
                    $data[$key]['shows'] = $redis['shows'] - $postgres['shows'];
                    $data[$key]['expenses'] = $redis['expenses'] - $postgres['costs'];
                }
            }
        }

        return $data;
    }

    public function getEmptyStatsList($campaignId, $userId, $limit = 1000, $offset = 0, $status = null){
        $sql = 'SELECT
                    "clicks",
                    "shows",
                    "rating",
                    "shock",
                    "adult",
                    "ads"."click_price" AS "clickPrice",
                    "max_clicks" AS "maxClicks",
                    "campaign_id" AS "campaignId",
                    "campaign"."description",
                    "ads"."geo_countries" AS "geoCountries",
                    "ads"."geo_regions" AS "geoRegions",
                    "ads"."ua_device" AS "uaDevice",
                    "ads"."ua_device_model"  AS "uaDeviceModel",
                    "ads"."ua_os" AS "uaOs",
                    "ads"."ua_os_ver"  AS "uaOsVer",
                    "ads"."ua_browser" AS "uaBrowser",
                    "ads"."targets" AS "targetList",
                    "campaign"."categories" AS "additionalCategories",
                    "campaign"."geo" AS "geo",
                    "campaign"."site_id" AS "siteId",
                    "campaign"."publish" AS "campaignStatus",
                    "ads"."id",
                    "ads"."content",
                    "ads"."status",
                    "ads"."type",
                    "ads"."black_list" AS "blackList",
                    "ads"."white_list" AS "whiteList",
                    "ads"."shock",
                    "ads"."adult",
                    "ads"."sms",
                    "ads"."animation"
                FROM "ads"
                    INNER JOIN "campaign"
                        ON ("campaign"."id" = "ads"."campaign_id")
                WHERE '.($campaignId ?  '"campaign"."id" = :id AND' : '').' "campaign"."user_id" = :user_id AND "ads".status '.($status == 'actual' ?  '<> 500 ' : ' = 500 ').'
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset;
                ';

        $statement = $this->pdoPersistent->prepare($sql);
        if($campaignId){
            $statement->bindParam(':id', $campaignId, \PDO::PARAM_INT);
        }

        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('cannot get ads list: ' . $this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $key => $row){
            $data[$key] = $this->prepareEmptyRow($row);
        }

        return $data;
    }

    public function getBeforeAdsStats($params = []) {
        $sql = 'SELECT
                    advertiser_stats.ads_id as "id",
                    advertiser_stats.campaign_id,
                    sum(advertiser_stats.shows) as "shows",
                    sum(advertiser_stats.clicks) as "clicks",
                    sum(advertiser_stats.costs) as "costs",
                    json_extract_path_text(ads.content, \'caption\') as "description"
                FROM
                    advertiser_stats
                    INNER JOIN "ads" ON (advertiser_stats.ads_id = ads.id)
                WHERE
                    advertiser_stats.user_id = :user_id
                    AND  date("date") < date(:start_date)
                GROUP BY json_extract_path_text(ads.content, \'caption\'), advertiser_stats.ads_id, advertiser_stats.campaign_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql, $this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
    }

    public function getAds($params = []) {

        $allStats = $this->getEmptyStatsList($params['campaignId'], $params['user_id'], $params['limit'], $params['offset'], $params['status']);
        return $allStats;
    }

    public function initById($id, $userId){
        $sql = 'SELECT
                    "ads"."clicks",
                    "ads"."shows",
                    "ads"."rating",
                    "ads"."shock",
                    "ads"."adult",
                    "ads"."click_price" as "clickPrice",
                    "ads"."content",
                    "ads"."max_clicks" as "maxClicks",
                    "ads"."categories",
                    "ads"."campaign_id" as "campaignId",
                    "campaign"."categories" as "additionalCategories",
                    "campaign"."site_id" AS "siteId",
                    "ads"."id",
                    "ads"."geo_countries" AS "geoCountries",
                    "ads"."geo_regions"  AS "geoRegions",
                    "ads"."ua_device" AS "uaDevice",
                    "ads"."ua_device_model"  AS "uaDeviceModel",
                    "ads"."ua_os" AS "uaOs",
                    "ads"."ua_os_ver"  AS "uaOsVer",
                    "ads"."ua_browser" AS "uaBrowser",
                    "ads"."targets" AS "targetList",
                    "ads"."type",
                    "ads"."status",
                    "ads"."shock",
                    "ads"."adult",
                    "ads"."sms",
                    "ads"."animation",
                    "ads"."black_list" as "blackList",
                    "ads"."white_list" as "whiteList",
                    "ads"."rating",
                    "campaign_id" as "campaignId",
                    "campaign"."geo" AS "geo"
                FROM "ads"
                    INNER JOIN "campaign"
                        ON ("campaign"."id" = "ads"."campaign_id")
                WHERE "ads"."id" = :id AND "campaign"."user_id" = :user_id';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('cannot get ads list: ' . $this->parseError($statement->errorInfo()));
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if(!$row){
            return false;
        }

        return $this->prepareRow($row);
    }

    public function delete($id, $userId){
        $sql = 'DELETE FROM "ads"
                USING "campaign"
                WHERE "ads"."id" = :id
                  AND "campaign"."user_id" = :user_id
                  AND "campaign"."id" = "ads"."campaign_id"';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        $statement->execute();
        return (bool)$statement->rowCount();
    }

    public function setStatus($id, $statusId)
    {
        $sql = 'UPDATE "ads" SET
                  "status" = :status
                WHERE
                  "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':status', $statusId, \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while change site status: ' . $this->parseError($statement->errorInfo()));

        return true;
    }

    private function prepareRow($row)
    {
        $clicks = intval(RedisIO::get("ads-clicks:{$row['id']}"));
        $shows = intval(RedisIO::get("ads-shows:{$row['id']}"));
        $row['shows'] = $shows ? $shows : $row['shows'];
        $row['clicks'] = $clicks ? $clicks : $row['clicks'];
        $row['expenses'] = floatval(RedisIO::get("ads-expenses:{$row['id']}"));
        $row['additionalCategories'] = $this->parseArrayFromDatabaseString($row['additionalCategories']); //campaign categories
        $row['categories'] = isset($row['categories']) ? $this->parseArrayFromDatabaseString($row['categories']) : $row['additionalCategories']; //ads categories
        $row['blackList'] = $this->parseArrayFromDatabaseString($row['blackList']);
        $row['whiteList'] = $this->parseArrayFromDatabaseString($row['whiteList']);
        $row['targetList'] = $this->parseArrayFromDatabaseString($row['targetList']);
        $row['content'] = json_decode($row['content'], 1);
        $row['content']['imageFile'] = $row['content']['imageUrl'];
        $row['content']['imageUrl'] = ImageWorker::buildAddress($row['content']['imageUrl']);
        $row['geoCountries'] = $this->parseArrayFromDatabaseString($row['geoCountries']);
        $row['geoRegions'] = $this->parseArrayFromDatabaseString($row['geoRegions']);
        $row['uaDevice'] = $this->parseArrayFromDatabaseString($row['uaDevice']);
        $row['uaDeviceModel'] = $this->parseArrayFromDatabaseString($row['uaDeviceModel']);
        $row['uaOs'] = $this->parseArrayFromDatabaseString($row['uaOs']);
        $row['uaOsVer'] = $this->parseArrayFromDatabaseString($row['uaOsVer']);
        $row['uaBrowser'] = $this->parseArrayFromDatabaseString($row['uaBrowser']);
        $row['status'] = (int)$row['status'];
        //$row['status'] = (bool)RedisIO::get("ads:{$row['id']}");

        return $row;
    }

    private function prepareEmptyRow($row)
    {
        $row['shows'] = 0;
        $row['clicks'] = 0;
        $row['expenses'] = 0;
        $row['additionalCategories'] = $this->parseArrayFromDatabaseString($row['additionalCategories']); //campaign categories
        $row['categories'] = isset($row['categories']) ? $this->parseArrayFromDatabaseString($row['categories']) : $row['additionalCategories']; //ads categories
        $row['blackList'] = $this->parseArrayFromDatabaseString($row['blackList']);
        $row['whiteList'] = $this->parseArrayFromDatabaseString($row['whiteList']);
        $row['content'] = json_decode($row['content'], 1);
        $row['content']['imageFile'] = $row['content']['imageUrl'];
        $row['content']['imageUrl'] = ImageWorker::buildAddress($row['content']['imageUrl']);
        $row['geoCountries'] = $this->parseArrayFromDatabaseString($row['geoCountries']);
        $row['geoRegions'] = $this->parseArrayFromDatabaseString($row['geoRegions']);
        $row['status'] = (int)$row['status'];
        //$row['status'] = (bool)RedisIO::get("ads:{$row['id']}");

        return $row;
    }

    public function setAnimations($ids, $animation)
    {
        $animation = (bool)$animation;
        $inPart = implode(',', $ids);

        $sql = "UPDATE \"ads\" SET
                \"animation\" = :animation
                WHERE \"id\" IN({$inPart})";

        $this->pdoPersistent->beginTransaction();
        $this->pdoActual->beginTransaction();

        $persistent = $this->pdoPersistent->prepare($sql);
        $actual = $this->pdoActual->prepare($sql);

        $persistent->bindParam(':animation', $animation, \PDO::PARAM_BOOL);
        $actual->bindParam(':animation', $animation, \PDO::PARAM_BOOL);

        try{
            if(!$persistent->execute()){
                $this->pdoPersistent->rollBack();
                $this->pdoActual->rollBack();
                throw new DataLayerException($this->parseError($persistent->errorInfo()));
            }
            if(!$actual->execute()){
                $this->pdoPersistent->rollBack();
                $this->pdoActual->rollBack();
                throw new DataLayerException($this->parseError($actual->errorInfo()));
            }

            $this->pdoPersistent->commit();
            $this->pdoActual->commit();
        }catch(\Exception $e){
            $this->pdoPersistent->rollBack();
            $this->pdoActual->rollBack();
            throw $e;
        }

        return true;
    }
}