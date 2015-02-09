<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 07.07.14
 * Time: 22:09
 */

namespace application\modules\webmaster\controllers;

use application\components\ControllerWebmaster;
use application\models\WebmasterStats;
use application\models\Blocks;
use core\Session;
use models\Block;
use models\Site;

class BlockController extends ControllerWebmaster
{
    public function actionIndex($siteId, $id = null)
    {
        $this->pageName = 'Creative Blocks';
        $this->pageName .= $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.webmaster.partials._pageNameDetails', null, true);

        $siteModel = \models\Site::getInstance();
        $siteModel->initById($siteId);

        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId])] = \Yii::t('webmaster_block', 'Площадка').' '.$siteModel->url;

        $model = new \BlockForm();

        if(\Yii::app()->getRequest()->getIsPostRequest() && isset($_POST['BlockForm'])){
            $model->setAttributes($_POST['BlockForm']);
            $model->type = Block::BLOCK_LIGHT_FORMAT;

            if($model->validate()){
                if($blockId = $model->block($siteId, $id)){
                    $this->redirect(\Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId]));
                }else{
                    $this->setFlash($model->errors, \Yii::t('webmaster_block', 'Произошла ошибка'), \Yii::t('webmaster_block', 'Попробуйте еще раз.'));
                    $this->redirect(['webmaster/site/list']);
                }
            }
        }

        if($id){
            $block = \models\Block::getInstance();
            $block->initById($id);

            $model->id = $block->getId();
            $model->siteId = $block->siteId;
            $model->description = $block->description;
            $model->size = $block->size;
            $model->color = $block->color;
            $model->bg = $block->bg;
            $model->status = $block->status;
        }

        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId])] = \Yii::t('webmaster_block', 'Площадка').' '.$siteModel->url;
        $this->breadcrumbs[\Yii::app()->createUrl($this->id.'/'.$this->action->id)] = $id ? ($model->description ? $model->description : \Yii::t('webmaster_block', 'Блок').' №'.$id) : \Yii::t('webmaster_block', 'Новый блок');

        $this->render('index', [
            'model'     => $model,
            'id'        => $id,
            'siteId'    => $siteId,
        ]);
    }

    public function actionList($siteId, $status = 'actual')
    {
        $this->topButtons[0]['elements']['a']['linkParams'] = ['siteId' => $siteId];

        $showModal = false;
        $siteModel = \models\Site::getInstance();
        $siteModel->initById($siteId);

        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId])] = \Yii::t('webmaster_block', 'Площадка').' '.$siteModel->url;
        $this->pageName = \Yii::t('webmaster_block', 'Мои блоки');
        unset($siteModel);

        if($blockId = \Yii::app()->session['blockId']){
            $this->modalContent = $this->getModalCode($blockId);
            unset(\Yii::app()->session['blockId']);
            $showModal = true;
        }

        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $containsToday = false;

        $now = new \DateTime();
        $today = $now->format($format);

        if(!$startDate) {
            $startDate = $now->modify('-1 month');;
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
            'status' => $status,
            'siteId' => $siteId
        ];

        try{
            $model = Block::getInstance();
            $statsModel = WebmasterStats::model();
            $statsModel->user_id = Session::getInstance()->getUserId();
            $stats = $statsModel->getPeriodSiteStats($params);

            $pageSize = \Yii::app()->params['defaultPageSize'];

            if($startDate && $endDate){
                $totalCount = count($model->getList([
                    'siteId' => $siteId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]));
            }else{
                $totalCount = $model->count(['site_id' => $siteId, 'status != '.Block::STATUS_ARCHIVED]);
            }

            $criteria = new \CDbCriteria;

            $pages = new \Pagination($totalCount);
            $pages->pageSize = $pageSize;
            $pages->applyLimit($criteria);

            $blocks = $model->getBlocks([
                'siteId' => intval($siteId),
                'limit' => $criteria->limit,
                'offset' => $criteria->offset,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status
            ]);
            $blocksList = [];

            $round = \Yii::app()->params['statsRound'];

            if($blocks){
                $siteModel = Site::getInstance();

                foreach($blocks as $block){
                    foreach($stats as $key => $stat){
                        if($block['id'] == $key){
                            $block['shows'] = $stat['shows'];
                            $block['clicks'] = $stat['clicks'];
                            $block['income'] = $stat['costs'];
                        }
                    }

                    if(trim($block['type']) == Blocks::FORMAT_MAIN){
                        $content = \CJSON::decode($block['content']);
                        $block['size'] = Blocks::FORMAT_MAIN;
                        $block['width'] = isset($content['width']) ? $content['width'] : '';
                    }

                    $blocksList[] = (object)array_merge($block, [
                        'format' => $block['size'],
                        'size' => isset($block['width']) ? $block['width'] : $block['size'],
                        'status' => $model->getStatusName($block['status']),
                        'statusClass' => $model->getStatusClass($block['status']),
                        'isEnabled' => $model->getIsEnabled($block['status']),
                        'isAllowedSwitch' => !in_array($block['status'],[500]),
                        'ctr' => $block['shows'] != 0 ? round(($block['clicks']/$block['shows'])*100, $round) : 0,
                        'expense' => $block['blockIncome'],
                        'type' => $block['type'] ? trim($block['type']) : Block::BLOCK_LIGHT_FORMAT,
                    ]);
                }
            }

            $createdBlock = \Yii::app()->session['createdBlock'];

            unset(\Yii::app()->session['createdBlock']);
        }catch(\Exception $e){
            $blocksList = [];
            $createdBlock = null;
            $pages = new \Pagination(0);

            $this->setFlash($e->getMessage(), \Yii::t('webmaster_block', 'Не удалось получить список кампаний.'), \Yii::t('webmaster_block', 'Пожалуйста, попробуйте позже'));
        }


        $this->render('list', [
            'blocksList' => $blocksList,
            'createdBlock' => $createdBlock,
            'pages' => $pages,
            'siteId' => $siteId,
            'model' => new \BlockForm(),
            'date' => $date,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'showModal' => $showModal,
            'blockTypesContent' => $this->partial('_modal', ['data' => $this->getModalAsk($siteId)], true),
            'status' => $status
        ]);
    }

    public function actionDelete($id, $siteId)
    {
        $block = \models\Block::getInstance();
        try{
            if($block->delete($id)){
                \Yii::app()->user->setFlash('success', 'Рекламный блок успешно удален!');
            }else{
                \Yii::app()->user->setFlash('error', 'Произошла ошибка! Попробуйте еще раз.');
            }
        }catch(\Exception $e){
            $this->setFlash($e->getMessage(), 'Произошла ошибка', 'Попробуйте еще раз');
        }

        $this->redirect(\Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId]));
    }

    public function actionChangeStatus($id = null)
    {
        if(\Yii::app()->request->isAjaxRequest && $id && isset($_POST['checked'])){
            $ajax = [
                'html' => '',
                'message' => '',
                'error' => '',
            ];

            $checked = (string)$_POST['checked'] === 'true';

            if($checked){
                $error = \Yii::t('webmaster_block', 'Не удалось активировать рекламный блок');
                $message = \Yii::t('webmaster_block', 'Рекламный блок успешно активирован!');
            }else{
                $error = \Yii::t('webmaster_block', 'Не удалось деактивировать рекламный блок');
                $message = \Yii::t('webmaster_block', 'Рекламный блок успешно деактивирован!');
            }

            $model = \models\Block::getInstance();

            try{
                if($model->initById($id)){
                    $model->status = $checked ? 1 : 0;

                    if($model->changeStatus()){
                        $ajax['message'] = $message;
                    }else{
                        $ajax['error'] = $error;
                    }
                }else{
                    if(YII_DEBUG)
                        $error .= ': '.\Yii::t('webmaster_block', 'не удалось найти блок с ID').' = '.$id;
                    $ajax['error'] = $error;
                }
            }catch(\Exception $e){
                if(YII_DEBUG)
                    $error .= ': '.$e->getMessage();
                $ajax['error'] = $error;
            }

            echo \CJSON::encode($ajax);
        }

        \Yii::app()->end();
    }

    public function actionRemoveAll()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['value'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            if($ids = \Yii::app()->request->getPost('value')){
                $ids = explode(',', $ids);
                $model = \models\Block::getInstance();
                $errors = [];

                foreach($ids as $id){
                    try{
                        if(!$model->delete($id))
                            $errors[] = \Yii::t('webmaster_block', \Yii::t('webmaster_block', 'Не удалось удалить блок')).' '.$id;
                    }catch(\Exception $e){
                        $errors[] = $this->parseError($e->getMessage());
                    }
                }

                if($errors)
                    \Yii::app()->user->setFlash('error', \Yii::t('webmaster_block', 'Не удалось удалить блоки:').' '.implode(';', $errors));
                else
                    \Yii::app()->user->setFlash('success', \Yii::t('webmaster_block', 'Блоки успешно удалены'));
            }else{
                $json['error'] = \Yii::t('webmaster_block', 'Неправильные данные');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionGetPreview($id = null, $siteId = null)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['previewTeaser'])){
            $json = [
                'error' => '',
                'message' => '',
                'success' => '',
                'html' => '',
                'teasers' => '',
                'css' => ''
            ];

            $teaserSize = \Yii::app()->request->getPost('teaserSize');
            $teaserColor = \Yii::app()->request->getPost('teaserColor');
            $teaserBg = \Yii::app()->request->getPost('teaserBg');

            if(!$teaserSize)
                $teaserSize = '160x600';
            if(!$teaserColor)
                $teaserColor = 'standart';
            if(!$teaserBg)
                $teaserBg = 'gray';

            $teaserColor = $teaserColor == 'undefined' ? 'standart' : $teaserColor;
            $teaserBg = $teaserBg == 'undefined' ? 'gray' : $teaserBg;
            $renderer = \models\BlockRenderer::getInstance();
            $model = \models\Block::getInstance();

            $params = [
                'type' => $teaserSize,
                'backgroundScheme' => $teaserBg,
                'colorScheme' => $teaserColor,
                'allowAdult' => 1,
                'allowShock' => 1
            ];

            $blocks = null;

            try{
                if($model->initById($id)){
                    $params['allowAdult'] = $model->allowAdult;
                    $params['allowShock'] = $model->allowShock;
                    $blocks = $renderer->renderNoUpdate($id, $params);
                }
            }catch(\Exception $e){
                $message = $e->getMessage();

                if($message != 'block not found' && $message != 'ID is not set')
                    $json['error'] = $this->parseError($message, \Yii::t('webmaster_block', 'К сожалению, не удалось загрузить предпросмотр блока'), \Yii::t('webmaster_block', 'Пожалуйста, попробуейте позже'));
            }

            if(!$blocks){
                $blockData = [
                    'categories' => [],
                    'id' => $id ? $id : 12345,
                    'siteId' => $siteId,
                    'userId' => \Yii::app()->user->id,
                ];

                foreach(\models\Category::getInstance()->findAll(['id']) as $cat){
                    if($cat->id == 19){
                        continue;
                    }
                    $blockData['categories'][] = $cat->id;
                }
                $params['allowAdult'] = 1;
                $params['allowShock'] = 1;

                $blocks = $renderer->renderNoUpdate($id, $params, $blockData);
            }

            list($css, $teasers) = $blocks;

            $json['teasers'] = urlencode($teasers);
            $json['css'] = json_encode($css);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
} 