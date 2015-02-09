<?php

namespace application\models;

use application\components\BaseModel;
use core\RedisIO;
use models\Publisher;

/**
 * This is the model class for table "sites".
 *
 * The followings are the available columns in table 'sites':
 * @property integer $user_id
 * @property string $url
 * @property integer $id
 * @property integer $status
 * @property boolean $allow_shock
 * @property boolean $allow_adult
 * @property integer $category
 * @property integer $additional_category
 * @property string $mirror
 * @property string $description
 * @property string $stats_url
 * @property string $stats_login
 * @property string $stats_password
 * @property boolean $contains_adult
 * @property boolean $allow_sms
 * @property string $create_date
 * @property string $banned_categories
 * @property integer $moderated
 * @property boolean $allow_animation
 *
 * @property boolean $allowShock
 * @property boolean $allowAdult
 * @property string $statsUrl
 * @property string $statsLogin
 * @property string $statsPassword
 * @property boolean $containsAdult
 * @property boolean $allowSms
 * @property string $createDate
 * @property string $bannedCategories
 * @property boolean $allowAnimation
 * @property integer $categoryId
 *
 * @property \application\models\behaviors\PublishBehavior $publisher
 *
 *
 * The followings are the available model relations:
 * @property \application\models\Users $user
 * @property \application\models\Blocks[] $blocks
 * @property \application\models\WebmasterStats[] $webmasterStats
 * @property \application\models\Sites[] $list
 * @property \application\models\Categories[] $categories
 * @property \application\models\Categories[] $additionalCats
 * @property \application\models\Categories[] $allCategories
 * @property \application\models\Categories[] $bannedCats
 * @property \application\models\Categories $additionalCategoryModel
 * @property \application\models\Categories $categoryModel
 * @property int $additionalCategory
 * @property string $additionalCatName
 * @property int[] $bannedCatsIds
 * @property int $clicks
 * @property int $shows
 * @property int $ctr
 * @property string $categoryName
 * @property string $statusName
 * @property array $statuses
 * @property array $selectorStatuses
 */
class Sites extends BaseModel
{
    const STATUS_BLOCS_DISABLED = 300;
    const STATUS_NO_MODERATED = 0;
    const STATUS_MODERATED = 1;
    const STATUS_PROHIBITED = 200;
    const STATUS_DISABLED = 2;
    const STATUS_PUBLISHED = 3;
    const STATUS_ALL = 1000;
    const STATUS_ARCHIVED = 500;

    private static $_statuses = [
        self::STATUS_BLOCS_DISABLED => 'Рекламные блоки деактивированы',
        self::STATUS_NO_MODERATED => 'Сайт на модерации',
        self::STATUS_MODERATED => 'Сайт промодерирован',
        self::STATUS_PROHIBITED => 'Сайт не допущен',
        self::STATUS_DISABLED => 'Сайт не опубликован',
        self::STATUS_PUBLISHED => 'Сайт опубликован',
        self::STATUS_ALL => 'Все',
    ];

    private static $_urlStatuses = [
        self::STATUS_NO_MODERATED => 'На модерации',
        self::STATUS_PROHIBITED => 'Недопущенные',
        self::STATUS_PUBLISHED => 'Промодерированные',
        self::STATUS_ALL => 'Все',
    ];

    private static $_statusesClasses = [
        self::STATUS_BLOCS_DISABLED => 'switch',
        self::STATUS_NO_MODERATED => 'switch suspended',
        self::STATUS_MODERATED => 'switch',
        self::STATUS_PROHIBITED => 'switch stopped',
        self::STATUS_DISABLED => 'switch',
        self::STATUS_PUBLISHED => 'switch',
    ];

    private static $_listColumns = [
        'id',
        'url' => [
            'header' => 'Адрес сайта'
        ],
        'stats_url',
        'stats_login',
        'stats_password',
        /*'category' => [
            'header' => 'Категория',
            'value' => 'categoryName',
        ],*/
        'status' => [
            'value' => 'statusName',
        ],
    ];

    private static $_search = [
        'id' => [
            'partialMatch' => false,
        ],
        'url',
        'stats_url'
    ];

    private static $_sortColumns = [
        'id',
        'url',
        'stats_url',
        'stats_login',
    ];

    private $_category;
    private $_categories;
    private $_additionalCats;
    private $_allCats;
    private $_bannedCats;
    private $_clicks;
    private $_shows;
    private $_ctr;
    private $_status;
    private $_selectorStatuses;
    private $_deleted = false;
    private $_siteCats;

