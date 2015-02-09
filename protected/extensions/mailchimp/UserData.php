<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 05.06.14
 * Time: 15:16
 */

class UserData
{
    private $_error;

    public $name;
    public $firstName;
    public $lastName;
    public $email;
    public $description;
    public $askUser = true;
    public $emailType = 'html';

    public function __construct($userData)
    {
        if(is_array($userData) || is_object($userData)){

            foreach($userData as $name=>$value){
                if(property_exists($this, $name)){
                    $this->$name = (string)$value;
                }
            }

            if(!$this->email)
                $this->_error = 'Not correct data: mast have string type property "email"';
        }else{
            $this->_error = 'Not correct data format: mast by an array or object';
        }
    }

    public function getParams()
    {
        $params = [];

        if($this->firstName)
            $params['MERGE1'] = $this->firstName;

        if($this->lastName)
            $params['MERGE2'] = $this->lastName;

        if(!$params)
            $params = null;

        return $params;
    }

    public function getError()
    {
        return $this->_error;
    }
}