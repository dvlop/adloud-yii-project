<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 03.07.14
 * Time: 13:44
 */

namespace application\modules\advertiser\controllers;

use application\components\ControllerAdvertiser;
use application\models\Ads;
use application\models\AdvertiserStats;
use application\models\Campaign;
use core\Session;

class AdsController extends ControllerAdvertiser
{
    public function actionIndex($campaignId, $id = null, $action = null)
    {
        if(!$id){
            $model = new Ads();
            $model->setCampaignId($campaignId);
            $title = \Yii::t('ads_campaign', 'Создание объявления');
        }else{
            $model = Ads::model()->findByPk($id);
            $title = \Yii::t('ads_campaign', 'Редактирование объявления');
            if($model === null)
                throw new \CHttpException(404,'The requested page does not exist.');
        }

        if($action == 'copy')
            $title = \Yii::t('ads_campaign', 'Копирование объявления');

        $campaignModel = Campaign::model()->findByPk($campaignId);
        if($campaignModel === null)
            throw new \CHttpException(404,'The requested page does not exist.');

        if($action == 'copy' && !$model->getCampaignsList()){
            \Yii::app()->user->setFlash('error', \Yii::t('ads_campaign', 'К сожалению, у Вас нет ни одной компании, в которую можно было бы скопировать объявление'));
            $this->redirect(\Yii::app()->createUrl('advertiser/ads/list', ['campaignId' => $campaignId]));
        }

        $modelCaption = $id ? $model->caption : \Yii::t('ads_campaign', 'Новое объявление');

        $this->alterPageName[0]['headers']['h1']['name'] = $title;
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/ads/list', ['campaignId' => $campaignId])] = \Yii::t('ads_campaign', 'Тизеры кампании').' '.$campaignModel->description;
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/ads', ['campaignId' => $campaignId, 'id' => $id])] = \Yii::t('ads_campaign', 'Объявление').' '.$modelCaption;

        if(\Yii::app()->request->isPostRequest && isset($_POST[$model->getModelName()])){
            $model->setAttributes($_POST[$model->getModelName()]);

            if($action == 'copy'){
                if($model->copy()){
                    \Yii::app()->user->setFlash('success', \Yii::t('ads_campaign', 'Объявление успешно скопировано!'));
                    $this->redirect(\Yii::app()->createUrl('advertiser/ads/list', ['campaignId' => $model->getCampaignId()]));
                }else{
                    $this->setFlash($model->errors, \Yii::t('ads_campaign', 'Не удалось скопировать объявление'));
                }
            }else{
                if($model->save()){
                    $message = \Yii::t('ads_campaign', 'Объявление успешно').' ';
                    $message .= $id ? \Yii::t('ads_campaign', 'изменено') : \Yii::t('ads_campaign', 'создано');

                    if($id && $model->status == Ads::STATUS_PUBLISHED){
                        $model->unPublish();
                        $model->publish();
                    }elseif($campaignModel->publish == Campaign::STATUS_PUBLISHED){
                        $model->publish();
                    }

                    \Yii::app()->user->setFlash('success', $message);
                    $this->redirect(\Yii::app()->createUrl('advertiser/ads/list', ['campaignId' => $campaignId]));
                }else{
                    $this->setFlash($model->errors, \Yii::t('ads_campaign', 'Не удалось сохранить объявление'));
                }
            }
        }

        if($action == 'copy')
            $buttonName = \Yii::t('ads_campaign', 'Копировать объявлеине');
        elseif($id)
            $buttonName = \Yii::t('ads_campaign', 'Сохранить объявление');
        else
            $buttonName = \Yii::t('ads_campaign', 'Создать объявление');

        $this->render('index', [
            'model' => $model,
            'id' => $id,
            'campaignId' => $campaignId,
            'buttonName' => $buttonName,
            'action' => $action
        ]);

    }

    public function actionList($campaignId = null)
    {
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/ads/list', ['campaignId' => $campaignId])] = \Yii::t('ads_list', 'Список тизеров');
        $this->pageName = \Yii::t('ads_list', 'Список тизеров');

        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $status = \Yii::app()->request->getQuery('status');
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $containsToday = false;

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
            'campaignId' => $campaignId
        ];

