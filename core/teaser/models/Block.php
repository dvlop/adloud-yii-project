<?php

namespace models;
use config\Config;
use core\CRUDInterface;
use core\RedisIO;
use core\Session;
use models\dataSource\BlockDataSource;
use templates\TemplateManager;

/**
 * @property BlockDataSource $nextLayer
 */
class Block extends \MLF\layers\Logic implements CRUDInterface
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    const STATUS_ARCHIVED = 500;

    const FORMAT_ADS_MINIMAL = 'AdsMinimal';
    const FORMAT_ADS_MINIMAL_LONG = 'AdsMinimalLong';
    const FORMAT_ADS_STANDARD = 'AdsStandard';
    const FORMAT_ADS_STANDARD_LONG = 'AdsStandardLong';
    const FORMAT_ADS_NEW_STANDARD = 'AdsNewStandard';
    const FORMAT_ADS_NEW_STANDARD_LONG = 'AdsNewStandardLong';
    const FORMAT_ADS_MAIN = 'AdsMain';
    const FORMAT_ADS_SIMPLE = 'AdsSimple';
    const FORMAT_ADS_MARKET = 'AdsMarket';

    const BLOCK_LIGHT_FORMAT = 'light';
    const BLOCK_PRO_FORMAT = 'pro';

    private static $statuses = [
        self::STATUS_DISABLED => 'Блок выключен',
        self::STATUS_ENABLED => 'Блок опубликован',
        self::STATUS_ARCHIVED => 'Блок в архиве',
    ];

    private static $statusesClasses = [
        self::STATUS_DISABLED => 'switch',
        self::STATUS_ENABLED => 'switch',
        self::STATUS_ARCHIVED => 'switch',
    ];

    public static $_formats = [
        self::FORMAT_ADS_MINIMAL,
        self::FORMAT_ADS_STANDARD,
        self::FORMAT_ADS_NEW_STANDARD,
    ];

    private static $_splitTypes = [
        '240x400',
        '240x4400',
    ];

    public static $_availableSplitTest = [
        '240x400' => [
            self::FORMAT_ADS_MINIMAL,
            self::FORMAT_ADS_STANDARD,
            self::FORMAT_ADS_NEW_STANDARD
        ],
        '240x4400' => [
            self::FORMAT_ADS_MINIMAL,
            self::FORMAT_ADS_STANDARD,
            self::FORMAT_ADS_NEW_STANDARD
        ]
    ];

    private static $_newFormats = [
        '160x300',
        '200x300',
        '240x300',
        '240x134',
        '300x125',
        '300x150',
        '336x140',
        '160x280',
        '200x90',
        '200x350',
        '240x100',
        '240x120',
        '300x150',
        '320x160',
    ];

    private static $_blockFormats = [
        self::BLOCK_LIGHT_FORMAT => 'Формат "Light"',
        self::BLOCK_PRO_FORMAT => 'Формат "PRO"',
    ];

    public $description;
    public $size = '160x600';
    public $color = 'standart';
    public $bg = 'gray';
    public $name;
    public $status = self::STATUS_DISABLED;
    public $siteId;
    public $categories;
    public $adsType;
    public $allowShock = false;
    public $allowAdult = false;
    public $allowSms = false;
    public $allowAnimation = false;
    public $fillType;
    public $shows;
    public $clicks;
    public $siteModerated = false;
    public $createDate;
    public $type;
    /**
     * @var BlockParams
     */
    public $params;

    private $id;
    private $blockIncome;
    private $siteStatus;

    /**
     * @return \models\Block
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id = null)
    {
        if($id !== null){
            $id = (int)$id;
            if($id)
                $this->id = $id;
        }
    }

    public function update($id = null)
    {
        if($id === null)
            $id = $this->id;

        return $this->nextLayer->update($this->setAttributes(), Session::getInstance()->getUserId());
    }

    public function changeStatus($id = null, $status = null)
    {
        if($id === null)
            $id = $this->id;

        if($status === null)
            $status = $this->status;

        return $this->nextLayer->changeStatus(['id' => $id, 'status' => $status]);
    }

    public function saveBlock($date = null)
    {
        if($date === null)
            $date = date(Config::getInstance()->getDateFormat());

        $blockId = $this->nextLayer->save(array_merge($this->setAttributes(), [
            'date' => $date,
            'userId' => Session::getInstance()->getUserId(),
            'categories' => $this->getCategories(),
        ]));
        $this->id = $blockId;

        return $this->id;
    }

    public function getList($params = [])
    {
        $list = $this->nextLayer->getList(array_merge($params, ['userId' => Session::getInstance()->getUserId()]));

        foreach($list as $num=>$block){
            $params = $block['content'] ? $block['content'] : [];
            $list[$num]['params'] = new BlockParams($params);
        }

        return $list;
    }

    public function getStatsList($params)
    {
        $params['user_id'] = Session::getInstance()->getUserId();
        return $this->nextLayer->getStatsList($params);
    }

    public function getBlocks($params){
        $params['user_id'] = Session::getInstance()->getUserId();
        return $this->nextLayer->getBlocks($params);
    }

    public function getAllUsersBlock($params = [])
    {
        return $this->nextLayer->getAllUsersBlock($params);
    }

    public function getSiteModerated($blockId = null)
    {
        if($blockId !== null && !$this->initById($blockId))
            throw new \LogicException('block ID '.$blockId.' does not exist');

        if($this->id === null)
            throw new \LogicException('need block ID');

        return Site::getInstance()->getIsModerated($this->siteStatus);
    }

    public static function getBlockFormats()
    {
        return self::$_blockFormats;
    }

    public function init($id = null)
    {
        if($id !== null){
            $this->initById($id);
        }else{
            $this->params = new BlockParams([]);
        }

        return $this;
    }

    public function initById($id, $idAdmin = false)
    {
        $data = $this->nextLayer->initById($id);
        if(!$data){
            return false;
        }

        $this->id = $data['id'];
        $this->shows = $data['shows'];
        $this->clicks = $data['clicks'];
        $this->siteId = $data['siteId'];
        $this->categories = $data['categories'];
        $this->description = trim($data['description']);
        $this->status = $data['status'];
        $this->color = trim($data['color']);
        $this->bg = trim($data['bg']);
        $this->size = trim($data['size']);
        $this->allowShock = $data['allowShock'];
        $this->allowAdult = $data['allowAdult'];
        $this->allowSms = $data['allowSms'];
        $this->allowAnimation = $data['allowAnimation'];
        $this->siteModerated = $data['siteModerated'] == Site::STATUS_MODERATED;
        $this->blockIncome = $data['blockIncome'];
        $this->createDate = $data['date'];
        $this->type = $data['type'] ? trim($data['type']) : self::BLOCK_LIGHT_FORMAT;
        $this->siteStatus = (int)$data['siteStatus'];

        $params = $data['content'] ? $data['content'] : [];
        $this->params = new BlockParams($params);

        return true;
    }

    public function getBlockIncome(){
        return $this->blockIncome;
    }

    public function getStatus($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        return $statusId == self::STATUS_ENABLED;
    }

    public function getIsEnabled($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        return $statusId === self::STATUS_ENABLED;
    }

    public function getStatusName($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        if(!isset(self::$statuses[$statusId]))
            throw new \LogicException('invalid status id');

        return self::$statuses[$statusId];
    }

    public function getStatusClass($statusId = null)
    {
        if($statusId === null)
            $statusId = $this->status;

        if(!isset(self::$statusesClasses[$statusId]))
            throw new \LogicException('invalid status id');

        return self::$statusesClasses[$statusId];
    }

    public function getCategories($id = null)
    {
        if($this->categories)
            return $this->categories;

        $categories = [];
        if($id !== null){
            if(!$this->initById($id))
                return $categories;
        }

        $siteCategories = \models\Site::getInstance()->findById(['category', 'additional_category'], $this->siteId);

        if($siteCategories){
            $categories[] = $siteCategories->category;
            if($siteCategories->additional_category)
                $categories[] = $siteCategories->additional_category;
        }

        return $this->categories = $categories;
    }

    public function getCategoriesNames($cats = null)
    {
        if($cats === null){
            if(!$cats = $this->categories)
                return [];
        }

        return $this->nextLayer->getCatsNames($cats);
    }

    public function delete($id)
    {
        return Publisher::getInstance()->unPublishDeletedBlock($id, true);
    }

    public function getInsertCode(){
        if($this->type == self::BLOCK_LIGHT_FORMAT)
            return TemplateManager::renderInsertCode($this);
        else
            return $this->getNewInsertCode();
    }

    public function getNewInsertCode(){
        return TemplateManager::renderNewInsertCode($this);
    }

    public function setCategories($siteId, $categories = null)
    {
        if($categories === null)
            return false;

        return $this->nextLayer->setCategories($siteId, $categories);
    }

    public static function getFormats()
    {
        $formats = [];

        foreach(self::$_formats as $format){
            if(RedisIO::get("block-type-status-{$format}"))
                $formats[] = $format;
        }

        return $formats;
    }

    public static function getAllFormats()
    {
        return self::$_formats;
    }

    public static function getSplitTypes()
    {
        return self::$_splitTypes;
    }

    public static function getNewFormats()
    {
        return self::$_newFormats;
    }

    public static function changeFormatStatus($format, $status)
    {
        if(!in_array($format, self::$_formats))
            throw new \LogicException('no such format');

        if($status)
            return RedisIO::set("block-type-status-{$format}", 1);
        else
            return RedisIO::delete("block-type-status-{$format}");
    }

    public function changeAdsInBlockStatus($adsId, $blockId, $status)
    {
        if($status) {
            return $this->nextLayer->DisbanAdsInBlock($adsId, $blockId);
        } else {
            return $this->nextLayer->BanAdsInBlock($adsId, $blockId);
        }
    }

    protected function setAttributes()
    {
        $params = [];

        if($this->id !== null)
            $params['id'] = $this->id;
        $params['name'] = trim($this->name);
        $params['description'] = trim($this->description);
        $params['size'] = trim($this->size);
        $params['color'] = trim($this->color);
        $params['bg'] = trim($this->bg);
        $params['status'] = $this->status;
        $params['siteId'] = $this->siteId;
        $params['allowShock'] = $this->allowShock;
        $params['allowAdult'] = $this->allowAdult;
        $params['allowSms'] = $this->allowSms;
        $params['allowAnimation'] = $this->allowAnimation;
        $params['content'] = $this->params->getSerialized();
        $params['type'] = $this->type ? $this->type : self::BLOCK_LIGHT_FORMAT;
        $params['createDate'] = (new \DateTime())->format(Config::getInstance()->getDateFormat());

        return $params;
    }

    public function save()
    {

    }

    public static  function getSplitTestTypesByFormat($format){
        $splitTestType = RedisIO::get("splitTestTypes:{$format}");
        if(!$splitTestType){
            return [];
        }
        return unserialize($splitTestType);
    }

    public static function addSplitTestTypeToFormat($format, $type){
        $splitTestType = RedisIO::get("splitTestTypes:{$format}");
        $splitTestType = $splitTestType ? unserialize($splitTestType) : [];
        $splitTestType[] = $type;
        return RedisIO::set("splitTestTypes:{$format}", serialize(array_unique($splitTestType)));
    }

    public static function removeTestTypeToFormat($format, $type){
        $splitTestType = RedisIO::get("splitTestTypes:{$format}");
        $splitTestType = $splitTestType ? unserialize($splitTestType) : [];

        if($splitTestType){
            foreach($splitTestType as $key=>$st){
                if($type == $st){
                    unset($splitTestType[$key]);
                }
            }
        }
        RedisIO::set("splitTestTypes:{$format}", serialize($splitTestType));

        return true;
    }

    public static function stopFormatSplitTest($format){
        foreach(self::$_formats as $type){
            RedisIO::delete("block-type-shows:{$type}_{$format}");
            RedisIO::delete("block-type-clicks:{$type}_{$format}");
            self::removeTestTypeToFormat($format, $type);
        }
        RedisIO::set("splitTestTypes:{$format}", serialize([]));
    }

    public static function getTypeSplitTestStats($format, $type){
        return [
            'shows' => \core\RedisIO::get("block-type-shows:{$type}_{$format}"),
            'clicks' => \core\RedisIO::get("block-type-clicks:{$type}_{$format}")
        ];
    }

    public static function getFormatSplitTestStats($format, $realFormat = null){
        $splitTestTypes = Block::getSplitTestTypesByFormat($format);
        $typeStats = [];
        $formatForSearch = $realFormat ? $realFormat : $format;
        $formats = in_array($formatForSearch, array_keys(self::$_availableSplitTest)) ? self::$_availableSplitTest[$formatForSearch] : [];

        foreach($formats as $type){
            $stats = Block::getTypeSplitTestStats($format, $type);
            $typeStats[$type] = [
                'shows' => $stats['shows'],
                'clicks' => $stats['clicks'],
                'ctr' => \core\RatingManager::getCtr($stats['shows'], $stats['clicks']),
                'status' => in_array($type, $splitTestTypes),
            ];
        }
        return $typeStats;
    }
}