    private static $_categoryModel;
    private static $_additinalCategoryModel;


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sites';
    }

    public function init()
    {
        $this->status = null;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['user_id, url, category', 'required', 'message' => 'Поле "{attribute}" должно быть заполнено'],
            ['url', 'unique', 'message' => 'Такой сайт уже зарегистрирован в системе'],
            ['status, moderated, user_id, additional_category, category', 'numerical', 'integerOnly'=>true],
            ['url, mirror, stats_url', 'length', 'max'=>400],
            ['description', 'length', 'max'=>512],
            ['stats_login', 'length', 'max'=>40],
            ['stats_password', 'length', 'max'=>56],
            ['allow_shock, allow_adult, category, additional_category, contains_adult, allow_sms, create_date, banned_categories, allow_animation', 'safe'],
            ['url, mirror, stats_url, description, stats_login, stats_password, allow_shock, allow_adult, category, contains_adult, allow_sms, allow_animation', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related additional_category
        // class name for the relations automatically generated below.
        return [
            'user' => [self::BELONGS_TO, '\application\models\Users', 'user_id'],
            'blocks' => [self::HAS_MANY, '\application\models\Blocks', 'site_id'],
            'webmasterStats' => [self::HAS_MANY, '\application\models\WebmasterStats', 'site_id'],
            //'additionalCategoryModel' => [self::BELONGS_TO, '\application\models\Categories', 'additional_category'],
            //'categoryModel' => [self::BELONGS_TO, '\application\models\Categories', 'category'],
        ];
    }

    public function behaviors()
    {
        return [
            'publisher' => [
                'class' => 'application\models\behaviors\PublishBehavior',
            ],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'url' => \Yii::t('webmaster_site','Введите адрес сайта:'),
            'mirror' => \Yii::t('webmaster_site','Введите адрес зеркала сайта:'),
            'category' => \Yii::t('webmaster_site','Категория'),
            'categoryLabel' => \Yii::t('webmaster_site','Выберите категорию вашего сайта'),
            'additionalCategory' => \Yii::t('webmaster_site','Выберите дополнительную категорию'),
            'bannedCategories' => \Yii::t('webmaster_site','Исключить показ объявлений следующих категорий'),
            'description' => \Yii::t('webmaster_site','Описание'),
            'statsUrl' => \Yii::t('webmaster_site','Введите url доступа к статистике'),
            'statsLogin' => \Yii::t('webmaster_site','Введите логин доступа к статистике'),
            'statsPassword' => \Yii::t('webmaster_site','Введите пароль доступа к статистике'),
            'containsAdult' => \Yii::t('webmaster_site','Ваш сайт содержит контент для взрослых'),
            'allowAdult' => \Yii::t('webmaster_site','Не показывать рекламу для взрослых'),
            'allowShock' => \Yii::t('webmaster_site','Показывать только товарные тизеры'),
            'allowSms' => \Yii::t('webmaster_site','Не показывать рекламу сайтов с SMS оплатой'),
            'allowAnimation' => \Yii::t('webmaster_site','Не показывать анимированную рекламу'),
            'status' => \Yii::t('webmaster_site','Статус'),
            'userId' => \Yii::t('webmaster_site','User'),
            'id' => 'ID',
            'createDate' => 'Create Date',
            'moderated' => 'Moderated',
            'clicks' => \Yii::t('webmaster_site','Клики'),
            'shows' => \Yii::t('webmaster_site','Показы'),
            'ctr' => 'CTR',
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Sites the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Sites[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Sites
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($id)
    {
        $this->user_id = intval($id);
    }

    public function setCategory($value)
    {
        $this->category = intval($value) ? intval($value) : null;
    }

    public function getCategoryId()
    {
        return $this->category;
    }

    public function getAllowShock()
    {
        return (bool)$this->allow_shock;
    }

    public function setAllowShock($allow)
    {
        $this->allow_shock = (bool)$allow;
    }

    public function getAllowAdult()
    {
        return (bool)$this->allow_adult;
    }

    public function setAllowAdult($allow)
    {
        $this->allow_adult = (bool)$allow;
    }

    public function getAdditionalCategory()
    {
        return $this->additional_category;
    }

    public function setAdditionalCategory($cats)
    {
        $this->additional_category = intval($cats) ? intval($cats) : null;
    }

    public function getStatsUrl()
    {
        return $this->stats_url;
    }

    public function setStatsUrl($url)
    {
        $this->stats_url = $url;
    }

    public function getStatsLogin()
    {
        return $this->stats_login;
    }

    public function setStatsLogin($login)
    {
        $this->stats_login = $login;
    }

    public function getStatsPassword()
    {
        return $this->stats_password;
    }

    public function setStatsPassword($pass)
    {
        $this->stats_password = $pass;
    }

    public function getContainsAdult()
    {
        return $this->contains_adult;
    }

    public function setContainsAdult($cont)
    {
        $this->contains_adult = (bool)$cont;
    }

    public function getAllowSms()
    {
        return $this->allow_sms;
    }

    public function setAllowSms($allow)
    {
        $this->allow_sms = (bool)$allow;
    }

    public function getCreateDate()
    {
        return $this->create_date;
    }

    public function setCreateDate($date)
    {
        $this->create_date = $date;
    }

    public function getAllowAnimation()
    {
        return $this->allow_animation;
    }

    public function setAllowAnimation($allow)
    {
        $this->allow_animation = (bool)$allow;
    }

    public function setBannedCategories($ids)
    {
        if($ids && is_array($ids)){
            $ids = '{'.implode(',', $ids).'}';
        }elseif($ids && is_string($ids) && strpos($ids, '{') === false)
            $ids = '{'.$ids.'}';

        $this->banned_categories = $ids ? $ids : null;
    }

    public function getBannedCatsIds()
    {
        return $this->banned_categories;
    }

    /**
     * @param null $userId
     * @return \application\models\Sites[]
     */
    public function getList($userId = null)
    {
        if($userId === null)
            $userId = \Yii::app()->user->id;

        return $this->findAll('user_id = :user_id ORDER BY id DESC', [':user_id' => $userId]);
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getAllCategories()
    {
        if($this->_allCats === null){
            $this->_allCats = Categories::model()->findAll('active = 1 ORDER BY id');
        }

        return $this->_allCats;
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getCategories()
    {
        if($this->_categories === null){
            $this->_categories = [];

            foreach($this->getAllCategories() as $cat){
                $cat->isChecked = $this->category == $cat->id;
                $this->_categories[] = $cat;
            }
        }
        return $this->_categories;
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getSiteCats()
    {
        if($this->_siteCats === null){
            $ids = $this->additional_category ? [$this->category, $this->additional_category] : $this->category;
            $this->_siteCats = Categories::model()->findAllByAttributes(['id' => $ids]);
        }

        return $this->_siteCats;
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getAdditionalCats()
    {
        if($this->_additionalCats === null){
            $this->_additionalCats = [];

            foreach($this->allCategories as $cat){
                $cat->isChecked = $this->additional_category == $cat->id;
                $this->_additionalCats[] = $cat;
            }
        }
        return $this->_additionalCats;
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getBannedCats()
    {
        if($this->_bannedCats === null){
            $this->_bannedCats = [];
            $banned = $this->bannedCatsIds;
            foreach($this->getAllCategories() as $cat){
                $cat->isChecked = $banned && in_array($cat->id, $banned);
                $this->_bannedCats[] = $cat;
            }
        }

        return $this->_bannedCats;
    }

    public function getAdditionalCatName()
    {
        if($this->additionalCategory)
            return $this->additionalCategoryModel->description;
        else
            return '';
    }

    public function getClicks()
    {
        if($this->_clicks === null){
            $this->_clicks = rand(0, 10);
        }

        return $this->_clicks;
    }

    public function getShows()
    {
        if($this->_shows === null){
            $this->_shows = rand(0, 10)*10000;
        }

        return $this->_shows;
    }

    public function getCtr()
    {
        if($this->_ctr === null){
            if(!$this->getShows())
                $this->_ctr = 0;
            else
                $this->_ctr = round(($this->getClicks()/$this->getShows())*100, 3);
        }

        return $this->_ctr;
    }

    public function getStatusName()
    {
        if($this->_status === null){
            if($this->status === null)
                $this->status = self::STATUS_NO_MODERATED;
            $this->_status = isset(self::$_statuses[$this->status]) ? self::$_statuses[$this->status] : self::STATUS_NO_MODERATED;
        }

        return $this->_status;
    }

    public function getIsConfirm()
    {
        return $this->status === null || $this->status === self::STATUS_NO_MODERATED || $this->status === self::STATUS_PROHIBITED || $this->status === self::STATUS_DISABLED;
    }

    public function getIsReject()
    {
        return $this->status !== self::STATUS_PROHIBITED;
    }

    public function getStatuses()
    {
        return self::$_statuses;
    }

    /**
     * @return \stdClass[]
     */
    public function getSelectorStatuses()
    {
        if($this->_selectorStatuses === null){
            $this->_selectorStatuses = [];

            foreach(self::$_urlStatuses as $id => $name){
                $this->_selectorStatuses[] = (object)[
                    'name' => $name,
                    'value' => $id,
                    'checked' => $id == $this->status,
                ];
            }
        }

        return $this->_selectorStatuses;
    }

    /**
     * @return \application\models\Categories
     */
    public function getCategoryModel()
    {
        if(self::$_categoryModel == null){
            self::$_categoryModel = Categories::model()->findByPk($this->category);
        }

        return self::$_categoryModel;
    }

    /**
     * @return \application\models\Categories
     */
    public function getAdditionalCategoryModel()
    {
        if(self::$_additinalCategoryModel === null){
            self::$_additinalCategoryModel = Categories::model()->findByPk($this->additional_category);
        }
        return self::$_additinalCategoryModel;
    }

    public function getCategoryName()
    {
        if($this->_category === null){
            if($this->getCategoryModel() === null)
                $this->_category = '';
            else
                $this->_category = $this->getCategoryModel()->description;

            if($this->getAdditionalCategoryModel() !== null)
                $this->_category .= ', '.$this->getAdditionalCategoryModel()->description;
        }

        return $this->_category;

    }

    public function getUserEmail()
    {
        if($this->user === null)
            return null;
        else
            return $this->user->email;
    }

    public function getListColumns()
    {
        return self::$_listColumns;
    }

    public function getSearchColumns()
    {
        return self::$_search;
    }

    public function getSortColumns()
    {
        return self::$_sortColumns;
    }

    public function getAdditionalSearchColumns()
    {
        $attrs = [];

        $statQuery = $this->getQueryStatus();

        if($statQuery !== null){
            $attrs['status'] = [
                'name' => 'status',
                'value' => $statQuery,
                'partialMatch' => false,
                'operation' => 'AND'
            ];
        }

        return $attrs;
    }

    public function publish()
    {
        $this->status = self::STATUS_PUBLISHED;
        if($this->updateInfoBeforePublish(true, true))
            return $this->publisher->publish();
        else
            return false;
    }

    public function unPublish()
    {
        $this->status = $this->status == self::STATUS_PUBLISHED || $this->status === null ? self::STATUS_DISABLED : $this->status;

        if($this->update(['status']))
            return $this->publisher->unPublish();
        else
            return false;
    }

    public function beforeSave()
    {
        if(!$this->create_date)
            $this->create_date = (new \DateTime())->format(\Yii::app()->params['dateTimeFormat']);

        if($this->isNewRecord){
            if($this->status === null)
                $this->status = self::STATUS_NO_MODERATED;
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if($this->updateInfoBeforePublish(false)){
            if($this->status == self::STATUS_PUBLISHED && $this->blocks){
                foreach($this->blocks as $block){
                    $block->publisher->unPublish();
                    $block->publisher->publish();
                }
            }
        }

        parent::afterSave();
    }

    public function sendEmailAfterChange($forUser = false)
    {
        if($forUser){
            $email = $this->user ? $this->user->email : \Yii::app()->user->email;
            $view = 'toUserAboutSiteModerated';
            $isConfirm = self::STATUS_MODERATED || $this->status == self::STATUS_PUBLISHED;

            $subject = 'Ваш сайт был промодерирован администратором Adloud';
            $title = 'Администратор Adloud ';


            $title .= $this->status == $isConfirm ? 'Подтвердил' : 'Отклонил';
            $title .= ' Ваш сайт '.$this->url.' (ID: '.$this->id.')';

            $body = [
                'model' => $this,
                'title' => $title,
                'reason' => $isConfirm ? '' : 'Ибо нехуй',
            ];
        }else{
            $email =\Yii::app()->params['supportEmail'];
            $view = 'toAdminAboutSiteCreated';
            $subject = 'Новый сайт в системе';
            $title = 'Пользователь ';
            $title .= $this->_deleted ? 'Удалил' : 'Создал';
            $title .= ' сайт';

            $body = [
                'model' => $this,
                'title' => $title,
                'id' => $this->_deleted ? $this->id : \CHtml::link($this->id, \Yii::app()->createAbsoluteUrl('/webmaster/site', ['id' => $this->id])),
            ];
        }

        //send mail
        \Yii::import('ext.yii-mail.YiiMailMessage');
        $message = new \YiiMailMessage;
        $message->subject = $subject;
        $message->view = $view;
        $message->setBody($body, 'text/html');
        $message->addTo($email);
        $message->from = \Yii::app()->params['fromMail'];

        $result = \Yii::app()->mail->send($message);

        if(!$result && \Yii::app()->user->isAdmin)
            $this->addError(null, 'Не удалось отправить почту админу');

        return $result;
    }

    public function delete()
    {
        $this->status = self::STATUS_ARCHIVED;
        if($this->update('status'))
            return $this->unPublish();
        else
            return false;
    }

    public function setAttributes($values, $safeOnly=true)
    {
        $bollVals = ['containsAdult', 'allowAdult', 'allowShock', 'allowSms', 'allowAnimation'];

        foreach($bollVals as $bool){
            $isSet = isset($values[$bool]);
            $this->$bool = $isSet;
            if($isSet)
                unset($values[$bool]);
        }

        parent::setAttributes($values, $safeOnly);
    }

    public function updateStatus($state)
    {
        if(!isset(self::$_statuses[$state])){
            $this->addError(null, 'Нет такого статуса: '.$state);
            return false;
        }

        $this->status = $state;
        if($state == self::STATUS_PUBLISHED){
            $result = $this->publish();
        }else{
            $result = $this->unPublish();
        }

        return $result;
    }

    public function setStatus($state){
        if(!isset(self::$_statuses[$state])){
            $state = self::STATUS_NO_MODERATED;
        }

        $this->status = $state;
    }

    private function getQueryStatus()
    {
        $result = null;
        $stat = \Yii::app()->request->getQuery('siteStatus');

        if($stat !== null){
            $stat = intval($stat);

            if(isset(self::$_statuses[$stat]))
                $result = $stat;
        }

        return $result;
    }

    public function getSiteTotalTraffic(){
        $result = [
            'id' => $this->id,
            'shows' => 0,
            'clicks' => 0,
        ];

        foreach($this->blocks as $block){
            $result['shows'] += RedisIO::get("block-shows:{$block->id}") ? RedisIO::get("block-shows:{$block->id}") : ($block->shows ? $block->shows : 0);
            $result['clicks'] += RedisIO::get("block-clicks:{$block->id}") ? RedisIO::get("block-clicks:{$block->id}") : ($block->clicks ? $block->clicks : 0);
        }

        return $result;
    }

    public function getSiteBeforeTraffic($startDate = null, $endDate = null){
        $result = [
            'id' => $this->id,
            'shows' => 0,
            'clicks' => 0,
        ];

        $sql = 'SELECT shows,clicks FROM webmaster_stats WHERE site_id = :siteId';
        $params = [':siteId' => $this->id];

        if($startDate && $endDate){
            $sql .= ' AND date >= :startDate AND date <= :endDate';
            $params = array_merge($params, [':startDate' => $startDate, ':endDate' => $endDate]);
        }

        $stats = WebmasterStats::model()->findAllBySql($sql, $params);

        foreach($stats as $stat){
            $result['shows'] += $stat->shows;
            $result['clicks'] += $stat->clicks;
        }

        return $result;
    }

    public function getSiteTodayTraffic(){
        $total = $this->getSiteTotalTraffic();
        $before = $this->getSiteBeforeTraffic();

        return [
            'id' => $this->id,
            'shows' => ($total['shows'] - $before['shows']),
            'clicks' => ($total['clicks'] - $before['clicks'])
        ];
    }

    public function getSitePeriodTraffic($startDate, $endDate){
        $today = (new \DateTime('now'))->format(\Yii::app()->params->dateFormat);
        $periodTraffic = [
            'id' => $this->id,
            'url' => $this->url,
            'shows' => 0,
            'clicks' => 0
        ];

        if($endDate == $today){
            $periodTraffic = $this->getSiteTodayTraffic();
        }

        $beforeTraffic = $this->getSiteBeforeTraffic($startDate, $endDate);

        $periodTraffic['shows'] += $beforeTraffic['shows'];
        $periodTraffic['clicks'] += $beforeTraffic['clicks'];

        return $periodTraffic;
    }

    public function getSiteTrafficByDate($startDate = null, $endDate = null){
        $today = (new \DateTime('now'))->format(\Yii::app()->params->dateFormat);
        $result = [];

        $sql = 'SELECT sum(shows) as "shows",sum(clicks) as "clicks","date"
        FROM webmaster_stats
        WHERE site_id = :siteId
        AND date >= :startDate AND date <= :endDate
        GROUP BY "date"
        ORDER BY "date" ASC';
        $params = [':siteId' => $this->id,':startDate' => $startDate, ':endDate' => $endDate];

        $stats = WebmasterStats::model()->findAllBySql($sql,$params);

        foreach($stats as $stat){
            $result[$stat->date] = [
                'id' => $this->id,
                'shows' => $stat->shows,
                'clicks' => $stat->clicks
            ];
        }

        if($endDate == $today){
            $todayData = $this->getSiteTodayTraffic();

            $result[$today] = [
                'id' => $this->id,
                'shows' => $todayData['shows'],
                'clicks' => $todayData['clicks']
            ];
        }

        return $result;
    }

    public function getPeriodData($startDate,$endDate){
        $systemCom = 0.2;
        $webmasterGet = 1 - $systemCom;

        $traffic = $this->getSitePeriodTraffic($startDate,$endDate);
        $income = round($this->getPeriodIncome($startDate,$endDate),2);
        $advertiserPaid = round($income/$webmasterGet,2);

        return [
            'id' => $this->id,
            'url' => $this->url,
            'user_id' => $this->user_id,
            'shows' => $traffic['shows'],
            'clicks' => $traffic['clicks'],
            'ctr' => $traffic['shows'] ? round(($traffic['clicks']/$traffic['shows'])*100,2) : 0,
            'income' => $income,
            'averageCost' => $traffic['clicks'] ? round($advertiserPaid/$traffic['clicks'],2) : 0,
            'advertisersPaid' => $advertiserPaid,
            'cpm' => $traffic['shows'] ? round(($income/$traffic['shows'])*1000,2) : 0,
            'systemIncome' => round($income/($webmasterGet/$systemCom),2)
        ];
    }

    public function getTodayIncome(){
        return $this->getTotalIncome() - $this->getBeforeIncome();
    }

    public function getBeforeIncome($startDate = null, $endDate = null){
        $income = 0;

        $sql = 'SELECT costs FROM webmaster_stats WHERE site_id = :siteId';
        $params = [':siteId' => $this->id];

        if($startDate && $endDate){
            $sql .= ' AND date >= :startDate AND date <= :endDate';
            $params = array_merge($params, [':startDate' => $startDate, ':endDate' => $endDate]);
        }
        $stats = WebmasterStats::model()->findAllBySql($sql,$params);

        foreach($stats as $stat){
            $income += $stat->costs;
        }

        return $income;
    }

    public function getTotalIncome(){
        $income = 0;

        $blocks = Blocks::model()->findAllByAttributes(['site_id' => $this->id]);
        foreach($blocks as $block){
            $income += RedisIO::get("block-income:{$block->id}") ? RedisIO::get("block-income:{$block->id}") : 0;
        }

        return $income;
    }

    public function getPeriodIncome($startDate,$endDate){
        $today = (new \DateTime('now'))->format(\Yii::app()->params->dateFormat);
        $periodIncome = 0;

        if($endDate == $today){
            $periodIncome = $this->getTodayIncome();
        }
        $beforeIncome = $this->getBeforeIncome($startDate, $endDate);
        $periodIncome += $beforeIncome;

        return $periodIncome;
    }

    public function updateInfoBeforePublish($isSave = true, $publishBlocks = false)
    {
        $result = true;

        if($this->status === null){
            if(\Yii::app()->user->isAdmin)
                $this->status = self::STATUS_PUBLISHED;
            else
                $this->status = self::STATUS_DISABLED;
        }

        if($isSave)
            $result = $this->update(['status']);

        if($result && $this->blocks){
            $isPublish = $this->status == self::STATUS_PUBLISHED && $publishBlocks;

            foreach($this->blocks as $block){
                if($isPublish && $block->status == Blocks::STATUS_DISABLED)
                    $block->status = Blocks::STATUS_PUBLISHED;

                $block->updateInfoBeforePublish();
            }
        }

        return $result;
    }
}