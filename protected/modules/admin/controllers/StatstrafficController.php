<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 18.09.14
 * Time: 12:19
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\entities\SystemStatsEntity;

class StatstrafficController extends ControllerAdmin
{
    public function actionIndex()
    {
        $period = $this->getPeriodParams();
        $traffic = SystemStatsEntity::getPeriodTotalTraffic($period['startDate'],$period['endDate']);
        $numbers = [
            'shows' => $traffic['shows'],
            'clicks' => $traffic['clicks'],
            'ctr' => $traffic['shows'] ? round(($traffic['clicks']/$traffic['shows'])*100,4) : 0
        ];
        $statData = SystemStatsEntity::getPeriodTotalTrafficByDate($period['startDate'],$period['endDate']);
        $chartsData = [
            'shows' => $this->array2lineChartData($statData,'shows'),
            'clicks' => $this->array2lineChartData($statData,'clicks'),
            'labels' => $this->getLabelsFromKeys($statData)
        ];

        $this->render('index', [
            'period' => $period,
            'numbers' => $numbers,
            'statData' => $statData,
            'chartsData' => $chartsData
        ]);
    }

    private function getPeriodParams(){
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $today = new \DateTime();
        $weekAgo = $today->modify('-1 week');
        $weekAgo = $weekAgo->format(\Yii::app()->params['dateFormat']);
        $today = $today->modify('+7 day')->format(\Yii::app()->params['dateFormat']);

        if(!$startDate) {
            $startDate = $weekAgo;
            $endDate = $today;
        }

        if(!$endDate) {
            $endDate = $startDate;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

    private function array2lineChartData(array $array, $dataKey){
        $result = [];
        foreach($array as $arr){
            $result['data'][] = $arr[$dataKey];
            $result['labels'][] = '"'.$arr[$dataKey].'"';
        }
        return $result;
    }

    private function getLabelsFromKeys(array $array){
        $result = [];
        foreach($array as $key => $val){
            $result[] = '"'.$key.'"';
        }
        return $result;
    }
}