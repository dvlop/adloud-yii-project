<?php

namespace application\modules\payment\components;

use application\modules\payment\PaymentModule;
use application\modules\payment\models\PaymentForm;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 24.04.14
 * Time: 19:04
 */
class CreditCard extends PaymentModule
{
    public $serviceUrl = 'https://merchant.webmoney.ru/lmi/payment.asp';

    public function getFormFields()
    {
        if($this->money)
            $orderId = $this->orderId;
        else
            $orderId = 1;

        $fields = array(
            'LMI_ALLOW_SDP' => 10,
            'LMI_PAYMENT_DESC' => 'Пополнение счёта в системе '.\Yii::app()->name,
            'LMI_PAYMENT_DESC_BASE64' => base64_encode('Пополнение счёта в системе '.\Yii::app()->name),
            'LMI_PAYEE_PURSE' => $this->accountNumber,
            'LMI_PAYMENT_NO' => $orderId,
            //'LMI_RESULT_URL' => $this->createResultUrl(),
            //'LMI_SUCCESS_URL' => $this->createSuccessUrl(),
            //'LMI_FAIL_URL' => $this->createFailUrl(),
        );

        return array_merge(parent::getFormFields(), $fields);
    }

    public function getHiddenFields()
    {
        $model = new PaymentForm();
        $model->formAttributes = $this->formFields;
        return $model->form;
    }

    public function form()
    {
        $model = new PaymentForm();
        $model->formAttributes = $this->formFields;

        return \Yii::app()->controller->renderPartial($this->parentModule->id.'.themes.'.$this->theme.'.views._partials.form', [
                'model' => $model,
                'url' => $this->serviceUrl,
                'paymentSystem' => $this->paymentNames[$this->id],
                'money' => $this->money,
                'currency' => $this->currency,
                'formFields' => $model->form,
                'moneyName' => 'LMI_PAYMENT_AMOUNT',
            ], 1);
    }
}