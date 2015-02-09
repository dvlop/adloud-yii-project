<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 07.07.14
 * Time: 22:32
 */

namespace application\modules\webmaster\controllers;

use application\components\ControllerWebmaster;
use application\models\UserPayoutRequest;

class MoneyController extends ControllerWebmaster
{
    public function actionIndex()
    {
        $model = new UserPayoutRequest('search');

        $this->pageName = \Yii::t('webmaster_money', 'Вывод средств');

        $this->render('index', [
            'model' => $model,
            'activePayment' => $model->getActivePayment(),
            'dataProvider' => $model->search(),
        ]);
    }

    public function actionPrepayment()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['moneyPrepayment'])){
            $json = [
                'message' => '',
                'html' => '',
                'error' => '',
            ];

            if(floatval(\Yii::app()->user->balance) < \Yii::app()->params['moneyOutLimit']){
                $json['error'] = 'Количество денег для вывода не может быть меньше '.\Yii::app()->params['moneyOutLimit'].' долларов';
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            $model = new UserPayoutRequest();


            if($model->addPrepayment()){
                $json['message'] = 'Запрос на вывод средств успешно отправлен. Номер зароса: '.$model->id;
                $json['html'] = [
                    'title' => $model->getPaymentDate(),
                    'row' => $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.webmaster.partials._payoutRow', ['payout' => $model], true),
                ];
            }else{
                $json['error'] = $this->parseError($model->errors, 'Не удалось отправить запрос на вывод средств', 'Пожалуйста, попробуйте позже');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionTransactionStats()
    {

    }
} 