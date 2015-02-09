<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 04.09.14
 * Time: 13:25
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\advertiser\controllers;

use application\components\ControllerAdvertiser;
use application\models\TargetList;
use core\RedisIO;
use application\models\Users;
use core\Session;

class TargetController  extends ControllerAdvertiser
{
    public function actionList()
    {
        $model = new TargetList();
        $model->user_id = Session::getInstance()->getUserId();

        if(\Yii::app()->request->isPostRequest && isset($_POST[$model->getModelName()])){
            $model->setAttributes($_POST[$model->getModelName()]);

            if($model->save()){
                $message = 'Таргет список успешно сохранен.';

                \Yii::app()->user->setFlash('success', $message);

                if(!$model->hasErrors()){
                    $this->redirect(\Yii::app()->createUrl('advertiser/target/list'));
                }
            }else{
                $this->setFlash($model->errors, 'К сожалению не удалось сохранить список');
            }
        }

        $this->breadcrumbs[\Yii::app()->createUrl($this->getId().'/'.$this->action->getId())] = 'Ретаргетинг';
        $showModal = false;

        if($blockId = \Yii::app()->session['blockId']){
            $this->modalContent = $this->getModalCode($blockId);
            unset(\Yii::app()->session['blockId']);
            $showModal = true;
        }

        $targetList = TargetList::model()->findAllByAttributes([
            'user_id' => Session::getInstance()->getUserId(),
            'status' => TargetList::LIST_STATUS_ACTIVE
        ],[
            'order' => 'id desc'
        ]);

        foreach($targetList as $key => $target){
            $target->shows = RedisIO::get("target-users:{$target->id}") ? RedisIO::get("target-users:{$target->id}") : 0;
            $targetList[$key] = $target;
        }

        $this->render('list', [
            'targetList' => $targetList,
            'showModal' => $showModal,
            'model' => $model
        ]);
    }

    public function actionDelete($id = null)
    {
        $block = TargetList::model()->findByPk($id);
        try{
            if($block->remove()){
                \Yii::app()->user->setFlash('success', 'Список успешно удален!');
            }else{
                \Yii::app()->user->setFlash('error', 'Произошла ошибка! Попробуйте еще раз.');
            }
        }catch(\Exception $e){
            $this->setFlash($e->getMessage(), 'Произошла ошибка', 'Попробуйте еще раз');
        }

        $this->redirect(\Yii::app()->createUrl('advertiser/target/list'));
    }

    public function actionCodemodal($id)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['showBlockCode'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => '',
            ];

            $data = $this->getModalCode($id);

            $data['title'] = 'Код для вставки на сайт';
            $data['subtitle'] = '';

            $json['html'] = $this->partial('_mainModalContent', ['data' => $data], true);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}