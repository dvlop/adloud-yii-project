<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 09.06.14
 * Time: 14:14
 */

class AdminFinanceForm extends FormModel {

    public $email;
    public $amount;
    public $description;

    public function attributeLabels()
    {
        return array(
            'amount' => 'Сумма',
            'email' => 'Email',
            'description' => 'Описание платежа',
        );
    }

    public function rules()
    {
        return array(
            array('amount,email,description', 'required', 'message' => 'Поле "{attribute}" должно быть заполнено'),
            array('amount', 'type', 'type' => 'float', 'message' => 'Поле {attribute} может содержать только числовые значения'),
        );
    }

    public function makeTransaction(){
        try{

            $user = \models\User::getInstance();
            $user->initByEmail($this->email);

            $tm = new \core\TransactionManager();

            $transaction = new \core\MoneyTransaction();
            $transaction->setType(\core\MoneyTransaction::SYSTEM_TO_WEBMASTER);
            $transaction->setRecipient($user->getId());
            $transaction->setAmount($this->amount);
            $transaction->setDescription($this->description);

            $tm->register($transaction);
            $tm->execute();

        } catch (Exception $e){
            $this->addError('error',$e->getMessage());
            return false;
        }
        return true;
    }
}