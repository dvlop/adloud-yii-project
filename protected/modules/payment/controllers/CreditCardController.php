<?php

namespace application\modules\payment\controllers;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 25.04.14
 * Time: 13:34
 */
class CreditCardController extends PaymentController
{
    public function actionResult($user = null, $hash = null)
    {
        $this->checkResult($user, $hash);
    }

    public function actionSuccess($user = null, $hash = null)
    {
        $this->checkResult($user, $hash);
    }

    public function actionFail()
    {
        $this->fail();
    }

    public function actionAddMoney()
    {
        \Yii::app()->test->show([$_GET, $_POST]);
    }
} 