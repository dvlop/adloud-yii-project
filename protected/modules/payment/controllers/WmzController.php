<?php

namespace application\modules\payment\controllers;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 25.04.14
 * Time: 13:34
 * @property array $attributes
 * @property array $form
 */
class WmzController extends PaymentController
{
    public $serviceUrl = 'https://merchant.webmoney.ru/lmi/payment.asp';
    public $systemCode = 'WMZ';

    public function actionResult($userId = null)
    {
        if(isset($_POST['LMI_PREREQUEST'])){
            $this->checkPreRequest($userId);
            \Yii::app()->end('YES');
        }elseif(isset($_POST['LMI_HASH'])){
            $this->pay($userId);
        }
        \Yii::app()->end();
    }

    public function actionSuccess()
    {
        $this->checkResult();
    }

    public function actionFail()
    {
        \Yii::app()->user->setFlash('error', 'К сожалению не удалсь пополнить счёт. Попробуйте позже.');
        $this->redirect(\Yii::app()->createUrl($this->module->baseUrl));
    }
}
