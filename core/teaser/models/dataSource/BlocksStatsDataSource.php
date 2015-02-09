<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 17.06.14
 * Time: 19:20
 */

namespace models\dataSource;
use config\Config;
use exceptions\DataLayerException;


class BlocksStatsDataSource  extends DataSourceLayer {

    public function getBlocksAlltimeStats($params = []){
        $sql = 'SELECT
                    blocks.id,
                    blocks.site_id,
                    sites.url as "site_desc",
                    blocks.description
                FROM
                    blocks
                    INNER JOIN sites ON (blocks.site_id = sites.id)
                WHERE
                    sites.user_id = :user_id';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id']
        ]);
    }

    public function getSiteAlltimeStats($params = []){
        $sql = 'SELECT
                    id,
                    url as "description"
                FROM
                    sites
                WHERE
                    sites.user_id = :user_id';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id']
        ]);
    }

    public function getAdsInBlockStats(array $params){
        $sql = 'SELECT
                    sum(transactions.amount) as "costs",
                    count(transactions.ads_id) as "clicks",
                    transactions.ads_id
                FROM
                    transactions
                    INNER JOIN "ads" ON (transactions.ads_id = ads.id)
                WHERE
                    transactions.block_id = :block_id
                GROUP BY transactions.ads_id';

        $stats = $this->runQuery($sql,$this->pdoActual, [
            ':block_id' => $params['block_id']
        ]);

        $ads = \core\RedisIO::hGetAll("block-stats:{$params['block_id']}");

        if ($ads) {
            $where = [];
            foreach($ads as $key => $val){
                $where[] = 'id = '.$key;
            }

            $where = implode(' OR ', $where);

            $sqlDescriptions = 'SELECT
                                    id,
                                    json_extract_path_text(ads.content, \'caption\') as "description",
                                    banned_blocks
                                FROM ads
                                WHERE '.$where;

            $descriptions = $this->runQuery($sqlDescriptions,$this->pdoActual, []);
        }

        $result = [];
        foreach($ads as $key => $shows){
            $result[$key] = [
                'id' => $key,
                'shows' => $shows
            ];
            foreach($descriptions as $desc) {
                if ($key == $desc['id']) {
                    $result[$key]['description'] = $desc['description'];
                    $result[$key]['status'] = strpos($desc['banned_blocks'],$params['block_id']) ? 0 : 1;
                }
            }
            foreach($stats as $stat){
                if ($key == $stat['ads_id']) {
                    $result[$key]['clicks'] = $stat['clicks'];
                    $result[$key]['costs'] = $stat['costs'];
                    $result[$key]['ctr'] = round($stat['clicks'] / $shows, 5)*100;
                }
            }
            if (!isset($result[$key]['clicks'])) $result[$key]['clicks'] = 0;
            if (!isset($result[$key]['costs'])) $result[$key]['costs'] = 0;
            if (!isset($result[$key]['ctr'])) $result[$key]['ctr'] = 0;
            if (!isset($result[$key]['status'])) $result[$key]['status'] = 1;
        }

        usort($result, function($a, $b) {
            return $b['ctr'] - $a['ctr'];
        });

        return $result;
    }

    public function getBlocksStats($params = []) {
        $sql = 'SELECT
                    webmaster_stats.block_id as "id",
                    webmaster_stats.site_id,
                    sum(webmaster_stats.shows) as "shows",
                    sum(webmaster_stats.clicks) as "clicks",
                    sum(webmaster_stats.costs) as "costs",
                    blocks.description as "description"
                FROM
                    webmaster_stats
                    INNER JOIN "blocks" ON (webmaster_stats.block_id = blocks.id)
                WHERE
                    webmaster_stats.user_id = :user_id
                    AND date("date") >= date(:start_date) AND date("date") <= date(:end_date)
                GROUP BY blocks.description, webmaster_stats.block_id, webmaster_stats.site_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql, $this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':end_date' => $params['endDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
    }

    public function getBeforeBlocksStats($params = []) {
        $sql = 'SELECT
                    webmaster_stats.block_id as "id",
                    webmaster_stats.site_id,
                    sum(webmaster_stats.shows) as "shows",
                    sum(webmaster_stats.clicks) as "clicks",
                    sum(webmaster_stats.costs) as "costs",
                    blocks.description as "description"
                FROM
                    webmaster_stats
                    INNER JOIN "blocks" ON (webmaster_stats.block_id = blocks.id)
                WHERE
                    webmaster_stats.user_id = :user_id
                    AND  date("date") < date(:start_date)
                GROUP BY blocks.description, webmaster_stats.block_id, webmaster_stats.site_id';

        return $this->runQuery($sql, $this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate']
        ]);
    }

    public function getSiteStats(array $params){
        $sql = 'SELECT
                    webmaster_stats.site_id as "id",
                    sum(shows) as "shows",
                    sum(clicks) as "clicks",
                    sum(costs) as "costs",
                    "sites".url as "description"
                FROM
                    webmaster_stats
                     INNER JOIN "sites"
                        ON ("sites".id = "webmaster_stats"."site_id")
                WHERE
                    webmaster_stats.user_id = :user_id
                    AND date("date") >= date(:start_date) AND date("date") <= date(:end_date)
                GROUP BY "sites".url, webmaster_stats.site_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':end_date' => $params['endDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
    }

    public function getBeforeSiteStats(array $params){
        $sql = 'SELECT
                    webmaster_stats.site_id as "id",
                    sum(shows) as "shows",
                    sum(clicks) as "clicks",
                    sum(costs) as "costs",
                    "sites".url as "description"
                FROM
                    webmaster_stats
                     INNER JOIN "sites"
                        ON ("sites".id = "webmaster_stats"."site_id")
                WHERE
                    webmaster_stats.user_id = :user_id
                    AND date("date") < date(:start_date)
                GROUP BY "sites".url, webmaster_stats.site_id
                LIMIT :limit OFFSET :offset';

        return $this->runQuery($sql,$this->pdoPersistent, [
            ':user_id' => $params['user_id'],
            ':start_date' => $params['startDate'],
            ':limit' => $params['limit'],
            ':offset' => $params['offset']
        ]);
    }

    public function getBlockDescription($id){
        $sql = 'SELECT "description" FROM "blocks" WHERE id= :id';
        $result = $this->runQuery($sql,$this->pdoPersistent, [
            ':id' => $id
        ]);
        return $result;
    }

    public function getSiteDescription($id){
        $sql = 'SELECT "description" FROM "blocks" WHERE id= :id';
        $result = $this->runQuery($sql,$this->pdoPersistent, [
            ':id' => $id
        ]);
        return $result;
    }

} 