        try{
            $this->topButtons[0]['elements']['a']['linkParams'] = ['campaignId' => $campaignId];
            $model = \models\Ads::getInstance();
            $statsModel = AdvertiserStats::model();
            $statsModel->user_id = Session::getInstance()->getUserId();
            $stats = $statsModel->getPeriodCampaignStats($params);

            $pageSize = \Yii::app()->params['defaultPageSize'];
            $totalCount = $model->count(['campaign_id' => $campaignId, 'status != '.Ads::STATUS_ARCHIVED]);

            $criteria = new \CDbCriteria;

            $pages = new \Pagination($totalCount);
            $pages->pageSize = $pageSize;
            $pages->applyLimit($criteria);
            $pages->filters = [
                'start_date' => date(\Yii::app()->params['dateFormat']),
            ];

            $ads = $model->getAds([
                'campaignId' => $campaignId,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'limit' => $criteria->limit,
                'offset' => $criteria->offset,
                'status' => $status
            ]);

            $adsList = [];

            $round = \Yii::app()->params['statsRound'];

            if($ads){
                $adsFromModel = new \AdsForm();

                foreach($ads as $ad){
                    $img = isset($ad['content']['imageUrl']) ? $ad['content']['imageUrl'] : null;
                    $statusName = \Yii::t('ads_list', $model->getStatusName($ad['status']));

                    if($ad['status'] == Ads::STATUS_PROHIBITED)
                        $statusText = isset($ad['content']['admin_message']) ? $statusName." <br /><b>Причина:</b> ".$ad['content']['admin_message'] : $statusName;
                    else
                        $statusText = $statusName;

                    foreach($stats as $stat){
                        if($ad['id'] == $stat['item_id']){
                            $ad['shows'] = $stat['shows'];
                            $ad['clicks'] = $stat['clicks'];
                            $ad['expenses'] = $stat['costs'];
                        }
                    }

                    $adsList[$ad['id']] = (object)[
                        'id' => $ad['id'],
                        'image' => $adsFromModel->getImageFormUrl($img),
                        'showUrl' => $ad['content']['showUrl'],
                        'description' => $ad['content']['description'],
                        'url' => $ad['content']['url'],
                        'caption' => $ad['content']['caption'],
                        'clicks' => $ad['clicks'],
                        'shows' => $ad['shows'],
                        'ctr' => $ad['shows'] > 0 ? round(($ad['clicks']/$ad['shows'])*100, $round) : 0,
                        'statusName' => $statusText,
                        'statusClass' => $model->getStatusClass($ad['status']),
                        'isEnabled' => $model->getIsEnabled($ad['status']),
                        'isAllowedSwitch' => $model->getModerated($ad['status']),
                        'clickPrice' => $ad['clickPrice'],
                        'expenses' => $ad['expenses'],
                    ];
                }
            }
            $createdAds = \Yii::app()->session['createdAds'];

            $this->render('list', [
                'adsList' => $adsList,
                'createdAds' => $createdAds,
                'pages' => $pages,
                'campaignId' => $campaignId,
                'date' => $date,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status
            ]);
        }catch(\Exception $e){
            $this->setFlash($e->getMessage(), \Yii::t('ads_list', 'Не удалось получить список тизеров'));
        }

