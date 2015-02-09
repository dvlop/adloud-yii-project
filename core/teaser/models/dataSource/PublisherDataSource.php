<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 09.02.14
 * Time: 19:18
 */

namespace models\dataSource;

use core\RatingManager;
use core\RedisIO;
use exceptions\DataLayerException;
use models\Ads;

class PublisherDataSource extends DataSourceLayer {

    public function tableName()
    {
        return 'adc';
    }

    public function publishAds($data, RatingManager $rm){

        $sql = 'INSERT INTO "ads"
                    (
                        "click_price",
                        "max_clicks",
                        "id",
                        "user_id",
                        "rating",
                        "categories",
                        "campaign_id",
                        "content",
                        "type",
                        "shock",
                        "adult",
                        "sms",
                        "animation",
                        "site_id",
                        "geo_countries",
                        "geo_regions",
                        "black_list",
                        "white_list",
                        "ua_device",
                        "ua_device_model",
                        "ua_os",
                        "ua_os_ver",
                        "ua_browser",
                        "targets"
                    ) 
               VALUES (
                        :click_price,
                        :max_clicks,
                        :id,
                        :user_id,
                        :rating,
                        :categories,
                        :campaign_id,
                        :content,
                        :type,
                        :shock,
                        :adult,
                        :sms,
                        :animation,
                        :site_id,
                        :geo_countries,
                        :geo_regions,
                        :black_list,
                        :white_list,
                        :ua_device,
                        :ua_device_model,
                        :ua_os,
                        :ua_os_ver,
                        :ua_browser,
                        :targets
               )';

        $this->pdoActual->beginTransaction();

        $statement = $this->pdoActual->prepare($sql);

        $countries = isset($data['geoCountries']) && $data['geoCountries'] ? $this->prepareArrayForInsert($data['geoCountries']) : null;
        $regions = isset($data['geoRegions']) && $data['geoRegions'] ? $this->prepareArrayForInsert($data['geoRegions']) : null;

        $device = isset($data['uaDevice']) && $data['uaDevice'] ? $this->prepareArrayForInsert($data['uaDevice']) : '{}';
        $device_model = isset($data['uaDeviceModel']) && $data['uaDeviceModel'] ? $this->prepareArrayForInsert($data['uaDeviceModel']) : '{}';
        $os = isset($data['uaOs']) && $data['uaOs'] ? $this->prepareArrayForInsert($data['uaOs']) : '{}';
        $os_ver = isset($data['uaOsVer']) && $data['uaOsVer'] ? $this->prepareArrayForInsert($data['uaOsVer']) : '{}';
        $browser = isset($data['uaBrowser']) && $data['uaBrowser'] ? $this->prepareArrayForInsert($data['uaBrowser']) : '{}';

        $targets = isset($data['targetList']) && $data['targetList'] ? $this->prepareArrayForInsert($data['targetList']) : '{}';

        $blackList =  $data['blackList'] ?  $this->prepareArrayForInsert($data['blackList']) : null;
        $whiteList =  $data['whiteList'] ?  $this->prepareArrayForInsert($data['whiteList']) : null;
        $categories = isset($data['categories']) ? $this->prepareArrayForInsert($data['categories']) : ( $data['additionalCategories'] ? $this->prepareArrayForInsert($data['additionalCategories']): null );
        $ctr = $data['shows'] ? $data['clicks'] / $data['shows'] : null;

        $queryData = array(
            ':click_price' => $data['clickPrice'],
            ':id' => $data['id'],
            ':user_id' => $data['userId'],
            ':max_clicks' => $data['maxClicks'],
            ':categories' => $categories,
            ':campaign_id' => $data['campaignId'],
            ':shock' => $data['shock'] ? 't' : 'f',
            ':adult' => $data['adult']  ? 't' : 'f',
            ':sms' => $data['sms']  ? 't' : 'f',
            ':animation' => $data['animation']  ? 't' : 'f',
            ':site_id' => isset($data['siteId']) ? $data['siteId'] : null,
            ':content' => json_encode($data['content']),
            ':type' => $data['type'],
            ':rating' => $rm->getAdsEfficiencyRate($data['clickPrice'], $ctr),
            ':geo_countries' => $countries,
            ':geo_regions' => $regions,
            ':black_list' => $blackList,
            ':white_list' => $whiteList,
            ':ua_device' => $device,
            ':ua_device_model' => $device_model,
            ':ua_os' => $os,
            ':ua_os_ver' => $os_ver,
            ':ua_browser' => $browser,
            ':targets' => $targets
        );

        $result = $statement->execute($queryData);

        if(!$result){
            $this->pdoActual->rollBack();
            
            if($statement->errorInfo()[0] == '23505'){
                throw new \exceptions\DataLayerException('already published ads id: '.$data['id']);
            } else {
                throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
            }
        }

        $this->pdoPersistent->beginTransaction();

        try{
            if(!\models\Ads::getInstance()->setStatus($data['id'], $data['statusId'])){
                $this->pdoActual->rollBack();
                $this->pdoPersistent->rollBack();
                throw new DataLayerException('can not change ads status');
            }
            $this->pdoActual->commit();
            $this->pdoPersistent->commit();
        }catch(\Exception $e){
            $this->pdoActual->rollBack();
            $this->pdoPersistent->rollBack();
            throw $e;
        }

        $rating = $rm->getAdsEfficiencyRate($data['clickPrice'], ($data['shows'] == 0 ? 0 : ($data['clicks'] / $data['shows'])));
        $rm->setCategoriesEfficiencyRate($rating, $data['additionalCategories'], $data['id']);

        $clicksKey = "ads-clicks:{$data['id']}";
        $showsKey = "ads-shows:{$data['id']}";

        RedisIO::set($clicksKey, $data['clicks']);

        RedisIO::set($showsKey, $data['shows']);

        $clickPriceKey = "click-price:{$data['id']}";
        RedisIO::set($clickPriceKey, $data['clickPrice']);

        $adsKey = "ads:{$data['id']}";
        RedisIO::set($adsKey, serialize($data));

        return $result;
    }

