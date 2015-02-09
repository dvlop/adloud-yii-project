<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 27.03.14
 * Time: 22:31
 */

class AdminForm extends CFormModel {
    public $amount;
    public $description;
    public $requestId;
    public $userId;

    public function attributeLabels()
    {
        return [
            'amount' => 'Вывести сумму',
            'description' => 'Номер оплаты платежной системы',
        ];
    }

    public function rules()
    {
        return [
            ['amount', 'required', 'message' => 'Поле "{attribute}" должно быть заполнено', 'on' => ['applyMoneyTransaction']],
            ['description', 'required', 'message' => 'Поле "{attribute}" должно быть заполнено', 'on' => ['applyMoneyTransaction']],
            ['amount', 'numerical', 'min' => 0.01, 'message' => 'Поле {attribute} может содержать только числовые значения'],
            ['amount, description', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function applyMoneyTransaction(){
        try{
            return \models\MoneyPayouts::getInstance()->applyPayoutRequest($this->requestId, $this->userId, $this->amount, $this->description);
        } catch(Exception $e){
            return false;
        }
    }
} 