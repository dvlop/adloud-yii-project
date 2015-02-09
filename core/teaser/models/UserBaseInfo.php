<?php

namespace models;
use core\CRUDInterface;
use models\dataSource\UserBaseInfoDataSource;

/**
 * Class UserBaseInfo
 * @package models
 * @property UserBaseInfoDataSource $nextLayer
 */
class UserBaseInfo extends \MLF\layers\Logic implements CRUDInterface
{
    public $siteUrl;
    public $desiredProfit;
    public $statLink;
    public $statLogin;
    public $statPassword;
    public $description;

    private $userId;
    private $id;

    /**
     * @return \models\UserBaseInfo
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function initById($id)
    {
        $result = $this->find(['where' => ['user_id' => $id]]);
        if(is_array($result) && !empty($result)){
            $data = $result[0];
            unset($result);

            $this->id = $data->id;
            $this->userId = $data->user_id;
            $this->siteUrl = trim($data->site_url);
            $this->desiredProfit = $data->desired_profit;
            $this->statLink = trim($data->stat_link);
            $this->statLogin = trim($data->stat_login);
            $this->statPassword = trim($data->stat_password);
            $this->description = trim($data->description);

            unset($data);
        }

        return $this;
    }

    public function save($id = null)
    {
        $data = get_object_vars($this);

        unset($data['nextLayer'], $data['id']);

        if(!$data['userId'])
            $data['userId'] = \core\Session::getInstance()->getUserId();

        if(!$data)
            throw new \LogicException('can not find UserBaseInfo object');

        return $this->nextLayer->save($data);
    }

    public function update($id = null)
    {
        if(!$id)
            $id = $this->id;

        if(!$id)
            return false;

        $data = [];

        if($this->siteUrl)
            $data['site_url'] = (string)$this->siteUrl;
        if($this->desiredProfit)
            $data['desired_profit'] = (int)$this->desiredProfit;
        if($this->statLink)
            $data['stat_link'] = (string)$this->statLink;
        if($this->statLogin)
            $data['stat_login'] = (string)$this->statLogin;
        if($this->statPassword)
            $data['stat_password'] = (string)$this->statPassword;
        if($this->description)
            $data['description'] = (string)$this->description;
        if($this->userId)
            $data['user_id'] = (int)$this->userId;

        if(empty($data))
            return false;

        return $this->nextLayer->update($data, $id);
    }

    public function delete($id)
    {

    }
}