<?php


namespace models\dataSource;
use core\RedisIO;
use exceptions\DataLayerException;
use models\dataSource\DataSourceLayer;

class CampaignDataSource extends DataSourceLayer
{
    const BLACK_LIST_NAME = 'black_list';
    const WHITE_LIST_NAME = 'white_list';

    public function tableName()
    {
        return 'campaign';
    }

    public function getList($userId, AdsDataSource $adsDataSource, $limit = 100, $offset = 0){
        $sql = 'SELECT
                     "campaign"."id",
                     "campaign"."description",
                     "campaign"."limit",
                     "campaign"."click_price" AS "clickPrice",
                     "campaign"."daily_limit" AS "dailyLimit",
                     "campaign"."geo",
                     "campaign"."site_url",
                     "campaign"."age_limit",
                     "campaign"."gender",
                     "campaign"."subject",
                     "campaign"."site_id" AS "siteId",
                     "campaign"."publish",
                     "campaign"."labels_id" AS "labelsId"
                FROM "campaign"
                WHERE "campaign"."user_id" = :user_id
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset;
                ';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $key => $camp){
            $stats = $this->getCampaignStats($camp['id'], $userId, $adsDataSource);
            $camp['clicks'] = $stats['clicks'];
            $camp['shows'] = $stats['shows'];
            $data[$key] = $this->prepareRow($camp);
        }

