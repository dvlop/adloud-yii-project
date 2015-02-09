<?php
/**
 * Created by PhpStorm.
 * User: M-A-X
 * Date: 10.07.14
 * Time: 13:50
 */

namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class StatsDataSource extends DataSourceLayer
{
    public function getSites($arParams = [])
    {
        $sql = 'SELECT
                    sites.id,
                    sites.url,
                    coalesce(SUM(WS.shows), 0) as shows,
                    ( CASE
                        WHEN coalesce(SUM(WS.shows), 0) > 0
                            THEN coalesce(SUM(WS.clicks), 0)/coalesce(SUM(WS.shows), 1)*100
                        ELSE 0
                    END ) AS ctr,
                    coalesce(SUM(WS.costs), 0) as costs,
                    (SELECT COUNT (blocks.id) FROM "blocks" WHERE (blocks.site_id = sites.id)) as blocks_count
                FROM "sites"
                LEFT JOIN "webmaster_stats" WS ON (sites.id = WS.site_id)
                WHERE sites.user_id = :user_id
                GROUP BY sites.id';

        $arSort = [
            'id' => 'sites.id',

            'url' => 'sites.url',
            'blocksCount' => 'blocks_count',
            'shows' => 'shows',
            'clicks' => 'clicks',
            'ctr' => 'ctr',
            'costs' => 'costs',
        ];



        $sortBy = 'id';
        if($arParams['sortBy'] && isset($arSort[$arParams['sortBy']]))
        {
            $sortBy = $arSort[$arParams['sortBy']];
        }

        $sortOrder = $arParams['sortOrder'] && in_array($arParams['sortOrder'], array('asc', 'desc')) ? $arParams['sortOrder'] : 'asc';

        $sql.=" ORDER BY $sortBy $sortOrder";

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':user_id', $arParams['userId']);

        $result = $statement->execute();
        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $row['ctr'] = round($row['ctr'], 1);
            $arResult[$row['id']] = json_decode(json_encode($row), FALSE);;
            $arStat[$row['id']] = ['shows' => 0, 'clicks' => 0, 'costs' => 0];
        }

        if(isset($arResult) && is_array($arResult))
        {
            // Получаем ид блоков
            $arSiteIds = array_keys($arResult);

            $sql = "SELECT
        blocks.id, blocks.site_id
        FROM blocks
        JOIN sites ON (sites.id = blocks.site_id)
        WHERE blocks.site_id IN (".implode(',', $arSiteIds).")
        ";

            $statement = $this->pdoPersistent->prepare($sql);
            $result = $statement->execute();
            if (!$result) {
                throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
            }

            while($row = $statement->fetch(PDO::FETCH_ASSOC))
            {
                $blockId = $row['id'];
                $userId = $row['site_id'];

                // Получаем статистику в реальном времени
                $arStat[$userId]['shows'] += RedisIO::get("block-shows:{$blockId}");
                $arStat[$userId]['clicks'] += RedisIO::get("block-clicks:{$blockId}");
                $arStat[$userId]['costs'] += RedisIO::get("block-income:{$blockId}");
            }

            // Обновляем статистику
            foreach($arResult as $siteId => $userInfo)
            {
                $siteStat = $arStat[$siteId];

                $ctr = $siteStat['shows'] ? round($siteStat['clicks'] / $siteStat['shows'] * 100, 1) : 0;

                $userInfo->clicks = $siteStat['clicks'];
                $userInfo->shows = $siteStat['shows'];
                $userInfo->ctr = $ctr;
                $userInfo->costs = $siteStat['costs'];

                $arResult[$siteId] = $userInfo;
            }

            return $arResult;
        }

    }

    public function getBlocks($arParams = [])
    {
        // Получаем информацию о пользователях
        $sql = 'SELECT
        blocks.id, blocks.description,
        coalesce(SUM(WS.shows), 0) as shows, coalesce(SUM(WS.clicks), 0) as clicks, coalesce(SUM(WS.clicks), 0) / coalesce(SUM(WS.shows), 1) * 100 as ctr , coalesce(SUM(WS.costs), 0) as costs

          FROM "blocks"
        LEFT JOIN "webmaster_stats" WS ON (blocks.id = WS.block_id)
        WHERE blocks.site_id = :site_id
        GROUP BY blocks.id
        ';

        $arSort = [
            'id' => 'blocks.id',

            'description' => 'blocks.description',
            'shows' => 'shows',
            'clicks' => 'clicks',
            'ctr' => 'ctr',
            'costs' => 'costs',
        ];



        $sortBy = 'id';
        if($arParams['sortBy'] && isset($arSort[$arParams['sortBy']]))
        {
            $sortBy = $arSort[$arParams['sortBy']];
        }

        $sortOrder = $arParams['sortOrder'] && in_array($arParams['sortOrder'], array('asc', 'desc')) ? $arParams['sortOrder'] : 'asc';

        $sql.=" ORDER BY $sortBy $sortOrder";

        $statement = $this->pdoPersistent->prepare($sql);
        $statement->bindParam(':site_id', $arParams['siteId']);

        $result = $statement->execute();
        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $row['ctr'] = round($row['ctr'], 1);
            $arResult[$row['id']] = json_decode(json_encode($row), FALSE);;
            $arStat[$row['id']] = ['shows' => 0, 'clicks' => 0, 'costs' => 0];
        }

        if(isset($arResult) && is_array($arResult))
        {
            // Получаем ид блоков
            $arBlockIds = array_keys($arResult);

            foreach($arBlockIds as $blockId)
            {
                // Получаем статистику в реальном времени
                $arStat[$blockId]['shows'] += RedisIO::get("block-shows:{$blockId}");
                $arStat[$blockId]['clicks'] += RedisIO::get("block-clicks:{$blockId}");
                $arStat[$blockId]['costs'] += RedisIO::get("block-income:{$blockId}");
            }

            // Обновляем статистику


            return $arResult;
        }

    }

}