<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 18.09.14
 * Time: 12:16
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\Categories;
use application\models\entities\SystemStatsEntity;
use application\models\Sites;

class StatscategoryController extends ControllerAdmin
{
    public function actionIndex()
    {
        $period = $this->getPeriodParams();
        $tableData = SystemStatsEntity::getPeriodCategoryData($period['startDate'],$period['endDate']);
        $chartsData = $this->array2PieData($tableData,'systemIncome','name');

        $this->render('index', [
            'period' => $period,
            'tableData' => $tableData,
            'chartsData' => $chartsData
        ]);
    }

    public function actionCategory($id = null)
    {
        if(!$id)
            $id = 1;
        $period = $this->getPeriodParams();
        $categoryName = Categories::model()->findByAttributes(['id' => $id])->name;
        $tableData = SystemStatsEntity::getPeriodSiteData($id,$period['startDate'],$period['endDate']);
        usort($tableData,function($a,$b){
            return $a['systemIncome'] < $b['systemIncome'];
        });
        $chartsData = array_slice($this->array2PieData($tableData,'systemIncome','url'),0,12);

        $this->render('category', [
            'period' => $period,
            'categoryName' => $categoryName,
            'tableData' => $tableData,
            'chartsData' => $chartsData
        ]);
    }

    public function actionSite($id = null)
    {
        if($id){
            $period = $this->getPeriodParams();
            $url = Sites::model()->findByAttributes(['id' => $id])->url;
            $tableData = SystemStatsEntity::getPeriodBlockData($id,$period['startDate'],$period['endDate']);
            usort($tableData,function($a,$b){
                return $a['income'] < $b['income'];
            });
            $chartsData = array_slice($this->array2PieData($tableData,'income','description'),0,12);

            $this->render('site', [
                'period' => $period,
                'url' => $url,
                'tableData' => $tableData,
                'chartsData' => $chartsData
            ]);
        }
    }

    private function getPeriodParams(){
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $today = new \DateTime();
        $weekAgo = $today->modify('-1 week');
        $weekAgo = $weekAgo->format(\Yii::app()->params['dateFormat']);
        $today = $today->modify('+1 week')->format(\Yii::app()->params['dateFormat']);

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

    private function getSumByCategory(array $categories){
        $result = [];
        foreach($categories as $key => $cat){
            if($key){
                $result[$key]['shows'] = 0;
                $result[$key]['clicks'] = 0;

                foreach($cat as $date){
                    $result[$key]['id'] = $date['id'];
                    $result[$key]['shows'] += $date['shows'];
                    $result[$key]['clicks'] += $date['clicks'];
                }
            }
        }
        return $result;
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

    private function array2PieData(array $array, $value, $label){
        $result = [];
        foreach($array as $key => $arr){
            $result[] = json_encode([
                'value' => $arr[$value],
                'label' => $arr[$label]
            ]);
        }
        return $result;
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