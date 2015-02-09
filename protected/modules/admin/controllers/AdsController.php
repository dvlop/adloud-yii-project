<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.07.14
 * Time: 14:06
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\Ads;
use application\models\Notification;

class AdsController extends ControllerAdmin
{
    public function actionIndex($id, $status = null)
    {
        $this->redirect(\Yii::app()->createUrl('admin/ads/list', ['status' => $status]));
    }

    public function actionList($status = null)
    {
        $model = new Ads();
        $model->setStatus($status);

        $attributes = [];
        if($model->status != Ads::STATUS_ALL){
            if($model->status == Ads::STATUS_PUBLISHED)
                $attributes['status'] = [Ads::STATUS_PUBLISHED, Ads::STATUS_DISABLED];
            else
                $attributes['status'] = $model->status;
        }

        $this->render('list', [
            'adsList' => $model->findAllByAttributes($attributes, ['order' => 'id desc']),
            'model' => $model
        ]);
    }

    public function actionSetStatus($id, $state, $status = null)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['setAdsStatus'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            $id = intval($id);
            $state = intval($state);

            $model = Ads::model()->findByPk($id);
            if(!$model){
                $json['error'] = $this->parseError($model->errors, 'К сожалению не удалось найти тизер ID '.$id);
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            $model->updateStatus($state);

            if($model->hasErrors()){
                $json['error'] = $this->parseError($model->errors, 'Промодерировано с ошибкой', '', true);
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            $json['success'] = $state == Ads::STATUS_PUBLISHED ? 'Тизер подверждён' : 'Тизер отклонён';

                if($status == Ads::STATUS_ALL)
                    $json['html'] = $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.admin.ads._adsTableRow', ['ads' => $model, 'status' => $model->status], true);
                else
                    $json['html'] = 'remove-this';

                switch($model->status){
                    case Ads::STATUS_PUBLISHED:
                        $this->sendNotification($model->getUserId(), Notification::TYPE_ADS_MODERATED);
                        break;
                    case Ads::STATUS_PROHIBITED:
                        $this->sendNotification($model->getUserId(), Notification::TYPE_ADS_REJECTED);
                        break;
                    default:
                        break;
                }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionSetshock($id)
    {
        $this->setBolVal($id, 'shock');
    }

    public function actionSetAdult($id)
    {
        $this->setBolVal($id, 'adult');
    }

    public function actionGetModal()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['adsId'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            $model = Ads::model()->findByPk(\Yii::app()->request->getPost('adsId'));
            if($model)
                $message = $model->getAdminMessage();
            else
                $message = '';

            $modalContent = [
                'title' => 'Укажите причину отказа',
                'content' => $message,
                'buttonOk' => 'Применить',
                'buttonCancel' => 'Отмена',
            ];

            $json['html'] = $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.partials._mainModalContent', ['data' => $modalContent], true);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    private function setBolVal($id, $name)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['value'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            $model = Ads::model()->findByPk((int)$id);

            if($model === null){
                $json['error'] = 'Не найдено объявление ID'.$id;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            $val = \Yii::app()->request->getPost('value') === 'true';

            switch($name){
                case 'shock':
                    $result = $model->updateshock($val);
                    break;
                case 'adult':
                    $result = $model->updateAdult($val);
                    break;
                default:
                    $result = false;
                    break;
            }

            if($result)
                $json['success'] = 'Значение атрибута '.$model->getAttributeLabel($name).' успешно изменено';
            else
                $json['error'] = $this->parseError($model->errors, 'Не удалось изменить значение атрибута '.$model->getAttributeLabel($name));

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}