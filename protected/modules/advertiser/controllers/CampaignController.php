<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 07.07.14
 * Time: 18:23
 */

namespace application\modules\advertiser\controllers;

use application\components\ControllerAdvertiser;
use application\models\Campaign;
use application\models\AdvertiserStats;
use core\Session;
use application\models\Label;

class CampaignController  extends ControllerAdvertiser
{
    public function actionIndex($id = null)
    {
        if($id === null){
            $model = new Campaign();
            $campDesc = \Yii::t('campaign', 'Новая кампания');
        }else{
            $model = Campaign::model()->findByPk($id);
            $campDesc = \Yii::t('campaign', 'Кампания').' '.$model->description;
            if($model === null)
                throw new \CHttpException(404, 'The requested page does not exist.');
        }

        $this->pageName = \Yii::t('campaign', 'Создание Кампании');

        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/campaign/index', ['id' => $id])] = $campDesc;

        if(\Yii::app()->request->isPostRequest && isset($_POST[$model->getModelName()])){
            $model->geo = null;
            $model->ua = null;
            $model->targets = null;

            $model->setAttributes($_POST[$model->getModelName()]);

            if($model->save()){
                $message = \Yii::t('campaign', 'Рекламная кампания успешно сохранена.');
                if($id === null)
                    $message .= ' '.\Yii::t('campaign', 'Теперь Вы можете создать тизеры');

                \Yii::app()->user->setFlash('success', $message);

                if($id){
                    if($model->publish == Campaign::STATUS_PUBLISHED){
                        $model->unPublish();
                        $model->publish();
                    }
                }elseif(\Yii::app()->user->isAdmin){
                    $model->publish();
                }

                if($id)
                    $this->redirect(\Yii::app()->createUrl('advertiser/campaign/list'));
                else
                    $this->redirect(\Yii::app()->createUrl('advertiser/ads/index', ['campaignId' => $model->id]));
            }else{
                $this->setFlash($model->errors, \Yii::t('campaign', 'К сожалению не удалось сохранить кампанию'));
            }
        }

        $this->render('index', ['model' => $model, 'campaignId' => $id]);
    }

    public function actionList()
    {
        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $status = \Yii::app()->request->getQuery('status');
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $labelId = \Yii::app()->request->getQuery('label');
        $containsToday = false;
        $containsLabel = false;

        $this->pageName = \Yii::t('campaigns', 'Мои кампании');

        if($labelId){
            $label = Label::model()->findByPk($labelId);
            if($label)
                $this->breadcrumbs[\Yii::app()->createUrl('advertiser/campaign/list', ['label' => $labelId])] = \Yii::t('campaigns', 'Кампании с меткой').' "'.$label->name.'"';
        }

        $now = new \DateTime();
        $today = $now->format($format);

        if(!$status) {
            $status = 'actual';
        }

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
            'labelId' => intval($labelId),
        ];

