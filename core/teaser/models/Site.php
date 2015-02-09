<?php
/**
 * Created by t0m
 * Date: 02.02.14
 * Time: 15:35
 */

namespace models;


use core\Session;
use core\CRUDInterface;
use models\dataSource\SiteDataSource;
/**
 * @property SiteDataSource $nextLayer
 */
class Site extends \MLF\layers\Logic implements CRUDInterface
{
    /*<-- old statuses -->*/
    const STATUS_BLOCS_DISABLED = 300;
    const STATUS_NO_MODERATED = 0;
    const STATUS_MODERATED = 1;
    /*<-- /old statuses -->*/

    /*<-- actual statuses -->*/
    const STATUS_PROHIBITED = 200;
    const STATUS_DISABLED = 2;
    const STATUS_PUBLISHED = 3;
    const STATUS_ARCHIVED = 500;
    /*<-- /actual statuses -->*/

    private static $statuses = [
        /*<-- old statuses -->*/
        self::STATUS_BLOCS_DISABLED => 'Рекламные блоки деактивированы',
        self::STATUS_NO_MODERATED => 'Сайт на модерации',
        self::STATUS_MODERATED => 'Сайт промодерирован',
        /*<-- /old statuses -->*/

        /*<-- actual statuses -->*/
        self::STATUS_PROHIBITED => 'Сайт не допущен',
        self::STATUS_DISABLED => 'Сайт не опубликован',
        self::STATUS_PUBLISHED => 'Сайт опубликован',
        self::STATUS_ARCHIVED => 'Сайт в архиве',
        /*<-- /actual statuses -->*/
    ];

    private static $statusesClasses = [
        /*<-- old statuses -->*/
        self::STATUS_BLOCS_DISABLED => 'switch',
        self::STATUS_NO_MODERATED => 'switch suspended',
        self::STATUS_MODERATED => 'switch',
        /*<-- /old statuses -->*/

        /*<-- actual statuses -->*/
        self::STATUS_PROHIBITED => 'switch stopped',
        self::STATUS_DISABLED => 'switch',
        self::STATUS_PUBLISHED => 'switch',
        self::STATUS_ARCHIVED => 'switch stopped',
        /*<-- /actual statuses -->*/
    ];

    public $url;
    public $mirror;
    public $category;
    public $additionalCategory;
    public $bannedCategories;
    public $description;
    public $statsUrl;
    public $statsLogin;
    public $statsPassword;
    public $containsAdult = false;
    public $allowShock = false;
    public $allowAdult = false;
    public $allowSms = false;
    public $allowAnimation = false;
    public $status = 0;
    public $createDate;
    public $moderated = 200;

    private $id;
    private $userId;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return \models\Site
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getId(){
        return $this->id;
    }

    public function getIsModerated($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;
        return $statusId == self::STATUS_MODERATED || $statusId == self::STATUS_DISABLED || $statusId == self::STATUS_PUBLISHED;
    }

    public function getIsBlocksDisabled($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;
        return $statusId === self::STATUS_BLOCS_DISABLED || $statusId === self::STATUS_DISABLED;
    }

    public function getAllowStatusChange($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        return $statusId != self::STATUS_PROHIBITED;
    }

    public function getIsEnabled($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;
        return $statusId == self::STATUS_PUBLISHED;
    }

    public function getStatusName($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        if(!isset(self::$statuses[$statusId]))
            throw new \LogicException('not correct status id: '.var_dump($statusId));

        return self::$statuses[$statusId];
    }

    public function getStatusClass($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        if(!isset(self::$statusesClasses[$statusId]))
            throw new \LogicException('not correct status id: '.var_dump($statusId));

        return self::$statusesClasses[$statusId];
    }

    public function getCategoryName($catId = null)
    {
        if($catId === null)
            $catId = $this->category; //description
        if($result = Category::getInstance()->getCategoryNames([$catId])){
            if(isset($result[0]['description']))
                $result = $result[0]['description'];
        }
        return (string)$result;
    }

    public function save()
    {

    }

    public function saveSite($date = null)
    {
        if($date === null)
            $date = date($this->config->getDateFormat());
        $blockId = $this->nextLayer->save(array_merge($this->getData(), [
            'date' => $date,
            'status' => self::STATUS_NO_MODERATED,
        ]));
        $this->id = $blockId;
        return $blockId;
    }

