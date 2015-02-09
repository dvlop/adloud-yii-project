<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.07.14
 * Time: 16:09
 */

namespace application\modules\advertiser\controllers;

use application\components\ControllerAdvertiser;
use models\Lists;

class ListsController extends ControllerAdvertiser
{
    public function actionIndex()
    {
        $this->model = new \ListsSitesForm();
        $listsModel = Lists::getInstance();
        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/lists/index')] = \Yii::t('lists', 'Списки сайтов');

        if(\Yii::app()->request->isPostRequest && isset($_POST['ListsSitesForm'])){
            $this->model->attributes = $_POST['ListsSitesForm'];

            if($this->model->validate()){
                try{
                    if($listsModel->saveList($this->model->attributes)){
                        \Yii::app()->user->setFlash('success', \Yii::t('lists', 'Список успешно сохраненён'));
                        $this->model = new \ListsSitesForm();
                    }else
                        \Yii::app()->user->setFlash('error', \Yii::t('lists', 'К сожалению, не удалось сохранить список. Пожалуйста, попробуйте позже'));
                }catch(\Exception $e){
                    $this->setFlash($e->getMessage(), \Yii::t('lists', 'К сожалению, не удалось сохранить список', 'Пожалуйста, попробуйте позже'));
                }
            }
        }

        $lists = [];
        try{
            if($lists = $listsModel->getAll()){
                foreach($lists as $num => $list){
                    $lists[$num] = (object)array_merge($list, [
                        'campaignsCount' => count($list['sites']),
                        'type' => $this->model->getTypeName($list['type']),
                    ]);
                }
            }else{
                $lists = [];
            }
        }catch(\Exception $e){
            $this->setFlash($e->getMessage(), \Yii::t('lists', 'К сожалению, не удалось загрузить списки'));
        }

        $this->render('index', [
            'lists' => $lists,
            'model' => $this->model,
        ]);
    }

    public function actionManage($id = null)
    {
        $this->model = new \ListsSitesForm();
        $listsModel = \models\Lists::getInstance();

        if($id){
            try{
                if($listsModel->initById($id))
                    $this->model->attributes = $listsModel->getData();
                else
                    \Yii::app()->user->setFlash('error', 'К сожалению, не удалось найти список ID '.$id);
            }catch(\Exception $e){
                $this->setFlash($e->getMessage(), 'К сожалению, не удалось найти список', 'ID сиска: '.$id);
            }
        }

        $oldCampaigns = $this->model->campaigns;

        if(\Yii::app()->request->isPostRequest && isset($_POST['ListsSitesForm'])){
            $this->model->attributes = $_POST['ListsSitesForm'];

            if($this->model->validate()){
                try{
                    if($listsModel->updateLists($this->model->attributes, $oldCampaigns)){
                        \Yii::app()->user->setFlash('success', 'Список успешно сохраненён');
                        $this->redirect(\Yii::app()->createUrl('advertiser/lists/index'));
                    }else{
                        \Yii::app()->user->setFlash('error', 'К сожалению, не удалось сохранить список');
                    }
                }catch(\Exception $e){
                    $this->setFlash($e->getMessage(), 'К сожалению, не удалось сохранить список');
                }
            }
        }

        $this->render('manage', ['model' => $this->model]);
    }

    public function actionRemove($id)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['remove']) && $_POST['remove']){
            $json = [
                'error' => '',
                'message' => '',
                'html' => '',
            ];

            try{
                $model = Lists::getInstance();
                if($model->initById($id)){
                    if($model->delete()){
                        $json['message'] = 'Список "'.$model->name.'" удалён';
                        $json['html'] = ' ';
                    }else{
                        $json['error'] = 'Не удалось удалить список';
                    }
                }else{
                    $json['error'] = 'Не удалось найти данные по ID '.$id;
                }
            }catch(\Exception $e){
                $json['error'] = $this->parseError($e->getMessage(), 'Не удалось удалить список');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}