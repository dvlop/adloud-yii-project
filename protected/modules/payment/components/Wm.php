<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 05.08.14
 * Time: 17:18
 */

namespace application\modules\payment\components;

use application\modules\payment\PaymentModule;
use application\modules\payment\models\PaymentForm;

class WM extends PaymentModule
{
    public $serviceUrl = 'https://merchant.webmoney.ru/lmi/payment.asp';

    public function getFormFields()
    {
        if($this->money)
            $orderId = $this->orderId;
        else
            $orderId = 1;

        $fields = array(
            'LMI_PAYMENT_DESC' => 'Пополнение счёта в системе '.\Yii::app()->name,
            'LMI_PAYMENT_DESC_BASE64' => base64_encode('Пополнение счёта в системе '.\Yii::app()->name),
            'LMI_PAYEE_PURSE' => $this->accountNumber,
            'LMI_PAYMENT_NO' => $orderId,
            'LMI_RESULT_URL' => $this->createResultUrl(),
            //'LMI_SUCCESS_URL' => $this->createSuccessUrl(),
            //'LMI_FAIL_URL' => $this->createFailUrl(),
            'LMI_SIM_MODE' => 0,
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

    public function iframe()
    {
        return '';
    }

    public function getRequestParamsNames()
    {
        return [
            'mode' => 'LMI_MODE',
            'paymentNum' => 'LMI_PAYMENT_NO',           // номер покупки, назначенный продавцом
            'payeeWmNum' => 'LMI_PAYEE_PURSE',          // кошелек продавца, на который покупатель совершил платеж.
            'money' => 'LMI_PAYMENT_AMOUNT',            // сумма, которую заплатил покупатель
            'payerWmNum' => 'LMI_PAYER_PURSE',          // кошелек покупателя, с которого он совершил платеж
            'payeeWMID' => 'LMI_PAYER_WM',              // WMID покупателя
            'WMCardNum' => 'LMI_PAYMER_NUMBER',         // номер WM-карты или чека Paymer, если была оплата WM-картой
            'payeePhone' => 'LMI_WMCHECK_NUMBER',       // номер телефона покупателя, если была оплата с WebMoney Check
            'hash' => 'LMI_HASH',                       // контрольная подпись.
            'dateTime' => 'LMI_SYS_TRANS_DATE',         // дата и время совершения платежа с точностью до секунд
            'orderId' => 'LMI_TELEPAT_ORDERID',         // ID транзакции
            'invsNum' => 'LMI_SYS_INVS_NO',
            'transNum' => 'LMI_SYS_TRANS_NO'
        ];
    }

    public function checkRequest($params = [])
    {
        if($params)
            $this->setRequestParams($params);

        $params = $this->getRequestParams();

        return ($this->getSecretHash() !== null && $this->getSecretHash() == $params['hash']);
    }

    public function setHash()
    {
        $params = $this->getRequestParams();

        $string =   $params['payeeWmNum'].
                    $params['money'].
                    $params['paymentNum'].
                    $params['mode'].
                    $params['invsNum'].
                    $params['transNum'].
                    $params['dateTime'].
                    $this->clientSecret.
                    $params['payerWmNum'].
                    $params['payeeWMID'];

        $this->setSecretHash($string);
    }
} 