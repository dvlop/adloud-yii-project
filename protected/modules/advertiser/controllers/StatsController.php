<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 07.07.14
 * Time: 18:17
 */

namespace application\modules\advertiser\controllers;

use application\components\ControllerAdvertiser;
use application\models\Ads;
use application\models\AdvertiserStats;
use application\models\Campaign;

class StatsController extends ControllerAdvertiser
{
    public function actionIndex(){
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/'.$this->action->getId())] = \Yii::t('advertiser_stats', 'Статистика рекламодателя');
        $pageName = \Yii::t('advertiser_stats', 'Статистика рекламодателя');

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
            $type = 'campaign';
        }

        if(!$status) {
            $status = 'actual';
        }

        if(!$startDate) {
            $startDate = $now->modify('-1 week');
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

        $model = AdvertiserStats::model();
        $model->user_id = \Yii::app()->user->id;

        $dateStats = $model->getPeriodStatsByDate($params);

        if($type == 'date'){
            $stats = $dateStats;
            $model->getDetailedDateStats($stats, $params);
        } else {
            $stats = $model->getPeriodStatsByCampaign($params);
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

        $pageName .= ($status == 'archived') ? ' '.\Yii::t('advertiser_stats', '(Архив)') : '';

        $this->render('index', [
            'chartData' => $chartData,
            'stats' => $stats,
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'total' => $total,
            'actionLink' => 'advertiser/stats/campaign',
            'linkParams' => '?status='.$status.'&startDate='.$startDate.'&endDate='.$endDate,
            'pageName' => $pageName,
            'status' => $status
        ]);
    }

    public function actionCampaign($id = null){
        $campaign = Campaign::model()->findByPk($id);

        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/index')] = 'Статистика рекламодателя';
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/campaign', ['id' => $id])] = $campaign->description;

        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $type = 'campaign';
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
            'today' => $containsToday,
            'status' => $status,
            'campaignId' => $id,
            'layer' => 'campaign'
        ];

        $model = AdvertiserStats::model();
        $model->user_id = \Yii::app()->user->id;

        $dateStats = $model->getPeriodCampaignStatsByDate($params);
        $stats = $model->getPeriodCampaignStats($params);

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

        $pageName = $campaign->description;
        $pageName .= ($status == 'archived') ? ' (Архив)' : '';

        $this->render('index', [
            'chartData' => $chartData,
            'stats' => $stats,
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'total' => $total,
            'actionLink' => '/advertiser/stats/ads',
            'linkParams' => '?status='.$status.'&startDate='.$startDate.'&endDate='.$endDate,
            'pageName' => $pageName,
            'status' => $status
        ]);
    }

    public function actionAds($id = null){
        $ads = Ads::model()->findByPk($id);

        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/index')] = 'Статистика рекламодателя';
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/campaign', ['campaignId' => $ads->campaign_id])] = $ads->campaign->description;
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/'.$this->getId().'/ads', ['id' => $id])] = json_decode($ads->content)->caption;

        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $type = 'campaign';
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $status = \Yii::app()->request->getQuery('status');
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

        $model = AdvertiserStats::model();
        $model->user_id = \Yii::app()->user->id;

        $dateStats = $model->getPeriodAdsStatsByDate([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'today' => $containsToday,
            'adsId' => $id,
            'layer' => 'ads'
        ]);

        $pageName = json_decode($ads->content)->caption;
        $pageName .= ($status == 'archived') ? ' (Архив)' : '';

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
            'isAd' => true
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