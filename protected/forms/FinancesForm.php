<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 05.03.14
 * Time: 1:33
 */

class FinancesForm extends CFormModel {
    public $amount;

    public function attributeLabels()
    {
        return array(
            'amount' => 'Сумма',
        );
    }

    public function rules()
    {
        return array(
            array('amount', 'required', 'message' => 'Поле {attribute} должно быть заполнено', 'on' => array('addMoney', 'getMoney')),
            array('amount', 'numerical', 'min' => 0.01, 'message' => 'Поле {attribute} может содержать только числовые значения'),
        );
    }

    private function createTransaction()
    {
        return \models\User::getInstance()->addMoneyRequest($this->amount);
    }

    public function getPaymentForm()
    {
        $dataFormationForm = array();

        $orderId = $this->createTransaction();

        $dataFormationForm['OrderId'] = $orderId;
        $dataFormationForm['PaySum'] = $this->amount;

        $dataFormationForm['LMI_PAYMENT_DESC'] = 'Платеж по счету';
        $dataFormationForm['LMI_PAYMENT_DESC_BASE64'] = base64_encode('Платеж по счету');
        $dataFormationForm['LMI_PAYEE_PURSE'] = \Yii::app()->params->payment_WMWallet;
        $dataFormationForm['LMI_PAYMENT_NO'] = $orderId;
        $dataFormationForm['LMI_PAYMENT_AMOUNT'] = $this->amount;
        $dataFormationForm['LMI_RESULT_URL'] = \Yii::app()->createAbsoluteUrl("advertiser/moneyPayResul");
        $dataFormationForm['LMI_SUCCESS_URL'] = \Yii::app()->createAbsoluteUrl("advertiser/moneyPaySuccess");
        $dataFormationForm['LMI_FAIL_URL'] = \Yii::app()->createAbsoluteUrl("advertiser/moneyPayFail");

        return $this->formationForm($dataFormationForm);
    }

    private function formationForm($data)
    {
        $fields = array();
        foreach($data AS $fieldKey => $fieldValue) {
            $fields[] = '<input type="hidden" name="' . $fieldKey . '" value="' . $fieldValue . '">';
        }

        $form = '<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp">';
        $form .= implode(' ', $fields);
        $form .= '<input type="submit" class="btn-u" value="Оплатить">';
        $form .= '</form>';

        return $form;
    }

    public function addMoney()
    {
        $user = \models\User::getInstance();
        $user->initById(\core\Session::getInstance()->getUserId());
        try {
            return $user->addMoneyBalance($this->amount, rand(10000, 99999));
        } catch (Exception $e) {
            $this->addError(null,'Возникла ошибка при пополнении баланса. Попробуйте еще раз позже.');
            return false;
        }
    }

    public function getMoney()
    {
        $user = \models\User::getInstance();
        $user->initById(\core\Session::getInstance()->getUserId());
        try {
            return $user->requestMoneyPayout($this->amount);
        } catch (Exception $e) {
            switch($e->getMessage()){
                case 'you have active requests':
                    $this->addError('getMoney','У вас есть необработаные запросы.');
                    break;
                case 'not enough money':
                    $this->addError('getMoney','У вас не достаточно денег.');
                    break;
                default:
                    $this->addError('getMoney','Возникла ошибка при снятии денег. Попробуйте еще раз позже.');
                    break;
            }

            return false;
        }
    }

}