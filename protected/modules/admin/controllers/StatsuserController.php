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

class StatsuserController extends ControllerAdmin
{
    public function actionIndex()
    {
        $period = $this->getPeriodParams();
        $users = SystemStatsEntity::getPeriodUsers($period['startDate'],$period['endDate']);
        $stats = SystemStatsEntity::getPeriodUsersByDate($period['startDate'],$period['endDate']);
        $chartsData = $this->array2lineChartData(SystemStatsEntity::arrayDateFill($stats,$period['startDate'],$period['endDate']),'sum');

        $this->render('index', [
            'period' => $period,
            'numbers' => $users['numbers'],
            'tableData' => $users['table'],
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
            $result['labels'][] = '"'.$arr['date'].'"';
        }
        return $result;
    }
}