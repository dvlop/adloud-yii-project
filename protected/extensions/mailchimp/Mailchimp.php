<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 05.06.14
 * Time: 15:02
 * @property MCAPI $api
 * @property UserData[] $userData
 * @property UserData $user
 * @property string $error
 */

class Mailchimp extends \CComponent
{
    public $baseUrl = 'http://api.mailchimp.com/';
    public $listName;
    public $apiUrl;
    public $timeout;
    public $chunkSize;
    public $apiKey;
    public $secure = false;
    public $askUser;
    public $emailType;

    private $_mcapi;
    private $_users = [];
    private $_errors = [];

    public function init()
    {
        \Yii::import('application.extensions.mailchimp.MCAPI');
        \Yii::import('application.extensions.mailchimp.UserData');

        $this->_mcapi = new MCAPI($this->apiKey, $this->secure, $this->baseUrl);
        if($this->apiUrl !== null)
            $this->_mcapi->apiUrl = $this->apiUrl;
        if($this->timeout !== null)
            $this->_mcapi->timeout = $this->timeout;
        if($this->chunkSize !== null)
            $this->_mcapi->chunkSize = $this->chunkSize;
    }

    public function addUser($data = null)
    {
        if($data !== null){
            if(!$this->setUser($data))
                return false;
        }

        return $this->addUserToSystem(end($this->_users));
    }

    public function addUsers($data = null)
    {
        if($data !== null){
            if(!$this->setUsers($data))
                return false;
        }

        $result = true;
        foreach($this->_users as $user){
            $tmpRes = $this->addUserToSystem($user);
            if($result)
                $result = $tmpRes;
        }

        return $result;
    }

    public function setUser($userInfo = null)
    {
        $user = new UserData($userInfo);
        if($error = $user->getError()){
            $this->_errors[] = $error;
            return false;
        }

        if(isset($this->_users[$user->email])){
            $this->_errors[] = 'This user already added to system: '.$user->email;
            return false;
        }

        if($this->askUser !== null)
            $user->askUser = $this->askUser;
        if($this->emailType !== null)
            $user->emailType = $this->emailType;

        $this->_users[$user->email] = $user;

        return count($this->_errors) === 0;
    }

    public function setUsers($data = [])
    {
        if(!is_array($data)){
            $this->_errors[] = 'not correct data format. Mast be array';
            return false;
        }

        if(!$data)
            $data = $this->_users;

        foreach($data as $user){
            $this->addUser($user);
        }

        return count($this->_errors) === 0;
    }

    public function getApi()
    {
        return $this->_mcapi;
    }

    public function getUser()
    {
        return end($this->_users);
    }

    public function getUsers()
    {
        return $this->_users;
    }

    public function getError()
    {
        return implode('; ', $this->_errors);
    }

    private function addUserToSystem(UserData $user)
    {
        if($user->askUser === null)
            $result = $this->api->listSubscribe($this->listName, $user->email, $user->getParams(), $user->emailType);
        else
            $result = $this->api->listSubscribe($this->listName, $user->email, $user->getParams(), $user->emailType, $user->askUser);

        if(!$result)
            $this->_errors[] = $this->api->errorMessage;

        return $result;
    }
}