<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 07.07.14
 * Time: 22:09
 */

namespace application\modules\block\controllers;

use application\components\ControllerWebmaster;
use application\models\Blocks;
use application\models\Sites;

class IndexController extends ControllerWebmaster
{
    private $site;

    public function actionIndex($format, $siteId, $id = null)
    {
        $this->creativeBlocks($format, $siteId, $id);

        if($id)
            $urlParams = ['format' => $format, 'siteId' => $siteId, 'id' => $id];
        else
            $urlParams = ['format' => $format, 'siteId' => $siteId];

        $this->render('index', [
            'model' => $this->model,
            'url' => \Yii::app()->createUrl('block/index/index', $urlParams),
        ]);
    }

    public function actionMain($siteId, $id = null)
    {
        $this->creativeBlocks('main', $siteId, $id);

        if($id){
            $urlParams = ['siteId' => $siteId, 'id' => $id];
            $buttonName = 'Сохранить';
        }else{
            $urlParams = ['siteId' => $siteId];
            $buttonName = 'Создать';
        }

        $this->render('main', [
            'model' => $this->model,
            'url' => \Yii::app()->createUrl('block/index/main', $urlParams),
            'siteId' => $siteId,
            'buttonName' => \Yii::t('block_market', $buttonName),
        ]);
    }

    public function actionGetPreview($siteId)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['format'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => '',
                'teasers' => '',
                'css' => ''
            ];

            $model = null;
            if($id = intval(\Yii::app()->request->getPost('id')))
                $model = Blocks::model()->findByPk($id);

            if($model === null){
                $model = new Blocks();
                $model->setSiteId($siteId);
            }

            if($params = $model->getPreview($_POST))
                list($json['css'], $json['teasers']) = $params;
            else{
                $json['error'] = $this->parseError($model->errors, \Yii::t('select_format', 'Не удалось загрузить предпросмотр тизеров'));
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionFormatsModal($id)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['addNewBlock'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => '',
            ];

            $json['html'] = $this->partial('_mainModalContent', ['data' => $this->getModalAsk($id)], true);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionCodeModal($id)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['showBlockCode'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => '',
            ];

            $data = $this->getModalCode($id);

            $data['title'] = \Yii::t('block_market', 'Код для вставки на сайт');
            $data['subtitle'] = '';

            $json['html'] = $this->partial('_mainModalContent', ['data' => $data], true);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    private function creativeBlocks($format, $siteId, $id)
    {
        $this->pageName = $id ? \Yii::t('block_market', 'Редактирование блока') : \Yii::t('block_market', 'Создание блока');
        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId])] = \Yii::t('block_market', 'Площадка').' '.$this->getSite($siteId)->url;
        $this->breadcrumbs[\Yii::app()->createUrl('block/index/index', ['id' => $siteId])] = $id ? \Yii::t('block_market', 'Редактирование блока') : \Yii::t('block_market', 'Создание блока');

        if($id !== null){
            if(!$model = Blocks::model()->findByPk($id))
                throw new \CHttpException(404, 'Page not found');
        }else{
            $model = new Blocks();
            $model->setFormat($format);
            $model->setSiteId($siteId);
        }

        if($model->getFormat() !== $format)
            throw new \CHttpException(404, 'Page not found');

        if(\Yii::app()->request->isPostRequest && isset($_POST[$model->getModelName()])){
            $attributes = $_POST[$model->getModelName()];
            if(!isset($attributes['useDescription']))
                $attributes['useDescription'] = false;

            $model->setAttributes($attributes);

            if($model->save()){
                $message = 'Блок успешно';
                $message .= $id ? \Yii::t('block_market', 'отредактирован').' ' : \Yii::t('block_market', 'создан').' ';
                \Yii::app()->user->setFlash('success', $message);

                if($id){
                    if($model->getSiteModel()->status == Sites::STATUS_PUBLISHED){
                        $model->unPublish();
                        $model->publish();
                    }
                }elseif($model->getSiteModel()->status == Sites::STATUS_PUBLISHED){
                    $model->publish();
                }

                $this->redirect(\Yii::app()->createUrl('webmaster/block/list', ['siteId' => $model->getSiteId()]));
            }else{
                $this->setFlash($model->errors, \Yii::t('block_market', 'Не удалось сохранить изменения'));
            }
        }

        $this->model = $model;
    }

    private function getSite($siteId)
    {
        if($this->site === null){
            $this->site = Sites::model()->findByPk($siteId);
            if(!$this->site)
                throw new \CHttpException(404, \Yii::t('block_market', 'Не удалось найти сайт ID').' '.$siteId);
        }

        return $this->site;
    }
}