    public function update()
    {
        $result = $this->nextLayer->update($this->getData());

        if($result){
            $categories = [];
            $categories[] = $this->category;
            if($this->additionalCategory)
                $categories[] = $this->additionalCategory;
            try{
                \models\Block::getInstance()->setCategories($this->id, $categories);
                Publisher::getInstance()->unPublishSite($this->id);
                Publisher::getInstance()->publishSite($this->id);
            }catch(\Exception $e){

            }
        }

        return $result;
    }

    public function getList($params = []){
        return $this->nextLayer->getList(array_merge($params, ['userId' => Session::getInstance()->getUserId()]));
    }

    public function getStatsList($params)
    {
        $params['user_id'] = Session::getInstance()->getUserId();
        $params['status_archived'] = self::STATUS_ARCHIVED;
        return $this->nextLayer->getStatsList($params);
    }

    public function getSites($params){
        $params['user_id'] = Session::getInstance()->getUserId();
        return $this->nextLayer->getSites($params);
    }

    /**
     * @param $id
     * @return bool
     */
    public function initById($id)
    {
        $data = $this->nextLayer->initById($id, Session::getInstance()->getUserId());

        if(!$data){
            return false;
        }

        $this->id = $data['id'];
        $this->url = trim($data['url']);
        $this->mirror = trim($data['mirror']);
        $this->userId = $data['userId'];
        $this->category = $data['category'];
        $this->additionalCategory = $data['additionalCategory'];
        $this->bannedCategories = $data['bannedCategories'];
        $this->description = trim($data['description']);
        $this->statsUrl = trim($data['statsUrl']);
        $this->statsLogin = trim($data['statsLogin']);
        $this->statsPassword = trim($data['statsPassword']);
        $this->containsAdult = $data['containsAdult'];
        $this->allowShock = $data['allowShock'];
        $this->allowAdult = $data['allowAdult'];
        $this->allowSms = $data['allowSms'];
        $this->allowAnimation = $data['allowAnimation'];
        $this->status = $data['status'];
        $this->createDate = $data['date'];
        $this->moderated = $data['moderated']; //publish

        return true;
    }

    public function delete($id = null)
    {
        if($id === null)
            $id = $this->id;

        return Publisher::getInstance()->unPublishDeletedSite($id);
    }

    public function setStatus($statusId, $id = null)
    {
        if($id === null)
            $id = $this->id;

        if(!$this->initById($id))
            throw new \LogicException('site is not found');

        return $this->nextLayer->setStatus($id, $statusId);
    }

    public function publish($id = null)
    {
        if($id === null)
            $id = $this->id;

        if(!$this->initById($id))
            throw new \LogicException('site is not found');

        if(!$this->getAllowStatusChange($this->moderated))
            throw new \LogicException('can not publish site with this status');

        $result = Publisher::getInstance()->publishSite($id);

        if($result){
            $result = $this->nextLayer->setStatus($id, self::STATUS_PUBLISHED);
        }

        return $result;
    }

    public function unPublish($id = null)
    {
        if($id === null)
            $id = $this->id;

        if(!$this->initById($id))
            throw new \LogicException('site is not found');

        if(!$this->getAllowStatusChange($this->moderated))
            throw new \LogicException('can not unpublish site with this status');

        $result = Publisher::getInstance()->unPublishSite($id);

        if($result){
            $result = $this->nextLayer->setModerated($id, self::STATUS_DISABLED);
        }

        return $result;
    }

    public function getStats($id = null){
        return $this->nextLayer->getStats( $id? $id : $this->id);
    }

    protected function getData()
    {
        return [
            'id' => $this->id,
            'url' => trim($this->url),
            'mirror' => trim($this->mirror),
            'userId' => Session::getInstance()->getUserId(),
            'category' => $this->category,
            'additionalCategory' => $this->additionalCategory,
            'bannedCategories' => $this->bannedCategories,
            'description' => trim($this->description),
            'statsUrl' => trim($this->statsUrl),
            'statsLogin' => trim($this->statsLogin),
            'statsPassword' => trim($this->statsPassword),
            'containsAdult' => $this->containsAdult,
            'allowShock' => $this->allowShock,
            'allowAdult' => $this->allowAdult,
            'allowSms' => $this->allowSms,
            'allowAnimation' => $this->allowAnimation,
            'status' => $this->status,
            'moderated' => $this->status == self::STATUS_MODERATED || self::STATUS_DISABLED,
        ];
    }
}