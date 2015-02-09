<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 18.09.14
 * Time: 12:17
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\entities\SystemStatsEntity;

class StatsfinanceController extends ControllerAdmin
{
    public function actionIndex()
    {
        $period = $this->getPeriodParams();
        $periodIncome = SystemStatsEntity::arrayDateFill(SystemStatsEntity::getIncomeByDate($period['startDate'],$period['endDate']),$period['startDate'],$period['endDate']);
        $periodMoneyIn = SystemStatsEntity::arrayDateFill(SystemStatsEntity::getMoneyInByDate($period['startDate'],$period['endDate']),$period['startDate'],$period['endDate']);
        $periodMoneyOut = SystemStatsEntity::arrayDateFill(SystemStatsEntity::getMoneyOutByDate($period['startDate'],$period['endDate']),$period['startDate'],$period['endDate']);

        $chartsData = [
            'income' => $this->array2lineChartData($periodIncome,'sum'),
            'moneyIn' => $this->array2lineChartData($periodMoneyIn,'sum'),
            'moneyOut' => $this->array2lineChartData($periodMoneyOut,'sum')
        ];
        $numbers = [
            'income' => $this->getSum($periodIncome),
            'moneyIn' => $this->getSum($periodMoneyIn),
            'moneyOut' => $this->getSum($periodMoneyOut)
        ];
        $period['endDate'] = (new \DateTime($period['endDate']))->modify('-1 day')->format(\Yii::app()->params->dateFormat);

        $this->render('index', [
            'numbers' => $numbers,
            'chartsData' => $chartsData,
            'period' => $period
        ]);
    }

    private function getPeriodParams(){
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $today = new \DateTime();
        $weekAgo = $today->modify('-1 week');
        $weekAgo = $weekAgo->format(\Yii::app()->params['dateFormat']);
        $today = $today->modify('+8 day')->format(\Yii::app()->params['dateFormat']);

        if($endDate)
            $endDate = (new \DateTime($endDate))->modify('+1 day')->format(\Yii::app()->params['dateFormat']);

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

    private function getSum($array){
        $sum = 0;
        foreach($array as $arr){
            $sum += $arr['sum'];
        }
        return $sum;
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