        try{
            $model = \models\Campaign::getInstance();
            $statsModel = AdvertiserStats::model();
            $statsModel->user_id = Session::getInstance()->getUserId();
            $stats = $statsModel->getPeriodStatsByCampaign($params);

            $pageSize = \Yii::app()->params['defaultPageSize'];
            $totalCount = $model->count(['user_id' => \Yii::app()->user->id, 'publish != '.Campaign::STATUS_ARCHIVED]);

            $criteria = new \CDbCriteria;

            $pages = new \Pagination($totalCount);
            $pages->pageSize = $pageSize;
            $pages->applyLimit($criteria);
            $pages->filters = [
                'start_date' => date(\Yii::app()->params['dateFormat']),
            ];

            $campaignsList = $model->getCampaigns([
                'startDate' => $startDate,
                'endDate' => $endDate,
                'limit' => $criteria->limit,
                'offset' => $criteria->offset,
                'status' => $status,
                'labelId' => intval($labelId),
            ]);

            $campaigns = [];

            $round = \Yii::app()->params['statsRound'];

            if($campaignsList){
                $labelModel = new Label();

                foreach($campaignsList as $camp){
                    $adsStatus = $model->getAdsStatus($camp['id']);

                    foreach($stats as $stat){
                        if($camp['id'] == $stat['item_id']){
                            $camp['shows'] = $stat['shows'];
                            $camp['clicks'] = $stat['clicks'];
                            $camp['expenses'] = $stat['costs'];
                        }
                    }

                    $label = null;
                    if($camp['labelsId'] && isset($camp['labelsId'][0]) && $camp['labelsId'][0]){
                        $label = $labelModel->findByPk($camp['labelsId'][0]);
                        if($label)
                            $containsLabel = true;
                    }

                    $campaigns[$camp['id']] = (object)array_merge($camp, [
                        'statusName' => $model->getStatusName($camp['status']),
                        'statusClass' => $model->getStatusClass($camp['status']),
                        'isEnabled' => $model->getIsEnabled($camp['status']),
                        'isAllowedSwitch' => $model->getModerated($camp['status']),
                        'ctr' => $camp['shows'] > 0 ? round(($camp['clicks']/$camp['shows'])*100, $round) : 0,
                        'runned' => $adsStatus['runned'],
                        'paused' => $adsStatus['paused'],
                        'moderated' => $adsStatus['moderated'],
                        'blocked' => $adsStatus['blocked'],
                        'label' => $label,
                    ]);
                }
            }

            $createdCampaign = \Yii::app()->session['createdCampaign'];

            $this->render('list', [
                'campaigns' => $campaigns,
                'createdCampaign' => $createdCampaign,
                'pages' => $pages,
                'date' => $date,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status,
                'containsLabel' => $containsLabel,
            ]);
        }catch(\Exception $e){
            throw new \CHttpException(404, $this->parseError($e->getMessage(), \Yii::t('campaigns', 'Не удалось получить список кампаний')));
        }

        unset(\Yii::app()->session['createdCampaign']);
    }

    public function actionDelete($id = null)
    {
        if($id && \Yii::app()->request->isAjaxRequest && isset($_POST['delete'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => ''
            ];

            $model = Campaign::model()->findByPk($id);
            if($model === null){
                $json['error'] = 'Не найдена кампания ID '.$id;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            if($model->delete()){
                $json['message'] = 'Рекламная кампания успешно удалена!';
                $json['html'] = ' ';
            }else{
                $json['error'] = $this->parseError($model->errors, 'К сожалению, не удалось удалить кампанию', 'Пожалуйста, попробуйте позже');
            }

            echo \CJSON::encode($json);
        }
        \Yii::app()->end();
    }

    public function actionChangeStatus()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['checked']) && isset($_POST['id'])){
            $json = [
                'html' => '',
                'error' => '',
                'message' => ''
            ];

            $id = $_POST['id'];
            $checked = (string)$_POST['checked'] === 'true';

            $campaign = Campaign::model()->findByPk($id);
            if(!$campaign){
                $json['error'] = 'Не удалось найти кампанию ID '.$id;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            if($checked){
                $message = 'Рекламная кампания успешно запущена!';
                $error = 'Не удалось запустить рекламную кампанию.';
                $methodName = 'publish';
            }else{
                $message = 'Рекламная кампания успешно остановлена!';
                $error = 'Не удалось остановить рекламную кампанию.';
                $methodName = 'unPublish';
            }

            if($campaign->$methodName())
                $json['message'] = $message;
            else
                $json['error'] = $this->parseError($campaign->errors, $error);

            echo \CJSON::encode($json);
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
                $model = \models\Campaign::getInstance();
                $errors = [];

                foreach($ids as $id){
                    try{
                        if(!$model->delete($id))
                            $errors[] = 'Не удалось удалить кампанию '.$id;
                    }catch(\Exception $e){
                        $errors[] = $this->parseError($e->getMessage());
                    }
                }

                if($errors)
                    \Yii::app()->user->setFlash('error', 'Не удалось удалить кампании: '.implode(';', $errors));
                else
                    \Yii::app()->user->setFlash('success', 'Кампании успешно удалены');
            }else{
                $json['error'] = 'Неправильные данные';
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionRemoveLabel($id)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['removeLabel'])){
            $json = [
                'message' => '',
                'html' => '',
                'error' => '',
            ];

            $model = Campaign::model()->findByPk($id);
            if(!$model){
                $json['error'] = 'Не удалось найти кампанию ID '.$id;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            if($model->removeLabel())
                $json['message'] = 'Метка кампании удалена';
            else
                $json['error'] = $this->parseError($model->errors, 'Не удалось удалить метку');

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
} 