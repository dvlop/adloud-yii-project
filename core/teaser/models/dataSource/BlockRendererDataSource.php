<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 16.02.14
 * Time: 23:00
 */

namespace models\dataSource;


use core\RatingManager;
use core\RedisIO;
use models\Publisher;

class BlockRendererDataSource extends DataSourceLayer{

    const GEO_SQL_PART = '("geo_countries" IS NULL AND "geo_regions" IS NULL) OR (:country = ANY("geo_countries") OR :region = ANY("geo_regions"))';
    const LIST_SQL_PART = '("white_list" IS NULL OR :site_id = ANY("white_list")) AND ("black_list" IS NULL OR :site_id != ALL("black_list"))';

    private static $_adsTypes = [
        '160x600' => 2,
        '200x300' => 1,
        '200x600' => 2,
        '240x400' => 3,
        '240x4400' => 15,
        '240x600' => 2,
        '300x250' => 2,
        '300x600' => 4,
        '728x90' => 2,
        '336x280' => 2,
        '468x60' => 1,
        '600x300' => 3,
        '640x300' => 4,
    ];

    private $_geoIpClass;
    private $_countryCode;
    private $_regionCode;
    private $_ip;

    public function getBlockInfo($id){
        $blockKey = "block:{$id}";

        $data = RedisIO::get($blockKey);

        if(!$data){
            return false;
        }

        return unserialize($data);
    }

