<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 18.05.14
 * Time: 20:48
 */

namespace models\dataSource;

use exceptions\DataLayerException;

class AdsStatsDataSource extends DataSourceLayer {

    public function getAdsAlltimeStats($params = []){
        $sql = 'SELECT
                    ads.id,
                    ads.campaign_id,
                    campaign.description as "camp_desc",
                    json_extract_path_text(ads.content, \'caption\') as "description"
                FROM
                    ads
                    INNER JOIN campaign ON (ads.campaign_id = campaign.id)
                WHERE
                    campaign.user_id = :user_id';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id']
        ]);
    }

    public function getCampaignAlltimeStats($params = []){
        $sql = 'SELECT
                    id,
                    description
                FROM
                    campaign
                WHERE
                    campaign.user_id = :user_id';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id']
        ]);
    }

    public function getAdsStats($params = []) {
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
                    AND date("date") >= date(:start_date) AND date("date") <= date(:end_date)
                GROUP BY json_extract_path_text(ads.content, \'caption\'), advertiser_stats.ads_id, advertiser_stats.campaign_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql, $this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':end_date' => $params['endDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
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
                GROUP BY json_extract_path_text(ads.content, \'caption\'), advertiser_stats.ads_id, advertiser_stats.campaign_id';

        return $this->runQuery($sql, $this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate']
        ]);
    }

    public function getCampaignStats(array $params){
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
                    AND date("date") >= date(:start_date) AND date("date") <= date(:end_date)
                GROUP BY "campaign".description, advertiser_stats.campaign_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':end_date' => $params['endDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
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

    public function countCampaigns($params = []){
        $sql = 'SELECT
                    COUNT(*)
                FROM
                    campaign
                    INNER JOIN advertiser_stats ON (campaign.id = advertiser_stats.campaign_id)
                WHERE
                    advertiser_stats.user_id = :user_id
                    AND date("date") >= date(:start_date) AND date("date") <= date(:end_date)';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':start_date', $params['start_date'], \PDO::PARAM_INT);
        $statement->bindParam(':end_date', $params['end_date'], \PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch();

        return intval($data['count']);
    }

    public function countAds($params = []){
        $sql = 'SELECT
                    COUNT(*) as "count",
                    ads.id as "id"
                FROM
                    ads
                    INNER JOIN advertiser_stats ON (ads.id = advertiser_stats.ads_id)
                WHERE
                    advertiser_stats.user_id = :user_id
                    AND date("date") >= date(:start_date) AND date("date") <= date(:end_date)';

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $params['user_id'], \PDO::PARAM_INT);
        $statement->bindParam(':start_date', $params['start_date'], \PDO::PARAM_INT);
        $statement->bindParam(':end_date', $params['end_date'], \PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch();

        return intval($data['count']);
    }
} 