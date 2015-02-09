<?php
namespace core;
/**
 * Created by t0m
 * Date: 29.12.13
 * Time: 20:35
 */

class Session
{
    private static $_instance;
    private $_connection;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getConnection($dbName = 'persistent_data')
    {
        if($this->_connection === null){
            $this->_connection = PostgreSQL::getInstance()->getConnection(Session::getInstance(), $dbName);
        }
        return $this->_connection;
    }

    public function setUserId($id){
        $sql = 'SELECT "access_level" FROM "users" WHErE "id" = :id';
        $statement = $this->getConnection()->prepare($sql);
        $success = $statement->execute(array(':id' => $id));
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if(!$success || !$data){
            return;
        }
        $_SESSION['_TEASER_USER_ID'] = $id;
        $_SESSION['_TEASER_ACCESS_LEVEL'] = $data['access_level'];
    }

    public function getUserId(){
        if(!isset($_SESSION['_TEASER_USER_ID'])){
            throw new \exceptions\DataLayerException('session is not initialized');
        }
        return $_SESSION['_TEASER_USER_ID'];
    }

    public function getUserAccessLevel(){
        if(!isset($_SESSION['_TEASER_ACCESS_LEVEL'])){
            throw new \exceptions\DataLayerException('session is not initialized');
        }
        return $_SESSION['_TEASER_ACCESS_LEVEL'];
    }


}