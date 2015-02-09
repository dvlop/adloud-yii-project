<?php
namespace application\modules\payment\components;

use application\components\ControllerAdvertiser;
use application\modules\payment\PaymentModule;
use application\modules\payment\extensions\curl\Curl;
use application\modules\payment\extensions\currency\Currency;
use core\RedisIO;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 25.04.14
 * Time: 11:06
 * @property string $balance
 * @property string $orderId
 * @property string $error
 * @property \WebUser $user
 * @property int $money
 * @property array $sideMenu
 * @property array $transactions
 */
class ExternalPart extends \CComponent
{
    private $_error;
    private $_money;
    private $_user;

    public function getBalance()
    {
        if($this->user->balance !== false){
            return $this->user->balance ? $this->user->balance : 0;
        }else{
            $this->_error = $this->user->error;
            return false;
        }
    }

    public function getOrderId($money=0)
    {
        if(!$money)
            $money = $this->money;

        if(!$money){
            $this->_error = 'Переданы неверные данные для создания заказа: нулевая сума денег';
            return false;
        }

        return $this->user->setMoneyRequest($money);
    }

    public function setMoney($money=0)
    {
        $money = (int)$money;
        if(!$money){
            $this->_error = 'Сумма должна быть больше нуля';
            return false;
        }

        $this->_money = $money;
        return true;
    }

    public function getMoney()
    {
        return $this->_money;
    }

    public function getError()
    {
        return $this->_error;
    }

    public function getSideMenu()
    {
        \Yii::import('application.controllers.ControllerAdvertiser');
        return (new ControllerAdvertiser('payment'))->sideMenu;
    }

    public function getTransactions()
    {
        return [];
    }

    public function sessionSet($name, $value)
    {
        if(is_array($value) || is_object($value))
            $value = json_encode($value);
        RedisIO::set($name, $value);
    }

    public function sessionGet($name)
    {
        $value = RedisIO::get($name);
        if(strpos($value, '{') !== false && strpos($value, '}') !== false && strpos($value, ':') !== false)
            $value = json_decode($value);

        return $value;
    }

    public function sessionUnset($name)
    {
        RedisIO::delete($name);
    }

    public function loadClassFromFile($fileName, $class)
    {
        if(!is_string($fileName) || $fileName === ''){
            $this->error = 'Необходимо указать путь к файлу, чтобы загрузить класс';
            return false;
        }

        if(!file_exists($fileName)){
            $this->error = 'Нет такого файла "'.$fileName.'"';
            return false;
        }else{

            if(strpos($class, '.') !== false){
                $className = explode('.', $class);
                $className = ucfirst(end($className));
            }else{
                $className = explode('/', $class);
                $className = ucfirst(end($className));
            }

            if(!class_exists($className)){
                $this->error = 'Не удалось загрузить класс "'.$className.'"';
                return false;
            }else{
                return new $className();
            }
        }
    }

    /**
     * @param null $id
     * @return \WebUser|\models\User|null
     */
    public function getUser($id = null)
    {
        if($this->_user === null){
            if($id === null){
                $this->_user = \Yii::app()->user;
            }else{
                $user = \models\User::getInstance();
                try{
                    if(!$user->initById($id)){
                        $this->_error = 'Не найден пользователь с  ID '.$id;
                        return null;
                    }

                    $this->_user = $user;
                }catch(\Exception $e){
                    $this->_error = $e->getMessage();
                    return null;
                }
            }
        }

        return $this->_user;
    }

    public function checkUserId($id)
    {
        return $this->getUser()->checkUserId($id);
    }

    public function addMoneyToBalance($money = 0, $params = [])
    {
        $money = floatval($money);
        if($money <= 0){
            $this->_error = 'Сумма должна быть больше 0';
            return false;
        }

        if(!isset($params['userId']) || !$params['userId']){
            $this->_error = 'Необходим параметр "userId"';
            return false;
        }

        $user = \models\User::getInstance();

        try{
            if(!$user->initById($params['userId'])){
                $this->_error = 'Не найден пользователь с  ID '.$params['userId'];
                return false;
            }

            $message = 'Пополнение счёта рекламодателя. Доп. параметры: (сумма: '.$money;

            if(isset($params['system']))
                $message .= '; система: '.$params['system'];
            if(isset($params['orderId']))
                $message .= '; номер платежа: '.$params['orderId'];

            $message .= ')';

            $result = $user->addMoneyBalance($money, $message);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }

        if(!$result)
            $this->_error = 'Не удалось пополнить баланс пользователя';

        return $result;
    }
}