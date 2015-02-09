<?php

namespace application\modules\payment\components;

use application\modules\payment\PaymentModule;
use application\modules\payment\models\PaymentForm;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 24.04.14
 * Time: 19:02
 * @property array $formFields
 * @property string $iframe
 * @property string $iframeNotSelectPayment
 * @property string $iframeSelectPayment
 */
class YandexMoney extends PaymentModule
{
    public $clientSecret;
    public $selectYandexPayment = false;

    protected $myFormServiceUrl = 'https://money.yandex.ru/quickpay/confirm.xml';
    protected $iframeServiceUrl = 'https://money.yandex.ru/embed/shop.xml';

    public function getFormFields()
    {
        if($this->money)
            $orderId = $this->orderId;
        else
            $orderId = 1;

        $fields = array(
            'is-inner-form' => 'true',
            'label' => $orderId,
            'need-email' => 'true',
            'quickpay-form' => 'shop',
            'receiver' => $this->accountNumber,
            'submit-button' => 'Оплатить',
            'targets' => 'Транзакция '.$orderId,
            'paymentType' => 'PC',
            'comment' => 'Пополнение счёта в платёжной системе '.\Yii::app()->name,
            'formcomment' => $this->projectName ? $this->projectName : \Yii::app()->name,
            'redirect_uri' => $this->createResultUrl(),
        );

        return array_merge(parent::getFormFields(), $fields);
    }

    public function getHiddenFields()
    {
        $model = new PaymentForm();
        $model->formAttributes = $this->formFields;
        return $model->form;
    }

    public function iframe()
    {
        $this->serviceUrl = $this->iframeServiceUrl;

        return $this->selectYandexPayment ? $this->iframeSelectPayment : $this->iframenotSelectPayment;
    }

    public function form()
    {
        $this->serviceUrl = $this->myFormServiceUrl;

        $model = new PaymentForm();
        $model->formAttributes = $this->formFields;

        return \Yii::app()->controller->renderPartial($this->parentModule->id.'.themes.'.$this->theme.'.views._partials.form', [
            'url' => $this->serviceUrl,
            'model' => $model,
            'paymentSystem' => $this->paymentNames[$this->id],
            'money' => $this->money,
            'currency' => $this->currency,
            'formFields' => $model->form,
            'moneyName' => 'sum',
        ], 1);
    }

    protected function getIframeNotSelectPayment()
    {
        return '<iframe
            frameborder="0"
            allowtransparency="true"
            scrolling="no"
            src="'.$this->serviceUrl.'?account='.$this->accountNumber.'&quickpay=shop&writer=seller&targets=Пополнение счёта в платёжной системе '.\Yii::app()->name.'&targets-hint=&default-sum='.$this->money.'&button-text=01&mail=on"
        width="450" height="163"></iframe>';
    }

    protected function getIframeSelectPayment()
    {
        return '<iframe
            frameborder="0"
            allowtransparency="true"
            scrolling="no"
            src="'.$this->serviceUrl.'?account='.$this->accountNumber.'&quickpay=shop&payment-type-choice=on&writer=seller&targets=Пополнение счёта в платёжной системе '.\Yii::app()->name.'&targets-hint=&default-sum='.$this->money.'&button-text=01"
            width="450" height="200"></iframe>';
    }
}