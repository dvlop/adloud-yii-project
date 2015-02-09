<?php
/**
 * Created by PhpStorm.
 * User: M-A-X
 * Date: 10.07.14
 * Time: 13:50
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\Categories;
use application\models\entities\SystemStatsEntity;
use application\models\Users;
use models\Block;
use core\RedisIO;
use models\BlockRenderer;
use models\MoneyPayouts;

class StatsController extends ControllerAdmin
{
    public function actionFormats($blockId)
    {
        $formats = [];
        $block = new \stdClass();

        $block->id = '';
        $block->name = '';

        try{
            $model = Block::getInstance();

            $blockSizes = BlockRenderer::getInstance()->getTypes();

            if($model->initById($blockId, true)){
                $id = $model->getId();
                $block->id = $id;
                $block->name = $model->description;

                $round = \Yii::app()->params['statsRound'];

                foreach(Block::getAllFormats() as $format){
                    $shows = 0;
                    $clicks = 0;

                    foreach($blockSizes as $size){
                        $shows += RedisIO::get("block-type-shows:{$format}_{$size}_{$id}");
                        $clicks += RedisIO::get("block-type-clicks:{$format}_{$size}_{$id}");
                    }

                    $formats[] = (object)[
                        'name' => $format,
                        'shows' => $shows ? $shows : 0,
                        'clicks' => $clicks ? $clicks : 0,
                        'ctr' => $shows > 0 ? round(($clicks/$shows)*100, $round) : 0,
                        'status' => in_array($format, Block::getFormats()),
                    ];
                }
            }else{
                \Yii::app()->user->setFlash('error', 'Блок с ID '.$blockId.' не найден в системе');
            }
        }catch(\Exception $e){
            \Yii::app()->user->setFlash('error', $e->getMessage());
        }

        $this->render('formats', [
            'formats' => $formats,
            'block' => $block
        ]);
    }

    public function actionSites($userId)
    {
        if(!$userId)
            $userId = 1;
        $period = $this->getPeriodParams();
        $user = Users::model()->findByAttributes(['id' => $userId]);
        $tableData = SystemStatsEntity::getPeriodSiteDataByUser($userId,$period['startDate'],$period['endDate']);
        usort($tableData,function($a,$b){
            return $a['systemIncome'] < $b['systemIncome'];
        });
        $chartsData = array_slice($this->array2PieData($tableData,'income','url'),0,12);

        $this->render('sites', [
            'period' => $period,
            'user' => $user,
            'tableData' => $tableData,
            'chartsData' => $chartsData
        ]);
    }

    public function actionBlocks($blockId)
    {
        $model = \models\BlocksStats::getInstance();

        $ads = $model->getAdsInBlockStats([
            'block_id' => $blockId
        ]);

        $this->render('blocks', [
            'ads' => $ads,
            'blockId' => $blockId
        ]);
    }

    public function actionTransactions($recipientId = null, $startDate = null, $endDate = null, $senderId = null, $blockId = null, $adsId = null, $siteId = null, $orderBy = '', $direction = ''){
        $stats = MoneyPayouts::getInstance()->getTransactionStats($recipientId, $startDate, $endDate, $senderId, $blockId, $adsId, $siteId, $orderBy, $direction);
        $this->render('transactions', ['stats' => $stats]);
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
}