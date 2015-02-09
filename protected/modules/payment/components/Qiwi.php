<?php
namespace application\modules\payment\components;

use application\modules\payment\PaymentModule;
use application\modules\payment\models\PaymentForm;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 24.04.14
 * Time: 19:03
 */
class Qiwi extends PaymentModule
{
    public $serviceUrl = 'https://w.qiwi.com/order/external/create.action';

    public function getFormFields()
    {
        if($this->money)
            $orderId = $this->orderId;
        else
            $orderId = 1;

        $fields = array(
            'from' => $this->accountNumber,
            'currency' => $this->currency,
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
                'method' => 'GET',
                'paymentSystem' => $this->paymentNames[$this->id],
                'money' => $this->money,
                'moneyName' => 'summ',
                'currency' => $this->currency,
                'formFields' => $model->form,
                'additionalFormFields' => [
                    (object)[
                        'label' => 'Номер телефона',
                        'name' => 'to',
                        'placeholder' => 'пример: +380112223344',
                    ],
                    (object)[
                        'label' => 'Комментарий',
                        'name' => 'com',
                        'inputValue' => 'Пополнение счёта в платёжной системе '.\Yii::app()->name,
                        'input' => 'textarea',
                    ],
                ],
            ], 1);
    }

    public function iframe()
    {
        return '';
    }
}