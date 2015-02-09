<?php

namespace application\modules\payment\models;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 23.04.14
 * Time: 18:56
 * @property array $paymentSystems
 * @property string $paymentSystem
 * @property string $creditCard
 */
class MoneyForm extends PaymentForm
{
    public $paymentSystem;
    public $currency;
    public $money;

    public function attributeLabels()
    {
        return array(
            'paymentSystems' => 'Платёжные системы',
            'creditCards' => 'Кредитные карты',
            'paymentSystem' => 'Платёжная система',
            'creditCard' => 'Кредитная карта',
            'currency' => 'Валюта',
            'money' => 'Деньги'
        );
    }

    public function rules()
    {
        return array(
            array('currency, money', 'required', 'message'=>'Поле {attribute} должно быть заполнено'),
            array('money', 'numerical', 'min' => 1, 'integerOnly' => true, 'message' => 'Поле {attribute} может содержать только числовые значения и должно быть больше нуля'),
            array('currency', 'length', 'min' => 2, 'max'=>'3', 'message' => 'Код валюты должен состоять из 2-х или 3-х символов'),
            array('paymentSystem', 'length', 'min' => 2, 'message' => 'Название плтёжной системы состоять минимум из 2-х символов'),
            array('paymentSystem', 'default'),
        );
    }

    public function getPaymentSystems()
    {
        if($this->paymentSystem === null){
            $names = array_keys($this->module->paymentSystems);
            $this->paymentSystem = array_shift($names);
        }
        return $this->module->paymentSystems;
    }
}