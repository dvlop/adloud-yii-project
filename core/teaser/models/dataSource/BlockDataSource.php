<?php


namespace models\dataSource;
use core\RedisConnection;
use core\RedisIO;
use exceptions\DataLayerException;
use models\Block;
use models\Category;
use models\dataSource\DataSourceLayer;

class BlockDataSource extends DataSourceLayer {

    public function tableName()
    {
        return 'blocks';
    }

    public function save(array $data)
    {
        $sql = 'SELECT id FROM "sites" WHERE id = :site_id AND "user_id" = :user_id';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':site_id', $data['siteId'], \PDO::PARAM_INT);
        $statement->bindParam(':user_id', $data['userId'], \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new \exceptions\DataLayerException('error while saving block: '.$this->parseError($statement->errorInfo()));


        if(!$statement->fetch(\PDO::FETCH_ASSOC)){
            throw new \exceptions\DataLayerException('invalid site');
        }

        \Yii::app()->test->show($data);

        $sql = 'INSERT INTO "blocks"
                    (
                      "name",
                      "description",
                      "size",
                      "color",
                      "bg",
                      "status",
                      "site_id",
                      "allow_shock",
                      "allow_adult",
                      "allow_sms",
                      "allow_animation",
                      "content",
                      "type",
                      "create_date",
                      "categories"
                    )
                VALUES
                    (
                      :name,
                      :description,
                      :size,
                      :color,
                      :bg,
                      :status,
                      :site_id,
                      :allow_shock,
                      :allow_adult,
                      :allow_sms,
                      :allow_animation,
                      :content,
                      :type,
                      :create_date,
                      :categories
                    )';


        $date = isset($data['createDate']) ? $data['createDate'] : '\'now\'';
        $categories = isset($data['categories']) ? $this->prepareArrayForInsert($data['categories']) : null;
        $allowShock = $data['allowShock'] ? '\'t\'' : '\'f\'';
        $allowAdult = $data['allowAdult'] ? '\'t\'' : '\'f\'';
        $allowSms = $data['allowSms'] ? '\'t\'' : '\'f\'';
        $allowAnimation = $data['allowAnimation'] ? '\'t\'' : '\'f\'';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindParam(':description', $data['description'], \PDO::PARAM_STR);
        $statement->bindParam(':size', $data['size'], \PDO::PARAM_STR);
        $statement->bindParam(':color', $data['color'], \PDO::PARAM_STR);
        $statement->bindParam(':bg', $data['bg'], \PDO::PARAM_STR);
        $statement->bindParam(':status', $data['status'], \PDO::PARAM_INT);
        $statement->bindParam(':site_id', $data['siteId'], \PDO::PARAM_INT);
        $statement->bindParam(':allow_shock', $allowShock);
        $statement->bindParam(':allow_adult', $allowAdult);
        $statement->bindParam(':allow_sms', $allowSms);
        $statement->bindParam(':allow_animation', $allowAnimation);
        $statement->bindParam(':content', $data['content'], \PDO::PARAM_STR);
        $statement->bindParam(':type', $data['type'], \PDO::PARAM_STR);
        $statement->bindParam(':create_date', $date);
        $statement->bindParam(':categories', $categories);

        if(!$statement->execute()){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        return $this->pdoPersistent->lastInsertId('blocks_id_seq');
    }

    public function update(array $data)
    {
        $pdo = $this->pdoPersistent;

        $sql = 'UPDATE "blocks" SET
            ("site_id", "categories", "description", "ads_type", "status", "color_scheme", "fill_type", "content") =
            (:site_id, :categories, :description, :ads_type, :status, :color_scheme, :fill_type, :content)
            WHERE "id" = :id';

        $categories = isset($data['categories']) ? '{'. implode(',', $data['categories']) . '}' : null;

        $statement = $pdo->prepare($sql);

        $statement->bindParam(':site_id', $data['siteId'], \PDO::PARAM_STR);
        $statement->bindParam(':categories', $categories, \PDO::PARAM_STR);
        $statement->bindParam(':description', $data['description'], \PDO::PARAM_STR);
        $statement->bindParam(':ads_type', $data['size'], \PDO::PARAM_STR);
        $statement->bindParam(':fill_type', $data['bg'], \PDO::PARAM_STR);
        $statement->bindParam(':color_scheme', $data['color'], \PDO::PARAM_STR);
        $statement->bindParam(':id', $data['id'], \PDO::PARAM_INT);
        $statement->bindParam(':status', $data['status'], \PDO::PARAM_INT);
        $statement->bindParam(':content', $data['content']);

        $result = $statement->execute();

        if(!$result){
            throw new \exceptions\DataLayerException($this->parseError($pdo->errorInfo()));
        }

        return $result;
    }

    public function changeStatus($data = [])
    {
        $pdo = $this->pdoPersistent;

        $sql = 'UPDATE "blocks" SET
            "status" = :status
            WHERE "id" = :id';

        $statement = $pdo->prepare($sql);

        $statement->bindParam(':id', $data['id'], \PDO::PARAM_INT);
        $statement->bindParam(':status', $data['status'], \PDO::PARAM_INT);

        $result = $statement->execute();

        if(!$result){
            throw new \exceptions\DataLayerException('error while adding block: ' . var_export($pdo->errorInfo(), 1));
        }

        return true;
    }

    public function setCategories($siteId, $categories = [])
    {
        $pdo = $this->pdoPersistent;

        $sql = 'UPDATE "blocks" SET
            "categories" = :categories
            WHERE "site_id" = :site_id';

        $statement = $pdo->prepare($sql);

        $categories = $this->prepareArrayForInsert($categories);
        $statement->bindParam(':site_id', $siteId, \PDO::PARAM_INT);
        $statement->bindParam(':categories', $categories);

        $result = $statement->execute();

        if(!$result){
            throw new \exceptions\DataLayerException('error while adding block: ' . var_export($pdo->errorInfo(), 1));
        }

        return true;
    }

    public function getList(array $params)
    {
        $sql = 'SELECT
                    "blocks"."shows",
                    "blocks"."clicks",
                    "blocks"."id",
                    "blocks"."site_id" AS "siteId",
                    "blocks"."description",
                    "blocks"."ads_type" AS "size",
                    "blocks"."status",
                    "blocks"."color_scheme" AS "color",
                    "blocks"."fill_type" AS "bg",
                    "blocks"."content",
                    "blocks"."type",
                    "sites"."category",
                    "sites"."url",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."id" as "siteId",
                    "sites"."moderated" as "siteModerated",
                    "sites"."status" AS "siteStatus",
                    date("blocks"."create_date") AS "date"
                FROM "blocks"
                        INNER JOIN "sites"
                        ON ("sites"."id" = "blocks"."site_id")
                WHERE '.(isset($params['siteId']) && $params['siteId'] ? '"blocks"."site_id" = :site_id AND' : '').'"sites"."user_id" = :user_id';

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
            $sql .= ' AND date("blocks"."create_date") >= date(:start_date) AND  date("blocks"."create_date") <= date(:end_date)';
        }

        $sql .= ' ORDER BY "blocks"."id" DESC
                LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['userId'], \PDO::PARAM_INT);
        if(isset($params['siteId']) && $params['siteId']){
            $statement->bindParam(':site_id', $params['siteId'], \PDO::PARAM_INT);
        }
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);
        if($startDate !== null && $endDate !== null){
            $statement->bindParam(':start_date', $startDate, \PDO::PARAM_STR);
            $statement->bindParam(':end_date', $endDate, \PDO::PARAM_STR);
        }

        if(!$statement->execute()){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if(!$data){
            return array();
        }

        foreach($data as $key => $row){
            $data[$key] = $this->prepareRow($row);
        }

        return $data;
    }

    public function getEmptyStatsList(array $params){
        $sql = 'SELECT
                    "blocks"."shows",
                    "blocks"."clicks",
                    "blocks"."id",
                    "blocks"."site_id" AS "siteId",
                    "blocks"."description",
                    "blocks"."ads_type" AS "size",
                    "blocks"."status",
                    "blocks"."color_scheme" AS "color",
                    "blocks"."fill_type" AS "bg",
                    "blocks"."content",
                    "blocks"."type",
                    "sites"."category",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."id" AS "siteId",
                    "sites"."moderated" AS "siteModerated",
                    "sites"."status" AS "siteStatus"
                FROM "blocks"
                        INNER JOIN "sites"
                        ON ("sites"."id" = "blocks"."site_id")
                WHERE "blocks"."site_id" = :site_id AND "sites"."user_id" = :user_id AND "blocks".status ';
        $sql .= $params['status'] == 'archived' ? '= 500' : '<> 500';
        $sql .= ' ORDER BY "blocks"."id" DESC
                LIMIT :limit OFFSET :offset';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':site_id', $params['siteId'], \PDO::PARAM_INT);
        $statement->bindParam(':limit', $params['limit'], \PDO::PARAM_INT);
        $statement->bindParam(':offset', $params['offset'], \PDO::PARAM_INT);

        if(!$statement->execute()){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if(!$data){
            return array();
        }

        foreach($data as $key => $row){
            $data[$key] = $this->prepareEmptyRow($row);
        }

        return $data;
    }

    public function getStatsList(array $params){
        $sql = 'SELECT
                    "blocks"."shows",
                    "blocks"."clicks",
                    "blocks"."id",
                    "blocks"."site_id" AS "siteId",
                    "blocks"."description",
                    "blocks"."ads_type" AS "size",
                    "blocks"."status",
                    "blocks"."color_scheme" AS "color",
                    "blocks"."fill_type" AS "bg",
                    "blocks"."content",
                    "blocks"."type",
                    "sites"."category",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."id" as "siteId",
                    "sites"."moderated" as "siteModerated",
                    "sites"."status" AS "siteStatus"
                FROM "blocks"
                        INNER JOIN "sites"
                        ON ("sites"."id" = "blocks"."site_id")
                WHERE "blocks"."site_id" = :site_id AND "sites"."user_id" = :user_id AND "blocks"."status" <> :status
                ORDER BY "blocks"."id" DESC
                LIMIT :limit OFFSET :offset';

        $archived = Block::STATUS_ARCHIVED;

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':site_id', $params['siteId'], \PDO::PARAM_INT);
        $statement->bindParam(':limit', $params['limit'], \PDO::PARAM_INT);
        $statement->bindParam(':offset', $params['offset'], \PDO::PARAM_INT);
        $statement->bindParam(':status', $archived, \PDO::PARAM_INT);

        if(!$statement->execute()){
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(!$data){
            return false;
        }

        foreach($data as $key => $row){
            $data[$key] = $this->prepareRow($row);
        }

        $beforePostgresStats = $this->getBeforeBlocksStats($params);

        if(!$beforePostgresStats){
            return $data;
        }

        foreach($data as $key => $redis){
            foreach($beforePostgresStats as $postgres){
                if($postgres['id'] == $redis['id']){
                    $data[$key]['clicks'] = $redis['clicks'] - $postgres['clicks'];
                    $data[$key]['shows'] = $redis['shows'] - $postgres['shows'];
                    $data[$key]['blockIncome'] = $redis['blockIncome'] - $postgres['costs'];
                }
            }
        }

        return $data;
    }

    public function getBeforeBlocksStats($params = []) {
        $sql = 'SELECT
                    webmaster_stats.block_id as "id",
                    webmaster_stats.site_id,
                    sum(webmaster_stats.shows) as "shows",
                    sum(webmaster_stats.clicks) as "clicks",
                    sum(webmaster_stats.costs) as "costs",
                    "blocks"."description",
                    "blocks"."type"
                FROM
                    webmaster_stats
                    INNER JOIN "blocks" ON (webmaster_stats.block_id = blocks.id)
                WHERE
                    webmaster_stats.user_id = :user_id
                    AND webmaster_stats.site_id = :site_id AND date("date") < date(:start_date)
                GROUP BY "blocks"."description", "blocks"."type", "webmaster_stats"."block_id", "webmaster_stats"."site_id"
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql, $this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':site_id' => $params['siteId'],
            ':start_date' => $params['startDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
    }

    public function getBlocks($params = []) {
        $allStats = $this->getEmptyStatsList($params);
        return $allStats;
    }

    public function getAllUsersBlock(array $params)
    {
        $sql = 'SELECT
                    "blocks"."shows",
                    "blocks"."clicks",
                    "blocks"."id",
                    "blocks"."site_id" AS "siteId",
                    "blocks"."description",
                    "blocks"."ads_type" AS "size",
                    "blocks"."status",
                    "blocks"."color_scheme" AS "color",
                    "blocks"."fill_type" AS "bg",
                    "blocks"."categories",
                    "blocks"."content",
                    "blocks"."type",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."id" AS "siteId",
                    "sites"."moderated" AS "siteModerated",
                    "sites"."user_id" AS "userId",
                    "sites"."status" AS "siteStatus",
                    date("blocks"."create_date") AS "date"
                FROM "blocks"
                        INNER JOIN "sites"
                        ON ("sites"."id" = "blocks"."site_id")
                LIMIT :limit OFFSET :offset';

        $limit = 10000;
        $offset = 0;

        if(isset($params['limit']) && $params['limit'])
            $limit = $params['limit'];
        if(isset($params['offset']) && $params['offset'])
            $offset = $params['offset'];

        $statement = $this->pdoPersistent->prepare($sql);

        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);

        if(!$statement->execute())
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        if(!$data = $statement->fetchAll(\PDO::FETCH_ASSOC))
            return [];

        foreach($data as $key => $row){
            $data[$key] = $this->prepareRow($row);
        }

        return $data;
    }

    public function delete($id){
        $sql = 'DELETE FROM "webmaster_stats"
                WHERE "block_id" = :id';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result)
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        $sql = 'DELETE FROM "blocks"
                WHERE "blocks"."id" = :id';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        $result = $statement->execute();
        if(!$result)
            throw new DataLayerException($this->parseError($statement->errorInfo()));

        return true;
    }

    public function initById($id, $userId = null, $forPublish = false){
        $sql = 'SELECT
                    "blocks"."shows",
                    "blocks"."clicks",
                    "blocks"."id",
                    "blocks"."site_id" AS "siteId",
                    "sites"."banned_categories" AS "bannedCategories",
                    "blocks"."description",
                    "blocks"."ads_type" AS "size",
                    "blocks"."status",
                    "blocks"."color_scheme" AS "color",
                    "blocks"."fill_type" AS "bg",
                    "blocks"."content",
                    "blocks"."type",
                    "sites"."url",
                    "sites"."category",
                    "sites"."allow_shock" AS "allowShock",
                    "sites"."allow_adult" AS "allowAdult",
                    "sites"."allow_sms" AS "allowSms",
                    "sites"."allow_animation" AS "allowAnimation",
                    "sites"."moderated" AS "siteModerated",
                    "sites"."status" AS "siteStatus",
                    date("blocks"."create_date") AS "date"
                FROM "blocks"
                        INNER JOIN "sites"
                        ON ("sites"."id" = "blocks"."site_id")
                WHERE "blocks"."id" = :id';

        if($userId !== null)
            $sql .= ' AND "sites"."user_id" = :user_id';

        $pdo = $this->pdoPersistent;

        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);

        if($userId !== null)
            $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        if(!$statement->execute()){
            $errorInfo = $pdo->errorInfo();
            throw new \exceptions\DataLayerException('error while block init: ' . $errorInfo[2]);
        }

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if(!$data){
            return false;
        }

        return $this->prepareRow($data, $forPublish);
    }

    public function getBlockIncome($id){
        return RedisIO::get("block-income:{$id}");
    }

    public function getClicksCount($id){
        return RedisIO::get("block-clicks:{$id}");
    }

    public function getShowsCount($id){
        return RedisIO::get("block-shows:{$id}");
    }

    public function getCatsNames($catsIds)
    {
        if(!is_array($catsIds))
            $catsIds = $this->parseArrayFromDatabaseString($catsIds);
        return Category::getInstance()->getCategoryNames($catsIds);
    }

    public function checkBannedAds($adsId){
        $sql = 'SELECT
                    banned_blocks
                FROM
                    ads
                WHERE id = :ads_id';

        return $this->runQuery($sql, $this->pdoActual, [
            ':ads_id' => $adsId
        ]);
    }

    public function BanAdsInBlock($adsId, $blockId){
        $bannedBlocks = $this->checkBannedAds($adsId);
        $bannedBlocks = substr($bannedBlocks[0]['banned_blocks'],1,strlen($bannedBlocks[0]['banned_blocks'])-2);

        if ($bannedBlocks) {
            $bannedBlocks .= ','.$blockId;
            $bannedBlocks = '{'.$bannedBlocks.'}';
        } else {
            $bannedBlocks = '{';
            $bannedBlocks .= $blockId;
            $bannedBlocks .= '}';
        }

        $sql = 'UPDATE "ads"
                SET
                    banned_blocks = :banned_blocks
                WHERE
                    id = :ads_id';

        $adsActual = $this->pdoActual->prepare($sql);
        $adsPersistent = $this->pdoPersistent->prepare($sql);

        $this->pdoActual->beginTransaction();
        $this->pdoPersistent->beginTransaction();

        $params = [
            ':ads_id' => $adsId,
            ':banned_blocks' => $bannedBlocks
        ];

        try
        {
            $adsActual->execute($params);
            $adsPersistent->execute($params);

            $this->pdoActual->commit();
            $this->pdoPersistent->commit();
        }
        catch (\Exception $e)
        {
            $this->pdoActual->rollBack();
            $this->pdoPersistent->rollBack();
            throw $e;
        }

        return true;
    }

    public function DisbanAdsInBlock($adsId, $blockId){
        $bannedBlocks = $this->checkBannedAds($adsId);
        $bannedBlocks = substr($bannedBlocks[0]['banned_blocks'],1,strlen($bannedBlocks[0]['banned_blocks'])-2);

        $bannedBlocks = explode(',',$bannedBlocks);
        $bannedBlocks = array_diff($bannedBlocks,[$blockId]);
        $bannedBlocks = '{'.implode(',',$bannedBlocks).'}';

        $sql = 'UPDATE "ads"
                SET
                    banned_blocks = :banned_blocks
                WHERE
                    id = :ads_id';

        $adsActual = $this->pdoActual->prepare($sql);
        $adsPersistent = $this->pdoPersistent->prepare($sql);

        $this->pdoActual->beginTransaction();
        $this->pdoPersistent->beginTransaction();

        $params = [
            ':ads_id' => $adsId,
            ':banned_blocks' => $bannedBlocks
        ];

        try
        {
            $adsActual->execute($params);
            $adsPersistent->execute($params);

            $this->pdoActual->commit();
            $this->pdoPersistent->commit();
        }
        catch (\Exception $e)
        {
            $this->pdoActual->rollBack();
            $this->pdoPersistent->rollBack();
            throw $e;
        }

        return true;
    }

    private function prepareRow($data, $forPublish = false)
    {
        $clicks = $this->getClicksCount($data['id']);
        $shows = $this->getShowsCount($data['id']);

        $data['shows'] = $shows ? $shows : $data['shows'];
        $data['clicks'] = $clicks ? $clicks : $data['clicks'];
        if(isset($data['category'])){
            $data['categories'] = [$data['category']];
        }
        $data['blockIncome'] = RedisIO::get("block-income:{$data['id']}");

        $blockIdString = "block:{$data['id']}";
        $data['status'] = (int)(bool)RedisIO::get($blockIdString);
        $data['content'] = json_decode($data['content'], true);

        if($forPublish){
            $content = $data['content'];

            $data['captionOpacity'] = isset($content['captionOpacity']) ? (int)$content['captionOpacity'] : 1;
            $data['textOpacity'] = isset($content['textOpacity']) ? (int)$content['textOpacity'] : 1;
            $data['buttonOpacity'] = isset($content['buttonOpacity']) ? (int)$content['buttonOpacity'] : 1;
            $data['backgroundOpacity'] = isset($content['backgroundOpacity']) ? (int)$content['backgroundOpacity'] : 1;
            $data['borderOpacity'] = isset($content['borderOpacity']) ? (int)$content['borderOpacity'] : 1;
            $data['adsBorderOpacity'] = isset($content['adsBorderOpacity']) ? (int)$content['adsBorderOpacity'] : 1;
            $data['adsBackOpacity'] = isset($content['adsBackOpacity']) ? (int)$content['adsBackOpacity'] : 1;
            $data['backHoverOpacity'] = isset($content['backHoverOpacity']) ? (int)$content['backHoverOpacity'] : 1;
            $data['imgBorderOpacity'] = isset($content['imgBorderOpacity']) ? (int)$content['imgBorderOpacity'] : 1;
            $data['captionHoverOpacity'] = isset($content['captionHoverOpacity']) ? (int)$content['captionHoverOpacity'] : 1;
            $data['splitFormat'] = isset($content['splitFormat']) ? trim($content['splitFormat']): (isset($content['type']) ? trim($content['type']) : null);
            $data['width'] = isset($content['width']) ? (string)$content['width'] : '';
            $data['font'] = isset($content['font']) ? (string)$content['font'] : '';
            $data['adsBackColor'] = isset($content['adsBackColor']) ? (string)$content['adsBackColor'] : '';
            $data['adsBorderColor'] = isset($content['adsBorderColor']) ? (string)$content['adsBorderColor'] : '';
            $data['adsBorder'] = isset($content['adsBorder']) ? (int)$content['adsBorder'] : 0;
            $data['adsBorderType'] = isset($content['adsBorderType']) ? (string)$content['adsBorderType'] : '';
            $data['textPosition'] = isset($content['textPosition']) ? (string)$content['textPosition'] : '';
            $data['alignment'] = isset($content['alignment']) ? (string)$content['alignment'] : '';
            $data['indentAds'] = isset($content['indentAds']) ? (string)$content['indentAds'] : '';
            $data['borderType'] = isset($content['borderType']) ? (string)$content['borderType'] : '';
            $data['indentBorder'] = isset($content['indentBorder']) ? (string)$content['indentBorder'] : 0;
            $data['imgBorderWidth'] = isset($content['imgBorderWidth']) ? (int)$content['imgBorderWidth'] : 0;
            $data['imgBorderType'] = isset($content['imgBorderType']) ? (string)$content['imgBorderType'] : '';
            $data['imgBorderColor'] = isset($content['imgBorderColor']) ? (string)$content['imgBorderColor'] : '';
            $data['imgWidth'] = isset($content['imgWidth']) ? (string)$content['imgWidth'] : '';
            $data['borderRadius'] = isset($content['borderRadius']) ? (int)$content['borderRadius'] : 0;
            $data['border'] = isset($content['border']) ? (int)$content['border'] : 0;
            $data['borderColor'] = isset($content['borderColor']) ? (string)$content['borderColor'] : '';
            $data['backgroundColor'] = isset($content['backgroundColor']) ? (string)$content['backgroundColor'] : '';
            $data['captionFontSize'] = isset($content['captionFontSize']) ? (int)$content['captionFontSize'] : 0;
            $data['captionStyle'] = isset($content['captionStyle']) ? (string)$content['captionStyle'] : '';
            $data['descFontSize'] = isset($content['descFontSize']) ? (int)$content['descFontSize'] : 0;
            $data['descStyle'] = isset($content['descStyle']) ? (string)$content['descStyle'] : '';
            $data['useDescription'] = isset($content['useDescription']) ? (int)$content['useDescription'] : 0;
            $data['captionColor'] = isset($content['captionColor']) ? (string)$content['captionColor'] : '';
            $data['textColor'] = isset($content['textColor']) ? (string)$content['textColor'] : '';
            $data['buttonColor'] = isset($content['buttonColor']) ? (string)$content['buttonColor'] : '';
            $data['backHoverColor'] = isset($content['backHoverColor']) ? (string)$content['backHoverColor'] : '';
            $data['captionHoverColor'] = isset($content['captionHoverColor']) ? (string)$content['captionHoverColor'] : '';
            $data['captionHoverFontSize'] = isset($content['captionHoverFontSize']) ? (int)$content['captionHoverFontSize'] : 0;
            $data['captionHoverStyle'] = isset($content['captionHoverStyle']) ? (string)$content['captionHoverStyle'] : '';
            $data['descLimit'] = isset($content['descLimit']) ? (int)$content['descLimit'] : 30;
        }

        return $data;
    }

    private function prepareEmptyRow($data)
    {
        $data['shows'] = 0;
        $data['clicks'] = 0;
        if(isset($data['category'])){
            $data['categories'] = [$data['category']];
        }
        $data['blockIncome'] = 0;

        $data['siteStatus'] = isset($data['siteStatus']) ? $data['siteStatus'] : 0;

//        $blockIdString = "block:{$data['id']}";
//        $data['status'] = (int)(bool)RedisIO::get($blockIdString);

        return $data;
    }
}