        return $data;
    }

    public function getAdsStatus($campaign_id){
        $sql = 'SELECT
                    status
                FROM
                    ads
                WHERE
                    campaign_id = :id';

        $data = $this->runQuery($sql, $this->pdoPersistent, [
            ':id' => $campaign_id
        ]);

        $result = [
            'runned' => 0,
            'paused' => 0,
            'moderated' => 0,
            'blocked' => 0
        ];

        foreach($data as $ad){
            switch ($ad['status']){
                case 0:
                    $result['moderated'] += 1;
                    break;
                case 1:
                    $result['runned'] += 1;
                    break;
                case 200:
                    $result['blocked'] += 1;
                    break;
                case 300:
                    $result['paused'] += 1;
                    break;
            }
        }

        return $result;
    }

    public function getEmptyStatsList($userId, AdsDataSource $adsDataSource, $limit = 100, $offset = 0, $archived = 'actual', $labelId = null)
    {
        $sql = 'SELECT
                     "campaign"."id",
                     "campaign"."description",
                     "campaign"."limit",
                     "campaign"."click_price" AS "clickPrice",
                     "campaign"."daily_limit" AS "dailyLimit",
                     "campaign"."geo",
                     "campaign"."site_url",
                     "campaign"."age_limit",
                     "campaign"."gender",
                     "campaign"."subject",
                     "campaign"."site_id" as "siteId",
                     "campaign"."publish",
                     "campaign"."labels_id" AS "labelsId"
                FROM "campaign"
                WHERE "campaign"."user_id" = :user_id AND "campaign"."publish" ';

        $sql .= $archived == 'archived' ? '= 500' : '<> 500';
        if($labelId !== null)
            $sql .= ' AND ( "campaign"."labels_id" IS NOT NULL AND :label_id = ANY("campaign"."labels_id") ) ';
        $sql .= ' ORDER BY id DESC LIMIT :limit OFFSET :offset;';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);
        if($labelId !== null)
            $statement->bindParam(':label_id', $labelId, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $key => $camp){
            $camp['clicks'] = 0;
            $camp['shows'] = 0;
            $data[$key] = $this->prepareEmptyRow($camp);
        }

        return $data;
    }

    public function getStatsList(array $params){
        $sql = 'SELECT
                     "campaign"."id",
                     "campaign"."description",
                     "campaign"."limit",
                     "campaign"."click_price" AS "clickPrice",
                     "campaign"."daily_limit" AS "dailyLimit",
                     "campaign"."geo",
                     "campaign"."site_url",
                     "campaign"."age_limit",
                     "campaign"."gender",
                     "campaign"."subject",
                     "campaign"."site_id" as "siteId",
                     "campaign"."publish",
                     "campaign"."labels_id" AS "labelsId"
                FROM "campaign"
                WHERE "campaign"."user_id" = :user_id AND "campaign"."publish" <> :status
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset;
                ';

        $archived = \models\Campaign::STATUS_ARCHIVED;

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':status', $archived, \PDO::PARAM_INT);
        $statement->bindParam(':limit', $params['limit'], \PDO::PARAM_INT);
        $statement->bindParam(':offset', $params['offset'], \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $key => $camp){
            $stats = $this->getCampaignStats($camp['id'], $params['user_id'], $params['adsDS']);
            $camp['clicks'] = $stats['clicks'];
            $camp['shows'] = $stats['shows'];
            $data[$key] = $this->prepareRow($camp);
        }

        $beforePostgresStats = $this->getBeforeCampaignStats($params);

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

    public function getCampaigns(array $params){
        $labelId = isset($params['labelId']) && $params['labelId'] ? $params['labelId'] : null;

        $allStats = $this->getEmptyStatsList($params['user_id'], new AdsDataSource(), $params['limit'], $params['offset'], $params['status'], $labelId);
        return $allStats;
    }

    public function getBeforeCampaignStats(array $params){
        $sql = 'SELECT
                    advertiser_stats.campaign_id as "id",
                    sum(shows) as "shows",
                    sum(clicks) as "clicks",
                    sum(costs) as "costs",
                    "campaign".description
                FROM
                    advertiser_stats
                     INNER JOIN "campaign"
                        ON ("campaign".id = "advertiser_stats"."campaign_id")
                WHERE
                    advertiser_stats.user_id = :user_id
                    AND date("date") < date(:start_date)
                GROUP BY "campaign".description, advertiser_stats.campaign_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
    }

    public function update($id, $data, $userId){
        $fields = '';
        $params = [];
        if(!empty($data['description'])){
            $fields .= '"description" = :description ';
            $params[':description'] = $data['description'];
        }
        //campaign
        if(!empty($data['clickPrice'])){
            $fields .= ($fields ? ',' : '') . '"click_price" = :click_price';
            $params[':click_price'] = $data['clickPrice'];
        }
        if(!empty($data['categories'])){
            $fields .= ($fields ? ',' : '') . '"categories" = :categories';
            $params[':categories'] = $this->prepareArrayForInsert($data['categories']);
        }
        if(!empty($data['limit'])){
            $fields .= ($fields ? ',' : '') . '"limit" = :limit';
            $params[':limit'] = $data['limit'];
        }
        if(!empty($data['endDate'])){
            $fields .= ($fields ? ',' : '') . '"stop_date" = :stop_date';
            $params[':stop_date'] = $data['endDate'];
        }
        if(!empty($data['blackList'])){
            $fields .= ($fields ? ',' : '') . '"black_list" = :black_list';
            $params[':black_list'] = $data['blackList'];
        }
        if(!empty($data['whiteList'])){
            $fields .= ($fields ? ',' : '') . '"white_list" = :white_list';
            $params[':white_list'] = $data['whiteList'];
        }
        if(!empty($data['startDate'])){
            $fields .= ($fields ? ',' : '') . '"start_date" = :start_date';
            $params[':start_date'] = $data['startDate'];
        }
        if(!empty($data['dailyLimit'])){
            $fields .= ($fields ? ',' : '') . '"daily_limit" = :daily_limit';
            $params[':daily_limit'] = $data['dailyLimit'];
        }
        if(!empty($data['geo'])){
            $fields .= ($fields ? ',' : '') . '"geo" = :geo';
            $params[':geo'] = $data['geo'];
        }
        if(isset($data['site_url'])){
            $fields .= ($fields ? ',' : '') . '"site_url" = :site_url';
            $params[':site_url'] = $data['site_url'];
        }
        if(isset($data['age_limit'])){
            $fields .= ($fields ? ',' : '') . '"age_limit" = :age_limit';
            $params[':age_limit'] = $data['age_limit'];
        }
        if(isset($data['gender'])){
            $fields .= ($fields ? ',' : '') . '"gender" = :gender';
            $params[':gender'] = $data['gender'];
        }

        if(isset($data['siteId'])){
            $fields .= ($fields ? ',' : '') . '"site_id" = :siteId';
            $params[':siteId'] = $data['siteId'];
        }

        if(isset($data['subject'])){
            $fields .= ($fields ? ',' : '') . '"subject" = :subject';
            $params[':subject'] = $this->prepareArrayForInsert($data['subject']);
        }

        if(isset($data['labelsId'])){
            $fields .= ($fields ? ',' : '') . '"labels_id" = :labelsId';
            $params[':labelsId'] = $this->prepareArrayForInsert($data['labelsId']);
        }

        if(!$fields){
            return false;
        }

        $params[':user_id'] = $userId;
        $params[':id'] = $id;
        $sql = 'UPDATE campaign SET '.$fields.' WHERE id = :id AND user_id= :user_id';
        $statement = $this->pdoPersistent->prepare($sql);

        $result = $statement->execute($params);
        if(!$result){
            throw new DataLayerException('cannot update campaign' . $this->parseError($statement->errorInfo()));
        }else{
            $model = new AdsDataSource();
            if($adsList = $model->getList($id, $userId)){
                $ids = [];
                foreach($adsList as $ads){
                    $ids[] = $ads['id'];
                }

                $params = [];
                $categories = isset($adsList[0]['additionalCategories']) ? $adsList[0]['additionalCategories'] : [];

                $params['categories'] = $categories;
                $params['geo'] = [
                    'country' => $data['countries'],
                    'region' => $data['regions'],
                ];

                $model->updateAdsInfo($params, $ids);
            }
        }
        return true;
    }

    public function getById($data){
        $sql = 'SELECT
                     "campaign"."id",
                     "campaign"."description",
                     "campaign"."click_price" as "clickPrice",
                     "campaign"."categories",
                     "campaign"."limit",
                     "campaign"."black_list" as "blackList",
                     "campaign"."white_list" as "whiteList",
                     "campaign"."start_date" as "startDate",
                     "campaign"."stop_date" as "stopDate",
                     "campaign"."daily_limit" as "dailyLimit",
                     "campaign"."geo",
                     "campaign"."site_url",
                     "campaign"."age_limit",
                     "campaign"."gender",
                     "campaign"."site_id" AS "siteId",
                     "campaign"."subject",
                     "campaign"."labels_id" AS "labelsId"
                FROM "campaign"
                WHERE "campaign"."user_id" = :user_id AND "campaign"."id" = :id
                ';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $data['userId'], \PDO::PARAM_INT);
        $statement->bindParam(':id', $data['id'], \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $campaign = array();

        foreach($result as $camp){
            if(!$campaign){
                $campaign = $camp;

                $campaign['categories'] = $this->parseArrayFromDatabaseString($camp['categories']);
                $campaign['blackList'] = $this->parseArrayFromDatabaseString($camp['blackList']);
                $campaign['whiteList'] = $this->parseArrayFromDatabaseString($camp['whiteList']);
                $campaign['subject'] = $this->parseArrayFromDatabaseString($camp['subject']);
                $campaign['geo'] = json_decode($camp['geo'], 1);
                $campaign['publish'] = (bool)RedisIO::get("campaignId:{$camp['id']}");
            }
        }

        unset($result);
        return $campaign;
    }

    public function save($data = []){

        $sql = 'INSERT INTO "campaign"
                    ("description",
                    "user_id",
                    "click_price",
                    "categories",
                    "geo",
                    "start_date",
                    "stop_date",
                    "limit",
                    "daily_limit",
                    "black_list",
                    "site_url",
                    "age_limit",
                    "gender",
                    "site_id",
                    "subject",
                    "publish",
                    "labels_id"
                    )
                VALUES
                    (:description,
                    :user_id,
                    :click_price,
                    :categories,
                    :geo,
                    :start_date,
                    :stop_date,
                    :limit,
                    :daily_limit,
                    :black_list,
                    :site_url,
                    :age_limit,
                    :gender,
                    :site_id,
                    :subject,
                    :publish,
                    :labels_id
                    )';
        $statement = $this->pdoPersistent->prepare($sql);

        $categories = isset($data['categories']) ? $this->prepareArrayForInsert($data['categories']) : null;
        $geo = isset($data['geo']) ? $data['geo'] : json_encode(['countries'=>[], 'regions'=>[], 'cities'=>[]]);
        $blackList = isset($data['blackList']) ? $this->prepareArrayForInsert($data['blackList']) : null;
        $limit = $data['limit'] ? $data['limit'] : null;
        $dailyLimit = $data['dailyLimit'] ? $data['dailyLimit'] : null;
        $siteUrl = isset($data['site_url']) ? $data['site_url'] : '';
        $ageLimit = isset($data['age_limit']) ? $data['age_limit'] : '';
        $labelsId = isset($data['labelsId']) ? $this->prepareArrayForInsert($data['labelsId']) : null;

        $statement->bindParam(':categories', $categories);
        $statement->bindParam(':geo', $geo);
        $statement->bindParam(':black_list', $blackList);
        $statement->bindParam(':description', $data['description'], \PDO::PARAM_STR);
        $statement->bindParam(':user_id', $data['userId'], \PDO::PARAM_INT);
        $statement->bindParam(':click_price', $data['clickPrice']);
        $statement->bindParam(':start_date', $data['startDate']);
        $statement->bindParam(':stop_date', $data['endDate']);
        $statement->bindParam(':site_id', $data['siteId']);
        $statement->bindParam(':limit', $limit);
        $statement->bindParam(':daily_limit', $dailyLimit);
        $statement->bindParam(':site_url', $siteUrl);
        $statement->bindParam(':age_limit', $ageLimit);
        $statement->bindParam(':gender', $ageLimit);
        $statement->bindParam(':subject', $subject);
        $statement->bindParam(':publish', $data['publish'], \PDO::PARAM_INT);
        $statement->bindParam(':labels_id', $labelsId);

        $result = $statement->execute();
        if (!$result) {

            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        return $this->pdoPersistent->lastInsertId('campaign_id_seq');
    }

    public function delete($id, $userId){
        $sql = 'DELETE
                FROM "campaign"
                WHERE id = :id AND "campaign"."user_id" = :user_id';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        $statement->execute();
        return (bool)$statement->rowCount();
    }

    public function getCampaignStats($id, $userId, AdsDataSource $adsDS){
        $adsList = $adsDS->getList($id, $userId);
        $shows = [];
        $clicks = [];
        foreach($adsList as $ads){
            $shows[] = $ads['shows'];
            $clicks[] = $ads['clicks'];
        }
        return array('shows' => array_sum($shows), 'clicks' => array_sum($clicks));
    }

    public function setStatus($id, $statusId)
    {
        $sql = 'UPDATE "campaign" SET
                  "publish" = :publish
                WHERE
                  "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':publish', $statusId, \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while change site status: ' . $this->parseError($statement->errorInfo()));

        return true;
    }

    public function setLists($listData, $campaignsToClean)
    {
        $campaignIdsString = implode(',', $listData['campaigns']);

        if($campaignsToClean){
            $clearCampaignsIdsString = implode(',', $campaignsToClean);
            $clearCampaignSql = [
                'campaigns' => 'UPDATE "campaign" SET "'.self::BLACK_LIST_NAME.'" = NULL, "'.self::WHITE_LIST_NAME.'" = NULL WHERE "id" IN ('.$clearCampaignsIdsString.')',
                'adsPersistent' => 'UPDATE "ads" SET "'.self::BLACK_LIST_NAME.'" = NULL, "'.self::WHITE_LIST_NAME.'" = NULL WHERE "campaign_id" IN ('.$clearCampaignsIdsString.')',
                'adsActual' => 'UPDATE "ads" SET "'.self::BLACK_LIST_NAME.'" = NULL, "'.self::WHITE_LIST_NAME.'" = NULL WHERE "campaign_id" IN ('.$clearCampaignsIdsString.')',
            ];
        }else{
            $clearCampaignSql = null;
        }
        $updateCampSql = 'UPDATE "campaign" SET "'.$listData['type'].'" = :list, "'.$listData['alterType'].'" = NULL WHERE "id" IN ('.$campaignIdsString.')';
        $updatePersistentAdsSql = 'UPDATE "ads" SET "'.$listData['type'].'" = :list, "'.$listData['alterType'].'" = NULL WHERE "campaign_id" IN ('.$campaignIdsString.')';
        $updateActualAdsSql = 'UPDATE "ads" SET "'.$listData['type'].'" = :list, "'.$listData['alterType'].'" = NULL WHERE "campaign_id" IN ('.$campaignIdsString.')';

        $sqlParams = [
            ':list' => $this->prepareArrayForInsert($listData['sites']),
        ];

        return $this->runTransaction($updateCampSql, $updatePersistentAdsSql, $updateActualAdsSql, $sqlParams, $clearCampaignSql);
    }

    public function getBlackAndWhiteLists($id)
    {
        $sql = 'SELECT
                    "black_list" AS "blackList",
                    "white_list" AS "whiteList"
                FORM
                    "campaign"
                WHERE
                    "id" = :id';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        if(!$statement->execute()){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        if($result = $statement->fetch()){
            if(isset($result['blackList']) && $result['blackList'])
                $result['blackList'] = $this->parseArrayFromDatabaseString($result['blackList']);
            if(isset($result['whiteList']) && $result['whiteList'])
                $result['whiteList'] = $this->parseArrayFromDatabaseString($result['whiteList']);
        }

        return $result;
    }

    public function removeLists(array $campaignIds)
    {
        $inSql = implode(',', $campaignIds);

        $updateCampSql = 'UPDATE "campaign" SET "'.self::BLACK_LIST_NAME.'" = NULL, "'.self::WHITE_LIST_NAME.'" = NULL WHERE "id" IN ('.$inSql.')';
        $updatePersistentAdsSql = 'UPDATE "ads" SET "'.self::BLACK_LIST_NAME.'" = NULL, "'.self::WHITE_LIST_NAME.'" = NULL WHERE "campaign_id" IN ('.$inSql.')';
        $updateActualAdsSql = 'UPDATE "ads" SET "'.self::BLACK_LIST_NAME.'" = NULL, "'.self::WHITE_LIST_NAME.'" = NULL WHERE "campaign_id" IN ('.$inSql.')';

        return $this->runTransaction($updateCampSql, $updatePersistentAdsSql, $updateActualAdsSql, null);
    }

    private function prepareRow($row)
    {
        $row['status'] = $row['publish'];
        $row['publish'] = (bool)RedisIO::get("campaignId:{$row['id']}");
        $row['expenses'] = floatval(RedisIO::get("campaign-expenses:{$row['id']}"));
        if(isset($row['blackList']))
            $row['blackList'] = $this->parseArrayFromDatabaseString($row['blackList']);
        if(isset($row['whiteList']))
            $row['whiteList'] = $this->parseArrayFromDatabaseString($row['whiteList']);
        if(isset($row['labelsId']) && isset($row['labelsId']))
            $row['labelsId'] = $this->parseArrayFromDatabaseString($row['labelsId']);
        return $row;
    }

    private function prepareEmptyRow($row){
        $row['status'] = $row['publish'];
        $row['publish'] = (bool)RedisIO::get("campaignId:{$row['id']}");
        $row['expenses'] = 0;
        if(isset($row['blackList']))
            $row['blackList'] = $this->parseArrayFromDatabaseString($row['blackList']);
        if(isset($row['whiteList']))
            $row['whiteList'] = $this->parseArrayFromDatabaseString($row['whiteList']);
        if(isset($row['labelsId']) && isset($row['labelsId']))
            $row['labelsId'] = $this->parseArrayFromDatabaseString($row['labelsId']);
        return $row;
    }

    private function runTransaction($updateCampSql, $updatePersistentAdsSql, $updateActualAdsSql, $sqlParams, $clearCampaignSql = null)
    {
        $campaignPersistent = $this->pdoPersistent->prepare($updateCampSql);
        $this->pdoPersistent->beginTransaction();

        try{
            if(!$campaignPersistent->execute($sqlParams)){
                $this->pdoPersistent->rollBack();
                throw new DataLayerException($this->parseError($campaignPersistent->errorInfo()));
            }
        }catch(\Exception $e){
            $this->pdoPersistent->rollBack();
            throw $e;
        }

        if($clearCampaignSql !== null){
            $campaignToCleatPersistent = $this->pdoPersistent->prepare($clearCampaignSql['campaigns']);
            try{
                if(!$campaignToCleatPersistent->execute()){
                    $this->pdoPersistent->rollBack();
                    throw new DataLayerException($this->parseError($campaignToCleatPersistent->errorInfo()));
                }
            }catch(\Exception $e){
                $this->pdoPersistent->rollBack();
                throw $e;
            }

            $adsToClearPersistent = $this->pdoPersistent->prepare($clearCampaignSql['adsPersistent']);
            try{
                if(!$adsToClearPersistent->execute()){
                    $this->pdoPersistent->rollBack();
                    throw new DataLayerException($this->parseError($adsToClearPersistent->errorInfo()));
                }
            }catch(\Exception $e){
                $this->pdoPersistent->rollBack();
                throw $e;
            }
        }

        $adsPersistent = $this->pdoPersistent->prepare($updatePersistentAdsSql);

        try{
            if(!$adsPersistent->execute($sqlParams)){
                $this->pdoPersistent->rollBack();
                throw new DataLayerException($this->parseError($adsPersistent->errorInfo()));
            }
        }catch(\Exception $e){
            $this->pdoPersistent->rollBack();
            throw $e;
        }

        if($clearCampaignSql !== null){
            $adsToClearActual = $this->pdoActual->prepare($clearCampaignSql['adsActual']);
            $this->pdoActual->beginTransaction();

            try{
                if(!$adsToClearActual->execute()){
                    $this->pdoPersistent->rollBack();
                    $this->pdoActual->rollBack();
                    throw new DataLayerException($this->parseError($adsToClearActual->errorInfo()));
                }
            }catch(\Exception $e){
                $this->pdoPersistent->rollBack();
                $this->pdoActual->rollBack();
                throw $e;
            }
        }

        $adsActual = $this->pdoActual->prepare($updateActualAdsSql);
        if($clearCampaignSql === null)
            $this->pdoActual->beginTransaction();

        try{
            if(!$adsActual->execute($sqlParams)){
                $this->pdoPersistent->rollBack();
                $this->pdoActual->rollBack();
                throw new DataLayerException($this->parseError($adsActual->errorInfo()));
            }
        }catch(\Exception $e){
            $this->pdoPersistent->rollBack();
            $this->pdoActual->rollBack();
            throw $e;
        }

        $this->pdoPersistent->commit();
        $this->pdoActual->commit();

        return true;
    }

}