<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 07.07.14
 * Time: 22:27
 */

namespace application\modules\webmaster\controllers;

use application\components\ControllerWebmaster;
use application\models\Blocks;
use application\models\WebmasterStats;
use application\models\Sites;

class StatsController extends ControllerWebmaster
{
    public function actionIndex(){
        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/'.$this->getId().'/'.$this->action->getId())] = \Yii::t('webmaster_stats', 'Статистика вебмастера');

        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $type = \Yii::app()->request->getQuery('type');
        $status = \Yii::app()->request->getQuery('status');
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $containsToday = false;
        $chartData = [
            'label' => [],
            'shows' => [],
            'clicks' => []
        ];

        $this->notAllowedDatesBefore($startDate,$endDate);

        $total = [
            'shows' => 0,
            'clicks' => 0,
            'costs' => 0
        ];

        $now = new \DateTime();
        $today = $now->format($format);

        if(!$type) {
            $type = 'sites';
        }

        if(!$status) {
            $status = 'actual';
        }

        if(!$startDate) {
            $startDate = $now->modify('-1 week');;
            $startDate = $startDate->format($format);
            $endDate = $today;
        }

        if(!$endDate) {
            $endDate = $startDate;
        }

        if($startDate == $today || $endDate == $today){
            $containsToday = true;
        }

        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'today' => $containsToday,
            'status' => $status
        ];

        $model = WebmasterStats::model();
        $model->user_id = \Yii::app()->user->id;

        $dateStats = $model->getPeriodStatsByDate($params);

        if($type == 'date'){
            $stats = $dateStats;
            $model->getDetailedDateStats($stats, $params);
        } else {
            $stats = $model->getPeriodStatsBySite($params);
        }

        foreach($dateStats as $key => $val){
            $chartData['label'][] = $val['date'];
            $chartData['shows'][] = $val['shows'];
            $chartData['clicks'][] = $val['clicks'];
        }

        if(count($chartData['label']) == 1) {
            $chartData['label'][] = $chartData['label'][0];
            $chartData['shows'][] = $chartData['shows'][0];
            $chartData['clicks'][] = $chartData['clicks'][0];
        }

        $pageName = 'Статистика вебмастера';
        $pageName .= ($status == 'archived') ? ' '.\Yii::t('webmaster_stats','(Архив)') : '';

        $this->render('index', [
            'chartData' => $chartData,
            'stats' => $stats,
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'total' => $total,
            'actionLink' => 'webmaster/stats/sites',
            'linkParams' => '?status='.$status.'&startDate='.$startDate.'&endDate='.$endDate,
            'pageName' => \Yii::t('webmaster_stats',$pageName),
            'status' => $status
        ]);
    }

    public function actionSites($id = null){
        $site = Sites::model()->findByPk($id);

        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/'.$this->getId().'/index')] = \Yii::t('webmaster_stats', 'Статистика вебмастера');
        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/'.$this->getId().'/sites', ['id' => $id])] = $site->url;

        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $type = 'sites';
        $status = \Yii::app()->request->getQuery('status');
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $containsToday = false;
        $chartData = [];

        $this->notAllowedDatesBefore($startDate,$endDate);

        $total = [
            'shows' => 0,
            'clicks' => 0,
            'costs' => 0
        ];

        $now = new \DateTime();
        $today = $now->format($format);

        if(!$startDate) {
            $startDate = $now->modify('-1 week');;
            $startDate = $startDate->format($format);
            $endDate = $today;
        }

        if(!$endDate) {
            $endDate = $startDate;
        }

        if($startDate == $today || $endDate == $today){
            $containsToday = true;
        }

        if(!$status){
            $status = 'actual';
        }

        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'today' => $containsToday,
            'siteId' => $id,
            'layer' => 'sites',
        ];

        $model = WebmasterStats::model();
        $model->user_id = \Yii::app()->user->id;

        $dateStats = $model->getPeriodSiteStatsByDate($params);
        $stats = $model->getPeriodSiteStats($params);

        foreach($dateStats as $key => $val){
            $chartData['label'][] = $val['date'];
            $chartData['shows'][] = $val['shows'];
            $chartData['clicks'][] = $val['clicks'];
        }

        if(!$chartData){
            $chartData = [
                'label' => [],
                'shows' => [],
                'clicks' => [],
            ];
        }

        if(count($chartData['label']) == 1) {
            $chartData['label'][] = $chartData['label'][0];
            $chartData['shows'][] = $chartData['shows'][0];
            $chartData['clicks'][] = $chartData['clicks'][0];
        }

        $pageName = $site->url;
        $pageName .= ($status == 'archived') ? ' '.\Yii::t('webmaster_stats','(Архив)') : '';

        $this->render('index', [
            'chartData' => $chartData,
            'stats' => $stats,
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'total' => $total,
            'actionLink' => '/webmaster/stats/blocks',
            'linkParams' => '?status='.$status.'&startDate='.$startDate.'&endDate='.$endDate,
            'pageName' => $pageName,
            'status' => $status
        ]);
    }

    public function actionBlocks($id = null){
        $blocks = Blocks::model()->findByPk($id);

        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/index')] = \Yii::t('webmaster_stats', 'Статистика вебмастера');
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/sites/id', ['id' => $blocks->site_id])] = $blocks->site->url;
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/blocks/id', ['id' => $id])] = $blocks->description;

        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $type = 'sites';
        $status = \Yii::app()->request->getQuery('status');
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $containsToday = false;
        $chartData = [];

        $this->notAllowedDatesBefore($startDate,$endDate);

        $now = new \DateTime();
        $today = $now->format($format);

        if(!$startDate) {
            $startDate = $now->modify('-1 week');;
            $startDate = $startDate->format($format);
            $endDate = $today;
        }

        if(!$endDate) {
            $endDate = $startDate;
        }

        if($startDate == $today || $endDate == $today){
            $containsToday = true;
        }

        $model = WebmasterStats::model();
        $model->user_id = \Yii::app()->user->id;

        $dateStats = $model->getPeriodBlocksStatsByDate([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'today' => $containsToday,
            'blockId' => $id,
            'layer' => 'blocks'
        ]);

        $pageName = $blocks->description;
        $pageName .= ($status == 'archived') ? ' '.\Yii::t('webmaster_stats','(Архив)') : '';

        foreach($dateStats as $key => $val){
            $chartData['label'][] = $val['date'];
            $chartData['shows'][] = $val['shows'];
            $chartData['clicks'][] = $val['clicks'];
        }

        if(count($chartData['label']) == 1) {
            $chartData['label'][] = $chartData['label'][0];
            $chartData['shows'][] = $chartData['shows'][0];
            $chartData['clicks'][] = $chartData['clicks'][0];
        }

        $this->render('index', [
            'chartData' => $chartData,
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'pageName' => $pageName,
            'isBlock' => true
        ]);
    }

    private function notAllowedDatesBefore(&$startDate, &$endDate){
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);

        $rim = new \DateTime('2014-08-08');

        if($start < $rim)
            $startDate = '2014-08-08';

        if($end < $rim)
            $endDate = '2014-08-08';
    }
}