        unset(\Yii::app()->session['createdAds']);
    }

    public function actionDelete($id, $campaignId)
    {
        $model = Ads::model()->findByPk($id);

        if($model === null)
            throw new \CHttpException(404, 'Page not found');

        if($model->delete())
            \Yii::app()->user->setFlash('success', \Yii::t('ads_list', 'Обьявление успешно удалено!'));
        else
            $this->setFlash($model->errors, \Yii::t('ads_list', 'Произошла ошибка!'));

        $this->redirect(\Yii::app()->createUrl('advertiser/ads/list', ['campaignId' => $campaignId]));
    }

    public function actionChangeStatus($id, $campaignId)
    {
        if($id && $campaignId && isset($_POST['checked'])){
            $json = [
                'html' => '',
                'error' => '',
                'message' => ''
            ];

            $checked = (string)$_POST['checked'] === 'true';

            $campaign = \models\Publisher::getInstance();

            if($checked)
                $error = \Yii::t('ads_list', 'Не удалось запустить рекламное объявление');
            else
                $error = \Yii::t('ads_list', 'Не удалось остановить рекламное объявление');

            try{
                $ads = \models\Ads::getInstance();
                $ads->initById($id);

                if($checked){
                    if($ads->getModerated()){
                        if($campaign->publishAds($id)){
                            $json['message'] = \Yii::t('ads_list', 'Рекламное объявление успешно запущено!');
                        }else{
                            $json['error'] = $error;
                        }
                    }else{
                        $json['error'] = \Yii::t('ads_list', 'Произошла ошибка! Обьяление не прошло модерацию.');
                    }
                }else{
                    if($campaign->unPublishAds($id)){
                        $json['message'] = \Yii::t('ads_list', 'Рекламное объявление успешно остановлено!');
                    }else{
                        $json['error'] = $error;
                    }
                }
            }catch(\Exception $e){
                $json['error'] = $this->parseError($e->getMessage(), \Yii::t('ads_list', $error));
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionGetAd($id)
    {
        /*
        * Получить обьявления для вывода в тултип в списке обьявлений при наведении на обьявление
        */
        $ads = \models\Ads::getInstance();
        $ads->initById($id);
        $this->renderPartial('ad', ['ad' => $ads]);
    }

    public function actionPreview($campaignId = null, $id = null)
    {
        if($campaignId && \Yii::app()->request->isAjaxRequest){
            $json = [
                'success' => '',
                'error' => '',
                'html' => '',
            ];

            $title = \Yii::app()->request->getPost('title');
            $desk = \Yii::app()->request->getPost('desk');
            $buttonText = \Yii::app()->request->getPost('buttonText');
            $url = \Yii::app()->request->getPost('url');
            $urlText = \Yii::app()->request->getPost('urlText');
            $showButton = \Yii::app()->request->getPost('showButton');
            $adsType = \Yii::app()->request->getPost('adsType');
            $img = \Yii::app()->request->getPost('img');

            $model = new \AdsForm();
            if($id != null){
                $model->initByAdsId($id, $campaignId);

                $title = !$title ? $model->caption : $title;
                $desk = !$desk ? $model->description : $desk;
                $buttonText = !$buttonText ? $model->buttonText: $buttonText;
                $url = !$url ? $model->url : $url;
                $urlText = !$urlText ? $model->showUrl : $urlText;
                $showButton = $showButton === null ? $model->showButton : $showButton;
                $adsType = !$adsType ? $model->type : $adsType;
            }

            $title = !$title ? 'Тестовое объявление' : $title;
            $desk = !$desk ? 'Тестовое описание рекламного объявления' : $desk;
            $buttonText = !$buttonText ? 'Кнопка': $buttonText;
            $url = !$url ? 'http://site.com' : $url;
            $urlText = !$urlText ? 'site.com' : $urlText;
            $showButton = $showButton === null ? true : (bool)$showButton;
            $adsType = !$adsType ? '300x250' : $adsType;
            $img = !$img ? ($model->image ? $model->image: '') : $img;

            if(!$img)
                $img = $model->defaultImg;


            $params = [
                'title' => $title,
                'desk' => $desk,
                'buttonText' => $buttonText,
                'url' => $url,
                'urlText' => $urlText,
                'showButton' => $showButton,
                'adsType' => $adsType,
                'img' => '<img src="'.$img.'" />',
            ];

            $viewFile = \Yii::getPathOfAlias('themes.'.\Yii::app()->theme->name.'.views.advertiser.ads.previews.').DIRECTORY_SEPARATOR.$adsType.'.php';

            if(is_file($viewFile)){
                $json['html'] = $this->renderFile($viewFile, $params, true);
            }else{
                $json['error'] = $this->parseError('Не найден файл: '.$viewFile, 'Не удалось загрузить предпросмтр', 'Пожалуйста, попробуйте позже');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionChangeAttr($campaignId)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['attrName']) && isset($_POST['id'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            $attrName = \Yii::app()->request->getPost('attrName');
            $id = \Yii::app()->request->getPost('id');
            $value = \Yii::app()->request->getPost($attrName);

            if(!$id)
                $json['error'] = 'Необходим ID тизера';
            if($value === null)
                $value = \Yii::app()->request->getPost('value');
            if($value === null)
                $json['error'] = 'Неправильное значение';

            if($value !== null)
                $value = floatval($value);

            if($id && $value !== null){
                $model = \models\Ads::getInstance();
                if($model->initById($id)){
                    if(property_exists('\models\Ads', $attrName)){
                        $model->$attrName = $value;
                        $model->adsContent->imageUrl = $model->adsContent->imageFile;
                        try{
                            if($model->updateAds($id)){
                                $json['message'] = 'Значение атрибута '.$attrName.' изменено';
                                $json['html'] = $value.'$';
                            }else{
                                $json['error'] = 'Не удалось зименить значение атрибута. Пожалуйста, попробуйте позже';
                            }
                        }catch(\Exception $e){
                            $json['error'] = $this->parseError($e->getMessage(), 'Неудалось зименить значение атрибута', 'Пожалуйста, попробуйте позже');
                        }
                    }else{
                        $json['error'] = 'Неправильное имя атрибута';
                    }
                }else{
                    $json['error'] = 'Не найдоно объявление ID '.$id;
                }
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionRemoveAll($campaignId = null)
    {
        if($campaignId && \Yii::app()->request->isAjaxRequest && isset($_POST['value'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            if($ids = \Yii::app()->request->getPost('value')){
                $ids = explode(',', $ids);
                $model = new Ads();
                $errors = [];

                foreach($ids as $id){
                    if($ads = $model->findByPk($id)){
                        if(!$ads->delete())
                            $errors[] = $model->getError(null);
                    }
                }

                if($errors)
                    \Yii::app()->user->setFlash('error', 'Не удалось удалить тизеры: '.implode(';', $errors));
                else
                    \Yii::app()->user->setFlash('success', 'Тизеры успешно удалены');
            }else{
                $json['error'] = 'Неправильные данные';
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}