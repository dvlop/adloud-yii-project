<?php

namespace application\modules\payment\models;

use application\modules\payment\PaymentModule;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 25.04.14
 * Time: 10:30
 * @property string $form;
 * @property PaymentModule $module
 * @property array $creditCards
 * @property array $currencies
 * @property string $formFields
 */
class PaymentForm extends \CFormModel
{
    public $formAttributes;
    public  $currency;
    public $money;

    private $_module;

    public function attributeLabels()
    {
        return array();
    }

    public function rules()
    {
        return array();
    }

    public function getForm($data=[])
    {
        if(!$data)
            $data = $this->formAttributes;

        if(!is_array($data) || empty($data)){
            $this->addError(null, 'Для создания формы отправки платёжных данных необходимы поля со значениями. Форма: '.get_class($this).', платёжная система: '.$this->module->id);
            return null;
        }

        $form = '';
        foreach($data AS $fieldKey => $fieldValue) {
            $form .= \CHtml::hiddenField($fieldKey, $fieldValue).' ';
        }
        $form .= '';

        return $form;
    }

    public function getModule()
    {
        if($this->_module === null){
            if(!isset(\Yii::app()->controller->module)){
                $this->addError(null, 'Не правильное обращение к модели '.__CLASS__.': модель вызывается только внутри модуля "payment"');
                $this->_module = false;
            }elseif(get_class(\Yii::app()->controller->module) != 'PaymentModule'){
                $this->addError(null, 'Не правильное обращение к модели '.__CLASS__.': модель вызывается только внутри модуля "payment"');
                $this->_module = false;
            }else{
                $this->_module = @\Yii::app()->controller->module;
            }
        }

        return $this->_module;
    }

    public function setModule(PaymentModule $module)
    {
        $this->_module = @$module;
    }

    public function getCurrencies()
    {
        return $this->module->currencies;
    }
} 