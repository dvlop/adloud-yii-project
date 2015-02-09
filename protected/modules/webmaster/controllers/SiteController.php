<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 07.07.14
 * Time: 21:46
 */

namespace application\modules\webmaster\controllers;

use application\components\ControllerWebmaster;
use application\models\Sites;
use application\models\WebmasterStats;
use core\Session;

class SiteController  extends ControllerWebmaster
{
    public function actionIndex($id = null)
    {
        if($id){
            if(!$model = Sites::model()->findByPk($id))
                throw new \CHttpException(404,'The requested page does not exist.');
            $pageName = \Yii::t('webmaster_site','Изменить площадку');
        }else{
            $model = new Sites();
            $pageName = \Yii::t('sites','Добавить площадку');
        }

        $this->breadcrumbs[\Yii::app()->createUrl($this->id.'/'.$this->action->id)] = $pageName;
        $this->alterPageName[0]['headers']['h1']['name'] = $pageName;

        if(\Yii::app()->request->isPostRequest && isset($_POST['application_models_Sites'])){
            $attributes = $_POST['application_models_Sites'];
            if(isset($_POST['SiteForm']))
                $attributes = array_merge($attributes, $_POST['SiteForm']);

            $model->setAttributes($attributes);

            if($model->save()){
                if(!$id){
                    $model->sendEmailAfterChange();
                    \Yii::app()->user->setFlash('success', 'Сайт успешно добавлен! Теперь Вы можете создать рекламный блок.');
                    $url = \Yii::app()->createUrl('block/select/format', ['siteId' => $model->id]);

                    if(\Yii::app()->user->isAdmin)
                        $model->publish();
                }else{
                    \Yii::app()->user->setFlash('success', 'Сайт успешно изменён!');
                    $url = \Yii::app()->createUrl('webmaster/site/list');

                    if($model->status == Sites::STATUS_PUBLISHED){
                        $model->unPublish();
                        $model->publish();
                    }
                }

                $this->redirect($url);
            }else{
                $this->setFlash('error', $this->parseError($model->errors, 'Не удалось создать площадку'));
            }
        }

        $this->render('index', ['model' => $model]);
    }

    public function actionList($status = 'actual')
    {
        $format = \Yii::app()->params['dateFormat'];
        $date = date($format);
        $startDate = \Yii::app()->request->getQuery('startDate');
        $endDate = \Yii::app()->request->getQuery('endDate');
        $containsToday = false;

        $this->pageName = \Yii::t('sites','Мои площадки');

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
            'status' => $status
        ];

        try{
            $model = \models\Site::getInstance();
            $statsModel = WebmasterStats::model();
            $statsModel->user_id = Session::getInstance()->getUserId();
            $stats = $statsModel->getPeriodStatsBySite($params);
            $pageSize = \Yii::app()->params['defaultPageSize'];

            if($startDate && $endDate){
                $totalCount = count($model->getList([
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]));
            }else{
                $totalCount = $model->count(['user_id' => \Yii::app()->user->id, 'status != '.Sites::STATUS_ARCHIVED]);
            }

            $criteria = new \CDbCriteria;

            $pages = new \Pagination($totalCount);
            $pages->pageSize = $pageSize;
            $pages->applyLimit($criteria);
            $pages->filters = [
                'start_date' => date(\Yii::app()->params['dateFormat']),
            ];

            $sites = $model->getSites([
                'limit' => $criteria->limit,
                'offset' => $criteria->offset,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status
            ]);

            $sitesList = [];

            $round = \Yii::app()->params['statsRound'];

            if($sites){
                foreach($sites as $site){

                    foreach($stats as $stat){
                        if($site['id'] == $stat['item_id']){
                            $site['shows'] = $stat['shows'];
                            $site['clicks'] = $stat['clicks'];
                            $site['income'] = $stat['costs'];
                        }
                    }

                    $sitesList[] = (object)array_merge($site, [
                        'statusClass' => $model->getStatusClass($site['status']),
                        'statusName' => $model->getStatusName($site['status']),
                        'isEnabled' => $model->getIsEnabled($site['status']),
                        'isAllowedSwitch' => $model->getIsModerated($site['status']),
                        'dataUrl' => \Yii::app()->createUrl('webmaster/site/changeStatus', ['id' => $site['id']]),
                        'clicks' => $site['clicks'],
                        'shows' => $site['shows'],
                        'income' => $site['income'],
                        'ctr' => $site['shows'] != 0 ? round(($site['clicks']/$site['shows'])*100, $round) : 0,
                        'earnings' => 0,
                    ]);
                }
            }

            $this->render('list', [
                'sitesList' => $sitesList,
                'createdSite' => '',
                'pages' => $pages,
                'date' => $date,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status
            ]);
        }catch(\Exception $e){
            $this->setFlash($e->getMessage(), 'Не удалось загрузить список сайтов');
            $this->render('list', [
                'sitesList' => [],
                'createdSite' => '',
                'pages' =>  new \Pagination(0),
                'date' => '',
                'startDate' => '',
                'endDate' => '',
                'status' => $status
            ]);
        }
    }

    public function actionDelete($id)
    {
        $site = \models\Site::getInstance();
        try{
            $site->delete($id);
            \Yii::app()->user->setFlash('success', 'Рекламная площадка успешно удалена!');
        }catch(\Exception $e){
            $this->setFlash($e->getMessage(), 'Произошла ошибка', 'Попробуйте еще раз');
        }

        $this->redirect(\Yii::app()->createUrl('webmaster/site/list'));
    }

    public function actionChangeStatus($id = null)
    {
        if(\Yii::app()->request->isAjaxRequest && $id && isset($_POST['checked'])){
            $json = [
                'html' => '',
                'message' => '',
                'error' => '',
            ];

            $checked = $_POST['checked'] === 'true';
            $massage = 'Сайт ID '.$id.' успешно ';
            $error = 'Не удалось ';

            if($checked){
                $method = 'publish';
                $massage .= 'активирован.';
                $error .= 'активировать ';
            }else{
                $method = 'unPublish';
                $massage .= 'деактивирован.';
                $error .= 'деактивировать ';
            }

            $error .= 'Сайт ID '.$id;

            $model = Sites::model()->findByPk($id);
            if(!$model){
                $json['error'] = 'Не удалось найти сайт ID '.$id;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            if($model->$method())
                $json['message'] = $massage;
            else
                $json['error'] = $this->parseError($model->errors, $error);

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
                $model = \models\Site::getInstance();
                $errors = [];

                foreach($ids as $id){
                    try{
                        if(!$model->delete($id))
                            $errors[] = 'Не удалось удалить сайт '.$id;
                    }catch(\Exception $e){
                        $errors[] = $this->parseError($e->getMessage());
                    }
                }

                if($errors)
                    \Yii::app()->user->setFlash('error', 'Не удалось удалить сайты: '.implode(';', $errors));
                else
                    \Yii::app()->user->setFlash('success', 'Сайты успешно удалены');
            }else{
                $json['error'] = 'Неправильные данные';
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
} 