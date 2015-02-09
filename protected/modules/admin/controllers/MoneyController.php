<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.07.14
 * Time: 14:26
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\UserPayoutRequest;
use application\models\ReferalStats;
use application\models\Transactions;
use application\models\TicketCategory;

class MoneyController extends ControllerAdmin
{
    public function actionPrepaymentRequestList($status = null)
    {
        $model = new UserPayoutRequest();
        $model->setStatus($status);

        $attributes = [];
        if($model->status != UserPayoutRequest::STATUS_ALL)
            $attributes['status'] = $model->status;

        $this->render('prepaymentRequestList', [
            'model' => $model,
            'payments' => $model->findAllByAttributes($attributes, ['order' => 'id desc']),
            'ticketCategories' => TicketCategory::model()->findAll(),
        ]);
    }

    public function actionReferalsPaymentList($status = null) {
        $model = new ReferalStats();
        $model->setStatus($status);

        $attributes = [];
        if($model->status != ReferalStats::STATUS_ALL)
            $attributes = ['status' => $model->status];

        $this->render('referalsPaymentList', [
            'model' => $model,
            'payments' => $model->findAllByAttributes($attributes, ['order' => 'id desc']),
        ]);
    }

    public function actionTransactionList()
    {
        $model = new Transactions();

        if(\Yii::app()->getRequest()->getIsAjaxRequest()){
            echo \CJSON::encode($model->getAjaxData($_GET));
        }else{
            $this->render('transactionList', [
                'columns' => $model->getTableColumns(),
            ]);
        }
    }

    public function actionPayout($id, $userId, $state)
    {
        $state = ($state == 'true') ? true : false;
        try{
            if($state){
                $amount = -1;
                $description = '';

                $user = \models\User::getInstance();
                $user->initById($userId);

                if($user->addMoneyBalance($amount, $description)){
                    \Yii::app()->user->setFlash('success', "Запрос на вывод денег успешно обработан.");
                } else {
                    \Yii::app()->user->setFlash('error', "Произошла ошибка! Попробуйте еще раз.");
                }
            } else {
                \Yii::app()->user->setFlash('success', "Запрос на отказ успешно обработан.");
            }
        } catch (\Exception $e) {
            \Yii::app()->user->setFlash('error', "Произошла ошибка! Попробуйте еще раз. " . $e->getMessage());
        }

        $this->redirect(\Yii::app()->createUrl('admin/money/prepaymentRequestList'));
    }


    // not checked
    public function actionIndex(){

        $model = new \AdminFinanceForm();
        if(\Yii::app()->request->isPostRequest){
            $model->attributes = $_POST['AdminFinanceForm'];
            if($model->validate()){
                $model->makeTransaction();
                if($model->errors){
                    $this->setFlash('error', $model->errors['error'][0]);
                }
            }
        }

        $this->render('adminFinance', array('model' => $model));
    }

    // not checked
    public function actionSetPrepaymentDate($id = null)
    {
        if($id !== null && \Yii::app()->request->isAjaxRequest && isset($_POST['dateValue'])){
            $json = [
                'massage' => '',
                'error' => '',
                'html' => '',
            ];

            try{
                if(\models\MoneyPayouts::getInstance()->setPaymentDate($_POST['dateValue'], $id)){
                    $json['massage'] = 'Дата для автоматической выплаты номер '.$id.' успешн установлена!';
                }else{
                    $json['error'] = 'Не удалось установить дату автоматической выплаты номер '.$id;
                }
            }catch(\Exception $e){
                $json['error'] = 'Не удалось установить дату выплаты: '.$e->getMessage();
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    // not checked
    public function actionSetPrepaymentStatus($id, $state = null, $status = null)
    {
        if($id !== null && \Yii::app()->request->isAjaxRequest && isset($_POST['prepaymentStatus'])){
            $json = [
                'massage' => '',
                'error' => '',
                'html' => '',
            ];

            $baseMessage = '';
            $baseError = '';
            $state = intval($state);

            $model = UserPayoutRequest::model()->findByPk($id);

            if(!$model){
                $json['error'] = 'Нет запроса на выплату ID '.$id;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            if($state == $model->statusOpened){
                $baseError = 'Не удалось взять в работу предварительную оплату номер '.$id.': ';
                $baseMessage = 'Выплата номер '.$id.' успешно взята в работу';
                $method = 'activate';
            }elseif($state == $model->statusDone){
                $baseError = 'Не удалось подтвердить предварительную оплату номер '.$id.': ';
                $baseMessage = 'Выплата номер '.$id.' успешно совершена';
                try{
                    \models\MoneyPayouts::getInstance()->outWebmasterMoney($model->userId, $model->amount);
                    $this->sendNotification($model->userId,'payment-success');
                }catch(\Exception $e){
                    $json['error'] = $this->parseError($e->getMessage(), 'Не удалось выплатить деньги');
                    echo \CJSON::encode($json);
                    \Yii::app()->end();
                }
                $method = 'activate';
            }elseif($state == $model->statusRejected){
                $baseError = 'Не удалось отклонить предварительную оплату номер '.$id.': ';
                $baseMessage = 'Выплата номер '.$id.' успешно отклонена';
                $method = 'deActivate';
                $this->sendNotification($model->userId,'payment-rejected');
            }else{
                $json['error'] = 'Неправльный номер статуса: '.$state;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            $model->setPayoutDate();
            $model->status = $state;

            if($model->update(['payout_date', 'status'])){
                $json['success'] = $baseMessage;
                $model->setStatus($status);

                $status = $model->status;
                $model->setStatus($state);

                if($status == UserPayoutRequest::STATUS_ALL)
                    $json['html'] = $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.admin.money._prepaymentRequestTableRow', ['payment' => $model, 'status' => $model->status], true);
                else
                    $json['html'] = 'remove-this';
            }
            else
                $json['error'] = $this->parseError($model->errors, $baseError);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    // not checked
    public function actionModerateReferalRequest($id = null)
    {
        if($id !== null && \Yii::app()->request->isAjaxRequest && isset($_POST['moderateReferal'])){
            $json = [
                'massage' => '',
                'error' => '',
                'html' => '',
            ];

            $activate = $_POST['moderateReferal'] === 'true' ? true : false;

            if($activate){
                $baseError = 'Не удалось подтвердить реферальную выплату номер '.$id.': ';
                $baseMessage = 'Реферальная выплата номер '.$id.' успешно подтверждена';
                $method = 'acceptRequest';
            }else{
                $baseError = 'Не удалось отклонить реферальную выплату номер '.$id.': ';
                $baseMessage = 'Реферальная выплата номер '.$id.' успешно отклонена';
                $method = 'denyRequest';
            }

            try{
                if(\models\Referals::getInstance()->$method($id)){
                    $json['massage'] = $baseMessage;
                }else{
                    $json['error'] = $baseError;
                }
            }catch(\Exception $e){
                $json['error'] = $baseError.$e->getMessage();
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}