    public function setCampaignLimit($limit, $category){
        RedisIO::set("campaign-limit:{$category}", $limit);
        RedisIO::set("campaignId:{$category}", 1);
        return true;
    }

    public function unPublishAds($adsId, $statusId = 0){
        $sql = 'SELECT * FROM "ads" WHERE "id" = :id';
        $statement = $this->pdoActual->prepare($sql);
        $result = $statement->execute([':id' => $adsId]);
        $adsData = $statement->fetch(\PDO::FETCH_ASSOC);

        if(!$result || !$adsData){
            throw new DataLayerException('ad is not published');
        }

        $this->pdoActual->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdoPersistent->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->pdoActual->beginTransaction();
        $this->pdoPersistent->beginTransaction();

        $clicksKey = "ads-clicks:{$adsId}";
        $showsKey = "ads-shows:{$adsId}";

        $clicks = RedisIO::get($clicksKey);

        $shows = RedisIO::get($showsKey);

        $clickPriceKey = "click-price:{$adsId}";
        RedisIO::delete($clickPriceKey);
        $adsKey = "ads:{$adsId}";
        RedisIO::delete($adsKey);
        RedisIO::set("campaignId:{$adsData['campaign_id']}", 1);

        try{
            $sql = 'UPDATE "ads"
                    SET
                        "rating" = :rating,
                        "shows" = :shows,
                        "clicks" = :clicks
                    WHERE id = :id
                    ';
            $statement = $this->pdoPersistent->prepare($sql);
            $updateResult = $statement->execute(array(
                ':rating' => $adsData['rating'] ? $adsData['rating'] : $adsData['click_price'],
                ':shows' => $shows ? $shows : 0,
                ':clicks' => $clicks ? $clicks : 0,
                ':id' => $adsId,
            ));

            if($updateResult && $statement->rowCount()){
                RedisIO::delete($clicksKey);
                RedisIO::delete($showsKey);
            }

            $sql = 'DELETE FROM "ads" WHERE "id" = :id';
            $statement = $this->pdoActual->prepare($sql);
            $deleteResult = $statement->execute(array(':id' => $adsId));

            if(!$updateResult || !$deleteResult){
                throw new \exceptions\DataLayerException('cannot unpublish ads');
            }
            $this->pdoActual->commit();
            $this->pdoPersistent->commit();
        }catch(\Exception $e){
            $this->pdoPersistent->rollBack();
            $this->pdoActual->rollBack();
            throw $e;
        }

        $this->updateMaxCategoryRating($this->parseArrayFromDatabaseString($adsData['categories']), $adsData['rating']);
        return true;
    }

