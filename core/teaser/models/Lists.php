<?php

namespace models;

use core\RedisIO;
use core\Session;
use models\dataSource\ListsDataSource;
use core\CRUDInterface;
use MLF\layers\Logic;

/**
 * @property ListsDataSource $nextLayer
 */

class Lists extends Logic implements CRUDInterface
{
    const WHITE_LIST = 1;
    const BLACK_LIST = 2;

    private $id;

    public $name;
    public $type;
    public $userId;
    public $sites;
    public $campaigns;
    public $description;

    /**
     * @return Lists
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getAll($params = [])
    {
        $userId = isset($params['userId']) ? $params['userId'] : Session::getInstance()->getUserId();;

        if(!$params)
            $params['userId'] = $userId;

        return $this->nextLayer->getAll($params);
    }

    public function save($data = [])
    {

    }

    public function saveList($data = [])
    {
        if($data)
            $this->setData($data);
        else
            $data = $this->getData();
        if(!$data)
            throw new \LogicException('data not given');

        if(!isset($data['userId']))
            $data['userId'] = Session::getInstance()->getUserId();

        $result = false;

        if($this->id = $this->nextLayer->save($data)){

            $result = Campaign::getInstance()->setLists($data);
            if(!$result)
                $this->nextLayer->delete($this->id);
        }

        return $result;
    }

    public function updateLists($data = [], $oldCampaigns = [])
    {
        if($data)
            $this->setData($data);
        else
            $data = $this->getData();

        if(!isset($data['userId']))
            $data['userId'] = Session::getInstance()->getUserId();

        if(!$data)
            throw new \LogicException('data not given');

        $result = false;

        $campaignsToClean = array_diff($oldCampaigns, $data['campaigns']);

        if($this->nextLayer->update($data)){
            $result = Campaign::getInstance()->setLists($data, $campaignsToClean);
            if(!$result)
                $this->nextLayer->delete($this->id);
        }

        return $result;
    }

    public function delete($id = null)
    {
        if($id === null)
            $id = $this->id;
        else
            $this->initById($id);

        if(Campaign::getInstance()->removeLists($this)){
            return $this->nextLayer->delete($id);
        }else{
            throw new \LogicException('can not clear blocks info');
        }
    }

    public function initById($id = null, $userId = null)
    {
        if($id === null)
            throw new \LogicException('get no ID');

        if(!$userId)
            $userId = Session::getInstance()->getUserId();

        $params = [
            'id' => $id,
            'userId' => $userId,
        ];

        return $this->setData($this->nextLayer->getById($params));
    }

    public function getId()
    {
        return $this->id;
    }

    public function setData($data = [])
    {
        $data = (array)$data;
        if(!$data)
            return false;
        if(isset($data['id']))
            $this->id = intval($data['id']);
        if(isset($data['name']))
            $this->name = (string)$data['name'];
        if(isset($data['type']))
            $this->type = intval($data['type']);
        if(isset($data['userId']))
            $this->userId = intval($data['userId']);
        if(isset($data['sites']))
            $this->sites = (array)$data['sites'];
        if(isset($data['campaigns']))
            $this->campaigns = (array)$data['campaigns'];
        if(isset($data['description']))
            $this->description = (string)$data['description'];

        return true;
    }

    public function getAllCampaigns()
    {
        return $this->nextLayer->getAllCampaigns();
    }

    public function getData()
    {
        $data = [];

        if($this->id)
            $data['id'] = $this->id;
        if($this->name)
            $data['name'] = $this->name;
        if($this->type)
            $data['type'] = $this->type;
        if($this->userId)
            $data['userId'] = $this->userId;
        if($this->sites)
            $data['sites'] = $this->sites;
        if($this->campaigns)
            $data['campaigns'] = $this->campaigns;
        if($this->description)
            $data['description'] = $this->description;

        return $data;
    }

    public function update()
    {

    }
}