    public function runAuction($params, $blockData, RatingManager $rm, $isPreview = false){
        $where = '';
        $queryParams = [];
        $noRatingWhere = '';
        $noRatingQueryParams = [];
        $whereCat = [];
        $noRatedWhereCat = [];

        foreach($blockData['categories'] as $i=>$catId){
            $whereCat[] = "(:category_{$i} = ANY(\"categories\") AND \"rating\" > :rating_{$i})";
            $noRatedWhereCat[] = "(:category_{$i} = ANY(\"categories\"))";

            $queryParams[':category_'.$i] = intval($catId);
            $noRatingQueryParams[':category_'.$i] = intval($catId);
            $queryParams[':rating_'.$i] = $rm->getRandomEfficiencyRate($catId) - 0.00001;
        }

        $where .= '( '.implode(' OR ', $whereCat).' )';
        $noRatingWhere .= ' ( '.implode(' OR ', $noRatedWhereCat).' )';
        $where .= ' AND ( '.self::LIST_SQL_PART.' )';
        $noRatingWhere .= ' AND ( '.self::LIST_SQL_PART.' )';

        $queryParams[':site_id'] = $blockData['siteId'];
        $noRatingQueryParams[':site_id'] = $blockData['siteId'];

        if(isset($blockData['allowShock']) && !$blockData['allowShock']){
            $where .= ' AND ( "shock" = \'t\' )';
            $noRatingWhere .= ' AND ( "shock" = \'t\' )';
        }
        if(isset($blockData['allowAdult']) && !$blockData['allowAdult']){
            $where .= ' AND ( "adult" IS NULL OR "adult" != \'t\' )';
            $noRatingWhere .= ' AND ( "adult" IS NULL OR "adult" != \'t\' )';
        }
        if(isset($blockData['allowSms']) && !$blockData['allowSms']){
            $where .= ' AND ( "sms" IS NULL OR "sms" != \'t\' )';
            $noRatingWhere .= ' AND ( "sms" IS NULL OR "sms" != \'t\' )';
        }
        if(isset($blockData['allowAnimation']) && !$blockData['allowAnimation']){
            $where .= ' AND ( "animation" IS NULL OR "animation" != \'t\' )';
            $noRatingWhere .= ' AND ( "animation" IS NULL OR "animation" != \'t\' )';
        }

        if(!$isPreview && $this->getCountyCode()){
            $where .= ' AND ( '.self::GEO_SQL_PART.' )';
            $noRatingWhere .= ' AND ( '.self::GEO_SQL_PART.' )';
            $noRatingQueryParams[':country'] = $queryParams[':country'] = $this->getCountyCode();
            $noRatingQueryParams[':region'] = $queryParams[':region'] = $this->getRegionCode();
        }
        if(isset($params['device'])){
            $where .= ' AND
            (
                (ua_device IS NULL AND ua_device_model IS NULL)
                OR (ua_device = \'{}\' AND ua_device_model = \'{}\')
                OR (:device = ANY(ua_device) OR (SELECT id FROM user_agent WHERE value = :deviceModel AND name = :device AND is_checked IS TRUE) = ANY(ua_device_model))
            )
            AND
            (
                (ua_os IS NULL AND ua_os_ver IS NULL)
                OR (ua_os = \'{}\' AND ua_os_ver = \'{}\')
                OR (:os = ANY(ua_os) OR (SELECT id FROM user_agent WHERE value = :osVer AND name = :os AND is_checked IS TRUE) = ANY(ua_os_ver))
            )
            AND
            (
                (ua_browser IS NULL)
                OR (ua_browser = \'{}\')
                OR (:browser = ANY(ua_browser))
            )';

            $noRatingWhere .= ' AND
            (
                (ua_device IS NULL AND ua_device_model IS NULL)
                OR (ua_device = \'{}\' AND ua_device_model = \'{}\')
                OR (:device = ANY(ua_device) OR (SELECT id FROM user_agent WHERE value = :deviceModel AND name = :device AND is_checked IS TRUE) = ANY(ua_device_model))
            )
            AND
            (
                (ua_os IS NULL AND ua_os_ver IS NULL)
                OR (ua_os = \'{}\' AND ua_os_ver = \'{}\')
                OR (:os = ANY(ua_os) OR (SELECT id FROM user_agent WHERE value = :osVer AND name = :os AND is_checked IS TRUE) = ANY(ua_os_ver))
            )
            AND
            (
                (ua_browser IS NULL)
                OR (ua_browser = \'{}\')
                OR (:browser = ANY(ua_browser))
            )';

            $noRatingQueryParams[':device'] = $queryParams[':device'] = $params['device'];
            $noRatingQueryParams[':deviceModel'] = $queryParams[':deviceModel'] = $params['deviceModel'];
            $noRatingQueryParams[':os'] = $queryParams[':os'] = $params['os'];
            $noRatingQueryParams[':osVer'] = $queryParams[':osVer'] = $params['osVer'];
            $noRatingQueryParams[':browser'] = $queryParams[':browser'] = $params['browser'];
        }

        if(!isset($params['targets']) || !$params['targets'])
            $params['targets'] = 0;
        if(isset($params['targets'])){
            $targets = '{'.str_replace(' ','',$params['targets']).'}';

            $where .= ' AND
            (
                (targets IS NULL)
                OR (targets = \'{}\')
                OR (:targets && targets)
            )';
            $noRatingWhere .= ' AND
            (
                (targets IS NULL)
                OR (targets = \'{}\')
                OR (:targets && targets)
            )';
            $noRatingQueryParams[':targets'] = $queryParams[':targets'] = $targets;
        }

        $where .= ' AND ("banned_blocks" IS NULL OR :block_id != ALL("banned_blocks"))';
        $noRatingWhere .= ' AND ("banned_blocks" IS NULL OR :block_id != ALL("banned_blocks"))';
        $noRatingQueryParams[':block_id'] = $queryParams[':block_id'] = $blockData['id'];

        $sql = "SELECT * FROM \"ads\" WHERE {$where} ORDER BY random() LIMIT :ads_num";//todo: something faster then random()

        if(!isset($params['adsNum'])){
            if(isset(self::$_adsTypes[$params['type']])){
                $adsNum = isset($params['num']) ? $params['num'] : self::$_adsTypes[$params['type']];
            }else{
                $adsNum = 1;
            }
        }else{
            $adsNum = $params['adsNum'];
        }

        $queryParams[':ads_num'] = $adsNum;

        $statement = $this->pdoActual->prepare($sql);
        $result = $statement->execute($queryParams);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if(!$result){
            return false;
        }
        if(count($data) < $adsNum){
            $inArray = [];

            foreach($data as $ad){
                $inArray[] = $ad['id'];
            }

            if($inArray){
                $adsIds = implode(',', $inArray);
                $noRatingWhere .= " AND ( \"id\" NOT IN ({$adsIds}) )";
            }

            $newAds = $this->getAdditionalAds($noRatingWhere, $noRatingQueryParams, $adsNum - count($data));
            $data = array_merge($newAds, $data);
        }

        foreach($data as $key=> $row){
            $data[$key] = $this->prepareRow($row);
        }
        if(!isset($params['noUpdate']) || !$params['noUpdate'])
            $this->updateAdsShows($data);

        return $data;
    }

    private function getAdditionalAds($where, $queryParams, $adsNum){

        $sql = "SELECT * FROM \"ads\" WHERE {$where} ORDER BY random() LIMIT :ads_num";//todo: something faster then random()

        $queryParams[':ads_num'] = $adsNum;
        $statement = $this->pdoActual->prepare($sql);
        $statement->execute($queryParams);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $data;
    }

    private function prepareRow($row){
        $row['content'] = json_decode($row['content'], 1);
        $row['categories'] = $this->parseArrayFromDatabaseString($row['categories']);
        $row['geo_countries'] = $this->parseArrayFromDatabaseString($row['geo_countries']);
        $row['geo_regions'] = $this->parseArrayFromDatabaseString($row['geo_regions']);

        $row['ua_device'] = $this->parseArrayFromDatabaseString($row['ua_device']);
        $row['ua_device_model'] = $this->parseArrayFromDatabaseString($row['ua_device_model']);
        $row['ua_os'] = $this->parseArrayFromDatabaseString($row['ua_os']);
        $row['ua_os_ver'] = $this->parseArrayFromDatabaseString($row['ua_os_ver']);
        $row['ua_browser'] = $this->parseArrayFromDatabaseString($row['ua_browser']);

        return $row;
    }

    public function updateBlockShows($blockId){
        $key = "block-shows:{$blockId}";
        RedisIO::incr($key);
    }

    public function updateAdsShows(array $data){
        foreach($data as $ads){
            $adsShows = RedisIO::incr("ads-shows:{$ads['id']}");
            $adsClicks = RedisIO::get("ads-clicks:{$ads['id']}");

            if($adsShows % 100 == 0 && $adsClicks > 0){
                $publisher = Publisher::getInstance();
                $ctr = $adsShows ? ($adsClicks/$adsShows) : 0;
                if($ctr > 0.2){
                    $publisher->unPublishAds($ads['id']);
                    return;
                }
                $this->refreshAdsCtr($ads, $ctr);
            }
        }
    }

    private  function refreshAdsCtr($ads, $ctr){
        $rm = new RatingManager();
        $rate = $rm->getAdsEfficiencyRate($ads['click_price'], $ctr);
        $rm->setAdsRating($rate, $ads['id']);
        //$rm->setCategoriesEfficiencyRate($rate, $ads['categories']);
        $rm->updateCategoryRatingsExtremes($rate, $ads['categories'], $ads['id']);
    }

    private function getCountyCode()
    {
        if($this->_countryCode === null ){
            $this->_countryCode = $this->getGeoIp()->countyCodeByIp($this->getIp());
        }
        return $this->_countryCode;
    }

    private function getRegionCode()
    {
        if($this->_regionCode === null ){
            $this->_regionCode = $this->getGeoIp()->regionCodeByIp($this->getIp());
        }
        return $this->_regionCode;
    }

    public function getTypes()
    {
        return array_keys(self::$_adsTypes);
    }

    private function getGeoIp()
    {
        if($this->_geoIpClass === null){
            require(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'geoIp'.DIRECTORY_SEPARATOR.'GeoIpClass.php');
            $this->_geoIpClass = new \GeoIpClass();
        }

        return $this->_geoIpClass;
    }

    private function getIp()
    {
        if($this->_ip === null){
            if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $this->_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $this->_ip = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? '77.120.242.128' : $_SERVER['REMOTE_ADDR'];
            }
        }
        return $this->_ip;
    }
}