    private function updateMaxCategoryRating(array $catIds, $rating){
        $rm = new RatingManager();
        foreach($catIds as $category){
            $currentRating = RedisIO::get("max-efficiency-rate:{$category}");
            if($rating == $currentRating){
                $sql = 'SELECT
                          MAX("rating") AS "r",
                          "id"
                        FROM "ads"
                        WHERE
                          :cat_id = any("categories")
                        GROUP BY "id"';

                $statement = $this->pdoActual->prepare($sql);
                $result = $statement->execute(array(':cat_id' => $category));
                $data = $statement->fetch(\PDO::FETCH_ASSOC);
                if($data['r']){
                    RedisIO::set("max-efficiency-rate:{$category}", floatval($data['r']));
                } else {
                    RedisIO::set("max-efficiency-rate:{$category}", 0);
                }
                $rm->updateCategoryRatingsExtremes(floatval($data['r']), [$category], $data['id']);
            }
        }
    }

    public function unPublishCampaign($campaignID, $sdaStatusId = 0, $archivedStatus = [500]){

        $sql = 'SELECT "id", "status" FROM "ads" WHERE "campaign_id" = :campaign_id';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':campaign_id', $campaignID, \PDO::PARAM_INT);
        $result = $statement->execute();
        if(!$result){
            throw new \exceptions\DataLayerException('doesn`t exist campaign ID: '.$campaignID);
        }
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $row){
            if(in_array($row['status'], $archivedStatus))
                continue;
            try{
                $this->unPublishAds($row['id'], $sdaStatusId);
            }catch(\Exception $e){
                if($sdaStatusId == Ads::STATUS_ARCHIVED){
                    $this->archiveAds($row['id'], $sdaStatusId);
                }
            }
        }
        RedisIO::delete("campaign-limit:{$campaignID}");
        RedisIO::delete("campaignId:{$campaignID}");
        return true;
    }

    public function archiveAds($id, $statusId = 500){
        $sql = 'UPDATE "ads"
                    SET
                       "status" = :status
                    WHERE id = :id
                    ';

        $statement = $this->pdoPersistent->prepare($sql);
        return $statement->execute([
            ':id' => $id,
            ':status' => $statusId
        ]);
    }

    public function publishBlock($data){
        $siteIdString = "site:{$data['siteId']}";
        $blockIdString = "block:{$data['id']}";

        RedisIO::set($siteIdString, 1);
        RedisIO::set($blockIdString, serialize([
            'categories' => $data['categories'],
            'id' => $data['id'],
            'siteId' => $data['siteId'],
            'userId' => $data['userId'],
            'siteUrl' => $data['url'],
            'allowShock' => !$data['allowShock'],
            'allowAdult' => !$data['allowAdult'],
            'allowSms' => !$data['allowSms'],
            'allowAnimation' => !$data['allowAnimation'],
            'captionOpacity' => isset($data['captionOpacity']) ? (bool)$data['captionOpacity'] : 1,
            'textOpacity' => isset($data['textOpacity']) ? (bool)$data['textOpacity'] : 1,
            'buttonOpacity' => isset($data['buttonOpacity']) ? (bool)$data['buttonOpacity'] : 1,
            'backgroundOpacity' => isset($data['backgroundOpacity']) ? (bool)$data['backgroundOpacity'] : 1,
            'borderOpacity' => isset($data['borderOpacity']) ? (bool)$data['borderOpacity'] : 1,
            'adsBorderOpacity' => isset($data['adsBorderOpacity']) ? (bool)$data['adsBorderOpacity'] : 1,
            'adsBackOpacity' => isset($data['adsBackOpacity']) ? (bool)$data['adsBackOpacity'] : 1,
            'backHoverOpacity' => isset($data['backHoverOpacity']) ? (bool)$data['backHoverOpacity'] : 1,
            'imgBorderOpacity' => isset($data['imgBorderOpacity']) ? (bool)$data['imgBorderOpacity'] : 1,
            'captionHoverOpacity' => isset($data['captionHoverOpacity']) ? (bool)$data['captionHoverOpacity'] : 1,
            'splitFormat' => isset($data['splitFormat']) ? (string)$data['splitFormat'] : 0,
            'width' => isset($data['width']) ? (string)$data['width'] : '',
            'font' => isset($data['font']) ? (string)$data['font'] : '',
            'adsBackColor' => isset($data['adsBackColor']) ? (string)$data['adsBackColor'] : '',
            'adsBorderColor' => isset($data['adsBorderColor']) ? (string)$data['adsBorderColor'] : '',
            'adsBorder' => isset($data['adsBorder']) ? intval($data['adsBorder']) : 0,
            'adsBorderType' => isset($data['adsBorderType']) ? (string)$data['adsBorderType'] : '',
            'textPosition' => isset($data['textPosition']) ? (string)$data['textPosition'] : '',
            'alignment' => isset($data['alignment']) ? (string)$data['alignment'] : '',
            'indentAds' => isset($data['indentAds']) ? (string)$data['indentAds'] : '',
            'borderType' => isset($data['borderType']) ? (string)$data['borderType'] : '',
            'indentBorder' => isset($data['indentBorder']) ? intval($data['indentBorder']) : 0,
            'imgBorderWidth' => isset($data['imgBorderWidth']) ? intval($data['imgBorderWidth']) : 0,
            'imgBorderType' => isset($data['imgBorderType']) ? (string)$data['imgBorderType'] : '',
            'imgBorderColor' => isset($data['imgBorderColor']) ? (string)$data['imgBorderColor'] : '',
            'imgWidth' => isset($data['imgWidth']) ? (string)$data['imgWidth'] : '',
            'borderRadius' => isset($data['borderRadius']) ? intval($data['borderRadius']) : 0,
            'captionFontSize' => isset($data['captionFontSize']) ? intval($data['captionFontSize']) : 0,
            'captionStyle' => isset($data['captionStyle']) ? (string)$data['captionStyle'] : '',
            'descFontSize' => isset($data['descFontSize']) ? intval($data['descFontSize']) : 12,
            'descStyle' => isset($data['descStyle']) ? (string)$data['descStyle'] : '',
            'useDescription' => isset($data['useDescription']) ? intval($data['useDescription']) : 0,
            'backHoverColor' => isset($data['backHoverColor']) ? (string)$data['backHoverColor'] : '',
            'captionColor' => isset($data['captionColor']) ? (string)$data['captionColor'] : '',
            'textColor' => isset($data['textColor']) ? (string)$data['textColor'] : '',
            'buttonColor' => isset($data['buttonColor']) ? (string)$data['buttonColor'] : '',
            'captionHoverColor' => isset($data['captionHoverColor']) ? (string)$data['captionHoverColor'] : '',
            'captionHoverFontSize' => isset($data['captionHoverFontSize']) ? intval($data['captionHoverFontSize']) : 12,
            'captionHoverStyle' => isset($data['captionHoverStyle']) ? (string)$data['captionHoverStyle'] : '',
            'descLimit' => isset($data['descLimit']) ? intval($data['descLimit']) : 0,
            'border' => isset($data['border']) ? (int)$data['border'] : 0,
            'borderColor' => isset($data['borderColor']) ? (string)$data['borderColor'] : '',
            'backgroundColor' => isset($data['backgroundColor']) ? (string)$data['backgroundColor'] : '',
        ]));

        $showsKey = "block-shows:{$data['id']}";
        RedisIO::set($showsKey, $data['shows'] ? $data['shows'] : 0);

        $clicksKey = "block-clicks:{$data['id']}";
        RedisIO::set($clicksKey, $data['clicks'] ? $data['clicks'] : 0);

        return true;
    }

    public function unPublishBlock($id, $update = true, $statusId){

        $blockIdString = "block:{$id}";
        $showsKey = "block-shows:{$id}";

        $shows = RedisIO::get($showsKey);

        $clicksKey = "block-clicks:{$id}";
        $clicks = RedisIO::get($clicksKey);

        if($update){
            $sql = 'UPDATE "blocks" SET "shows" = :shows, "clicks" = :clicks, "status" = :status WHERE "id" = :id';
            $statement = $this->pdoPersistent->prepare($sql);
            $result = $statement->execute([
                ':shows' => $shows ? $shows : 0,
                ':clicks' => $clicks ? $clicks : 0,
                ':id' => $id,
                ':status' => $statusId
            ]);

            if(!$result)
                throw new DataLayerException($this->parseError($statement->errorInfo()));
        }

        RedisIO::delete($blockIdString);
        RedisIO::delete($showsKey);
        RedisIO::delete($clicksKey);

        return true;
    }
} 