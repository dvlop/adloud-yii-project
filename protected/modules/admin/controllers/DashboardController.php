<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 18.09.14
 * Time: 11:38
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\entities\SystemStatsEntity;

class DashboardController extends ControllerAdmin
{
    public function actionIndex()
    {
        $todayTraffic = SystemStatsEntity::getTraffic('today');
        $allTimeTraffic = SystemStatsEntity::getTraffic();

        $todaySiteTraffic = SystemStatsEntity::getTrafficBySite('today');
        $allTimeSiteTraffic = SystemStatsEntity::getTrafficBySite();

        $numbers = [
            'today' => [
                'shows' => $todayTraffic['total']['shows'],
                'clicks' => $todayTraffic['total']['clicks'],
                'ctr' => $todayTraffic['total']['shows'] ? round(($todayTraffic['total']['clicks']/$todayTraffic['total']['shows'])*100,4) : 0,
                'income' => SystemStatsEntity::getIncome('today'),
                'moneyIn' => SystemStatsEntity::getMoneyIn('today'),
                'moneyOut' => SystemStatsEntity::getMoneyOut('today'),
                'users' => SystemStatsEntity::getUsersCount('today'),
                'sites' => SystemStatsEntity::getSitesCount('today'),
                'ads' => SystemStatsEntity::getAdsCount('today')
            ],
            'allTime' => [
                'income' => SystemStatsEntity::getIncome(),
                'moneyIn' => SystemStatsEntity::getMoneyIn(),
                'moneyOut' => SystemStatsEntity::getMoneyOut(),
                'users' => SystemStatsEntity::getUsersCount(),
                'sites' => SystemStatsEntity::getSitesCount(),
                'ads' => SystemStatsEntity::getAdsCount()
            ]
        ];

        $chartsData = [
            'category' => [
                'today' => $this->array2chartData($todayTraffic['byCategory']),
                'allTime' => $this->array2chartData($allTimeTraffic['byCategory'])
            ],
            'site' => [
                'today' => $this->array2chartData($todaySiteTraffic),
                'allTime' => $this->array2chartData($allTimeSiteTraffic)
            ],
        ];

        $this->render('index', [
            'numbers' => $numbers,
            'chartsData' => $chartsData
        ]);
    }

    private function array2chartData(array $array){
        $result = [];
        foreach($array as $key => $arr){
            $result[] = json_encode([
                'value' => $arr['shows'],
                'label' => $key
            ]);
        }
        return $result;
    }
}