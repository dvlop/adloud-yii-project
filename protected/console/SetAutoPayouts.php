<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.08.14
 * Time: 18:33
 */

namespace application\console;

use application\models\Users;
use application\models\UserPayoutRequest;

class SetAutoPayouts extends Console
{
    private $minSum = 5;
    private $_userIds = [];

    public function initialise(array $params)
    {
        if($params){
            $this->minSum = floatval($params[0]);
        }
    }

    public function run()
    {
        foreach(Users::model()->findAll() as $user){
            if($user->money >= $this->minSum){
                $this->_userIds[] = $user->id;
            }
        }

        return $this->setPayments();
    }

    private function setPayments()
    {
        if(!$this->_userIds){
            $this->setError('There are no members who have longer enough money');
            return false;
        }

        $result = false;

        foreach($this->_userIds as $id){
            $model = new UserPayoutRequest();

            if($model->getActivePayment($id) && $model->getActivePayment($id)->status == UserPayoutRequest::STATUS_IN_WORK)
                continue;

            if($model->addPrepayment($id))
                $result = true;
            else
                $this->setError('Unable to establish payment for the user ID '.$id);
        }

        if(!$result)
            $this->setError('All users have ordered payments');

        return $result;
    }
}