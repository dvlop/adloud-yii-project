<?php

namespace application\models;

use application\components\BaseModel;
use application\models\entities\AdsContentEntity;
use models\Countries;

/**
 * This is the model class for table "ads".
 *
 * The followings are the available columns in table 'ads':
 * @property string $shows
 * @property integer $clicks
 * @property double $rating
 * @property double $click_price
 * @property integer $max_clicks
 * @property string $id
 * @property string $campaign_id
 * @property string $start_date
 * @property string $stop_date
 * @property integer $status
 * @property integer $moderated
 * @property integer $daily_limit
 * @property integer $limit
 * @property double $expenses
 * @property string $content
 * @property string $type
 * @property boolean $shock
 * @property boolean $adult
 * @property string $create_date
 * @property string $geo_countries
 * @property string $geo_regions
 * @property string $site_id
 * @property string $black_list
 * @property string $white_list
 * @property boolean $animation
 * @property boolean $sms
 * @property string $categories
 * @property string $banned_blocks
 * @property string $ua_device
 * @property string $ua_device_model
 * @property string $ua_browser
 * @property string $ua_os
 * @property string $ua_os_ver
 * @property string $targets
 *
 * The followings are the available model relations:
 * @property \application\models\Campaign $campaign
 *
 * @property \application\models\entities\AdsContentEntity $entity
 * @property \application\models\behaviors\PublishBehavior $publisher
 * @property string $url
 * @property string $showUrl
 * @property string $caption
 * @property string $description
 * @property string $buttonText
 * @property string $image
 * @property string $img
 * @property bool $showButton
 * @property string $clickUrl
 * @property float $clickPrice
 * @property integer $campaignId
 * @property string $contentText
 * @property string $cats
 * @property string $shockInput
 * @property string $adultInput
 * @property \stdClass[] $showButtonList
 * @property \application\models\Country[] $countries
 * @property \application\models\Region[] $regions
 * @property \stdClass[] $statuses
 * @property string $adminMessage
 */
class Ads extends BaseModel
{
    const STATUS_NO_MODERATED = 0;
    const STATUS_MODERATED = 3;
    const STATUS_PROHIBITED = 200;
    const STATUS_DISABLED = 2;
    const STATUS_PUBLISHED = 1;
    const STATUS_ARCHIVED = 500;
    const STATUS_ALL = 1000;

    private static $_statuses = [
        self::STATUS_NO_MODERATED => 'Блок на модерации',
        self::STATUS_MODERATED => 'Блок промодерирован',
        self::STATUS_PROHIBITED => 'Блок не допущен',
        self::STATUS_DISABLED => 'Блок не опубликован',
        self::STATUS_PUBLISHED => 'Блок опубликован',
        self::STATUS_ARCHIVED => 'Блок удалён',
        self::STATUS_ALL => 'Все',
    ];

    private static $_webStatuses = [
        self::STATUS_NO_MODERATED => 'На модерации',
        self::STATUS_PROHIBITED => 'Не допущенные',
        self::STATUS_PUBLISHED => 'Промодерированные',
        self::STATUS_ALL => 'Все',
    ];

    private static $_showButtonNames = [
        'Не использовать кнопку',
        'Использовать кнопку (Рекомедуеться)',
    ];

    private static $_listColumns = [
        'id',
        'img',
        'contentText',
        'url',
        'clickPrice',
        'cats',
        'shockInput',
        'adultInput'
    ];

    private static $_search = [

    ];

    private static $_sortColumns = [

    ];

    private $_entity;
    private $_buttonList;
    private $_isNew = true;
    private $_categories;
    private $_queryStatuses;
    private $_campaignsList;
    private $_categoriesText;

    public $cropParams;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ads';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['click_price, campaign_id', 'required'],
            ['clicks, max_clicks, status, moderated, daily_limit, limit', 'numerical', 'integerOnly'=>true],
            ['rating, expenses', 'numerical'],
            ['click_price', 'numerical', 'min' => 0.01],
            ['start_date, stop_date', 'length', 'max'=>6],
            ['type', 'length', 'max'=>64],
            ['geo_countries, geo_regions, targets', 'default'],
            //['', 'filter', 'filter' => 'htmlspecialchars']
            //['image', 'file', 'allowEmpty' => false],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'campaign' => [self::BELONGS_TO, '\application\models\Campaign', 'campaign_id'],
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
            'shows' => 'Shows',
            'clicks' => 'Clicks',
            'rating' => 'Rating',
            'click_price' => 'Цена за переход',
            'max_clicks' => 'Max Clicks',
            'id' => 'ID',
            'campaign_id' => 'Campaign',
            'start_date' => 'Start Date',
            'stop_date' => 'Stop Date',
            'status' => 'Status',
            'moderated' => 'Moderated',
            'daily_limit' => 'Daily Limit',
            'limit' => 'Limit',
            'expenses' => 'Expenses',
            'content' => 'Content',
            'type' => 'Type',
            'shock' => 'Товарный тизер',
            'adult' => 'Контент для взрослых',
            'create_date' => 'Create Date',
            'geo_countries' => 'Geo Countries',
            'geo_regions' => 'Geo Regions',
            'site_id' => 'Site',
            'black_list' => 'Black List',
            'white_list' => 'White List',
            'animation' => 'Animation',
            'sms' => 'Sms',
            'categories' => 'Additional Categories',
            'banned_blocks' => 'Banned Blocks',
            'img' => 'Изображение',
            'contentText' => 'Содержание',
            'url' => 'Ссылка',
            'clickPrice' => 'Стоимость клика',
            'cats' => 'Категории',
            'shockInput' => 'Товарный тизер',
            'adultInput' => 'Контент для взрослых',
            'ua_device' => 'Ua Device',
            'ua_browser' => 'Ua Browser',
            'us_os' => 'Ua Os',
            'targets' => 'Ретаргетинг'
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return \application\models\Ads the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Ads[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Ads
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    /**
     * @return \application\models\entities\AdsContentEntity
     */
    public function getEntity()
    {
        if($this->_entity === null){
            $this->_entity = new AdsContentEntity($this->content);
        }

        return $this->_entity;
    }

    public function setUrl($url)
    {
        $this->getEntity()->url = (string)$url;
    }

    public function getUrl()
    {
        return $this->getEntity()->url;
    }

    public function setShowUrl($url)
    {
        $this->getEntity()->showUrl = (string)$url;
    }

    public function getShowUrl()
    {
        return $this->getEntity()->showUrl;
    }

    public function setCaption($caption)
    {
        $this->getEntity()->caption = htmlspecialchars($caption);
    }

    public function getCaption()
    {
        return $this->getEntity()->caption;
    }

    public function setDescription($description)
    {
        $this->getEntity()->description = htmlspecialchars((string)$description);
    }

    public function getDescription()
    {
        return $this->getEntity()->description;
    }

    public function setButtonText($buttonText)
    {
        $this->getEntity()->buttonText = htmlspecialchars((string)$buttonText);
    }

    public function getButtonText()
    {
        return $this->getEntity()->buttonText;
    }

    public function setImage($image)
    {
        $this->getEntity()->setImage($image);
    }

    public function getImage()
    {
        return $this->getEntity()->getImageUrl();
    }

    public function setShowButton($showButton)
    {
        $this->getEntity()->showButton = (bool)$showButton;
    }

    public function getShowButton()
    {
        return (bool)$this->getEntity()->showButton;
    }

    public function setClickUrl($url)
    {
        $this->getEntity()->clickUrl = htmlspecialchars((string)$url);
    }

    public function getClickUrl()
    {
        return $this->getEntity()->clickUrl;
    }

    public function setClickPrice($price)
    {
        $this->click_price = floatval($price);
    }

    public function getClickPrice()
    {
        if($this->click_price === null && $this->campaign !== null){
            $this->click_price = $this->campaign->getClickPrice();
        }
        return $this->click_price ? floatval($this->click_price) : null;
    }

    public function setCampaignId($id)
    {
        $this->campaign_id = intval($id);
    }

    public function getCampaignId()
    {
        return intval($this->campaign_id);
    }

    public function setCropParams($params)
    {
        $this->getEntity()->setCropParams((string)$params);
    }

    public function getCropParams()
    {
        return $this->getEntity()->cropParams;
    }

    public function getStatusName()
    {
        if($this->status === null)
            $this->status = self::STATUS_NO_MODERATED;

        if(isset(self::$_statuses[$this->status]))
            return self::$_statuses[$this->status];
        else
            return self::STATUS_NO_MODERATED;
    }

    public function getImg()
    {
        if($this->id !== null)
            return '<img src="'.$this->getImage().'" height="70px"" />';
        else
            return null;
    }

    public function setAdminMessage($message)
    {
        $this->getEntity()->setAdminMessage($message);
    }

    public function getAdminMessage()
    {
        return $this->getEntity()->getAdminMessage();
    }

    public function getIsAllowedPublish()
    {
        return $this->status == self::STATUS_DISABLED || $this->status == self::STATUS_MODERATED;
    }

    public function getContentText()
    {
        if($this->id !== null){
            $content = $this->getEntity();
            return $content->caption.', '.$content->description.', '.$content->buttonText.', '.$content->showUrl;
        }else{
            return null;
        }
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getAdsCategories()
    {
        if($this->_categories === null){
            $this->_categories = Categories::model()->findAllByAttributes(['id' => $this->getCatsIds()]);
            if($this->_categories === null)
                $this->_categories = [];

            $this->_categories = array_filter($this->_categories);
        }

        return $this->_categories;
    }

    /**
     * @return string
     */
    public function getCategoriesText()
    {
        if($this->_categoriesText === null){
            $this->_categoriesText = '';

            foreach($this->getAdsCategories() as $cat){
                $this->_categoriesText .= $cat->description ? $cat->description.', ' : '';
            }

            if($this->_categoriesText)
                $this->_categoriesText = substr($this->_categoriesText, 0, strlen($this->_categoriesText)-2);
        }

        return $this->_categoriesText;
    }

    /**
     * @return string[]
     */
    public function getCatsIds()
    {
        if($this->categories)
            return $this->parseDbArray($this->categories);
        else
            return [];
    }

    public function getCats()
    {
        if($this->id !== null)
            return '<a href="#" class="ads-cats-list">Показать список</a>';
        else
            return null;
    }

    public function setCountries($countries)
    {
        $this->geo_countries = $this->parseDbString($countries);
    }

    public function getCountries()
    {
        return $this->parseDbArray($this->geo_countries);
    }

    public function setRegions($regions)
    {
        $this->geo_regions = $this->parseDbString($regions);
    }

    public function getRegions()
    {
        return $this->parseDbArray($this->geo_regions);
    }

    public function setDevice($items)
    {
        $this->ua_device = $this->parseDbString($items);
    }

    public function getDevice()
    {
        return $this->parseDbArray($this->ua_device);
    }

    public function setDeviceModel($items)
    {
        $this->ua_device_model = $this->parseDbString($items);
    }

    public function getDeviceModel()
    {
        return $this->parseDbArray($this->ua_device_model);
    }

    public function setBrowser($items)
    {
        $this->ua_browser = $this->parseDbString($items);
    }

    public function getBrowser()
    {
        return $this->parseDbArray($this->ua_browser);
    }

    public function setOs($items)
    {
        $this->ua_os = $this->parseDbString($items);
    }

    public function getOs()
    {
        return $this->parseDbArray($this->ua_os);
    }

    public function setOsVer($items)
    {
        $this->ua_os_ver = $this->parseDbString($items);
    }

    public function getOsVer()
    {
        return $this->parseDbArray($this->ua_os_ver);
    }

    public function setCategories($cats)
    {
        $cats = (string)$cats;
        if($cats)
            $this->categories = $cats;
    }

    public function setModerated($int)
    {
        $this->moderated = intval($int);
    }

    public function setShows($int)
    {
        $this->shows = intval($int);
    }

    public function setClicks($int)
    {
        $this->clicks = intval($int);
    }

    public function setRating($int)
    {
        $this->rating = intval($int);
    }

    public function setSms($bool)
    {
        $this->sms = (bool)$bool;
    }

    public function setAnimation($bool)
    {
        $this->animation = (bool)$bool;
    }

    public function setShock($bool)
    {
        $this->shock = (bool)$bool;
    }

    public function setAdult($bool)
    {
        $this->adult = (bool)$bool;
    }

    public function setBlackList($list)
    {
        $list = (string)$list;
        if($list)
            $this->black_list = $list;
    }

    public function setWhiteList($list)
    {
        $list = (string)$list;
        if($list)
            $this->white_list = $list;
    }

    public function setBannedBlocks($list)
    {
        $list = (string)$list;
        if($list)
            $this->banned_blocks = $list;
    }

    public function setUaDevice($list)
    {
        $list = (string)$list;
        if($list)
            $this->ua_device = $list;
    }

    public function setUaBrowser($list)
    {
        $list = (string)$list;
        if($list)
            $this->ua_browser = $list;
    }

    public function setUaOs($list)
    {
        $list = (string)$list;
        if($list)
            $this->ua_os = $list;
    }

    public function setUaDeviceModel($list)
    {
        $list = (string)$list;
        if($list)
            $this->ua_device_model = $list;
    }

    public function setTargets($list)
    {
        $list = (string)$list;
        if($list)
            $this->targets = $list;
    }

    public function setUaOsVer($list)
    {
        $list = (string)$list;
        if($list)
            $this->ua_os_ver = $list;
    }

    public function setStartDate($list)
    {
        $list = (string)$list;
        if($list)
            $this->start_date = $list;
    }

    public function setStopDate($list)
    {
        $list = (string)$list;
        if($list)
            $this->stop_date = $list;
    }

    public function setExpenses($list)
    {
        $this->expenses = $list;
    }

    public function setDailyLimit($list)
    {
        $this->daily_limit = intval($list);
    }

    public function setTargetList($items){
        $this->targets = $this->parseDbString($items);
    }

    public function setAdsCopyCampaign($id)
    {
        $id = intval($id);

        if(!Campaign::model()->findByPk($id)){
            $this->addError('adsCopyCampaign', 'Невт кампании ID '.$id);
        }else{
            $this->campaign_id = $id;
        }
    }

    public function setStatus($status)
    {
        $status = intval($status);
        if(!isset(self::$_statuses[$status]))
            $status = self::STATUS_NO_MODERATED;

        $this->status = $status;
    }

    public function getTargetList(){
        return $this->parseDbArray($this->targets);
    }

    public function getShockInput()
    {
        if($this->id !== null)
            return '<input class="set-ads-bool" name="shock" onclick="AdsList.changeContentType($(this))" data-url='.\Yii::app()->createUrl('admin/ads/setshock', ['id' => $this->id]).' type="checkbox"'.($this->shock ? 'checked="checked"' : '').' />';
        else
            return null;
    }

    public function getAdultInput()
    {
        if($this->id !== null)
            return '<input class="set-ads-bool" onclick="AdsList.changeContentType($(this))" name="adult" data-url='.\Yii::app()->createUrl('admin/ads/setAdult', ['id' => $this->id]).' type="checkbox"'.($this->adult ? 'checked="checked"' : '').' />';
        else
            return null;
    }

    /**
     * @return \stdClass[]
     */
    public function getShowButtonList()
    {
        if($this->_buttonList === null){
            $this->_buttonList = [];
            foreach(self::$_showButtonNames as $id => $text){
                $this->_buttonList[] = (object)[
                    'value' => $id,
                    'name' => $text,
                    'checked' => $this->getShowButton() === (bool)$id,
                ];
            }
        }

        return $this->_buttonList;
    }

    public function getIsConfirm()
    {
        $this->status = (int)$this->status;
        return $this->status === self::STATUS_PROHIBITED || $this->status === self::STATUS_NO_MODERATED || $this->status === self::STATUS_DISABLED;
    }

    public function getIsReject()
    {
        $this->status = (int)$this->status;

        return $this->status !== self::STATUS_PROHIBITED;
    }

    /**
     * @return \stdClass[]
     */
    public function getSelectorStatuses()
    {
        if($this->_queryStatuses === null){
            $this->_queryStatuses = [];

            foreach(self::$_webStatuses as $value => $name){
                $this->_queryStatuses[] = (object)[
                    'value' => $value,
                    'name' => $name,
                    'checked' => $value == $this->status,
                ];
            }
        }

        return $this->_queryStatuses;
    }

    public function getUserId()
    {
        if($this->campaign !== null)
            return $this->campaign->getUserId();
        else
            return null;
    }

    /**
     * @return \stdClass[]
     */
    public function getCampaignsList()
    {
        if($this->_campaignsList === null){
            $this->_campaignsList = [];

            if($this->campaign)
                $userId = $this->campaign->getUserId();
            else
                $userId = \Yii::app()->user->id;

            foreach(Campaign::model()->findAllByAttributes(['user_id' => $userId]) as $camp){
                if($camp->publish == Campaign::STATUS_PROHIBITED || $camp->publish == Campaign::STATUS_ARCHIVED)
                    continue;

                $this->_campaignsList[] = (object)[
                    'name' => $camp->description,
                    'id' => $camp->id,
                    'checked' => $camp->id == $this->getCampaignId(),
                    'status' => $camp->publish,
                ];
            }
        }

        return $this->_campaignsList;
    }

    public function setEntity(AdsContentEntity $entity)
    {
        $this->_entity = $entity;
    }

    public function checkStatus($id)
    {
        return isset(self::$_statuses[$id]);
    }

    public function updateStatus($id, $message = null)
    {
        $id = intval($id);

        if(!$this->checkStatus($id)){
            $this->addError(null, 'Не верный статус: '.$id);
            return false;
        }

        $this->status = $id;

        if($id == self::STATUS_PUBLISHED)
            $result = $this->publish();
        else
            $result = $this->unPublish();

        if(!$result){
            $result = $this->update(['status']);
        }

        if($message)
            $this->setAdminMessage($message);

        return $result;
    }

    public function updateShock($val)
    {
        $this->shock = (bool)$val;
        $result = $this->update(['shock']);

        if($result && $this->status == self::STATUS_PUBLISHED){
            $this->setActualConnection();
            $result = $this->update(['shock']);
            $this->setPersistentConnection();
        }
        return $result;
    }

    public function updateAdult($val)
    {
        $this->adult = (bool)$val;
        $result = $this->update(['adult']);

        if($result && $this->status == self::STATUS_PUBLISHED){
            $this->setActualConnection();
            $result = $this->update(['adult']);
            $this->setPersistentConnection();
        }
        return $result;
    }

    public function beforeSave()
    {
        $this->_isNew = $this->getIsNewRecord();

        $file = isset($_FILES[$this->getModelName()]['tmp_name']['image']) ? $_FILES[$this->getModelName()]['tmp_name']['image'] : false;

        if($file){
            if(!$this->getEntity()->setImage($file)){
                $this->addError('image', $this->getEntity()->getError());
                return false;
            }

            $this->animation = $this->getEntity()->getIsAnimation();
        }elseif($this->_isNew && !$this->getIsCopy()){
            $this->addError('image', 'Необходимо выбрать изображение');
            return false;
        }

        if($this->_isNew){
            
            $today = new \DateTime('now');
            $today = $today->format(\Yii::app()->params->dateFormat);

            if($this->create_date === null)
                $this->create_date = $today;

            if($this->type === null)
                $this->type = 'AdsStandard';

            if($this->status === null)
                $this->status = \Yii::app()->user->isAdmin ? self::STATUS_DISABLED : self::STATUS_NO_MODERATED;

            if($this->campaign !== null){
                if($this->categories === null){
                    $this->categories = $this->parseDbString($this->campaign->getAllCategories());
                }

                if($this->geo_countries === null){
                    $this->geo_countries = $this->parseDbString($this->campaign->getGeography()['country']);
                }

                if($this->geo_regions === null){
                    $this->geo_regions = $this->parseDbString($this->campaign->getGeography()['region']);
                }

                if($this->ua_device === null){
                    $this->ua_device = $this->parseDbString($this->campaign->getUserAgent()['device']);
                }

                if($this->ua_device_model === null){
                    $this->ua_device_model = $this->parseDbString($this->campaign->getUserAgent()['deviceModel']);
                }

                if($this->ua_browser === null){
                    $this->ua_browser = $this->parseDbString($this->campaign->getUserAgent()['browser']);
                }

                if($this->ua_os === null){
                    $this->ua_os = $this->parseDbString($this->campaign->getUserAgent()['os']);
                }

                if($this->ua_os_ver === null){
                    $this->ua_os_ver = $this->parseDbString($this->campaign->getUserAgent()['osVer']);
                }

                if($this->targets === null){
                    $this->targets = $this->parseDbString($this->campaign->targets);
                }
            }

            if($this->shock === null)
                $this->shock = false;

            if($this->adult === null)
                $this->adult = false;

            if($this->sms === null)
                $this->sms = false;

            if($this->animation === null)
                $this->animation = false;

            if($this->rating === null)
                $this->rating = $this->click_price;

            if($this->shows === null)
                $this->shows = 0;
            if($this->clicks === null)
                $this->clicks = 0;
        }

        $this->updateInfoBeforePublish(false);

        $this->content = \CJSON::encode($this->getEntity());

        return parent::beforeSave();
    }

    public function beforeCopy()
    {
        if(!parent::beforeCopy())
            return false;

        $status = \Yii::app()->user->isAdmin ? self::STATUS_DISABLED : self::STATUS_NO_MODERATED;
        $this->status = $status;
        $this->moderated = $status;
        $this->shows = 0;
        $this->clicks = 0;

        return true;
    }

    public function afterSave()
    {
        return parent::afterSave();
    }

    public function delete()
    {
        $this->status = self::STATUS_ARCHIVED;
        $this->publisher->unPublish();
        return $this->update(['status']);
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

        $attrs['status'] = [
            'name' => 'status',
            'value' => self::STATUS_NO_MODERATED,
            'partialMatch' => false,
            'operation' => 'AND'
        ];

        if($statQuery !== null){
            if($statQuery === self::STATUS_ALL)
                unset($attrs['status']);
            else
                $attrs['status']['value'] = $statQuery;
        }

        return $attrs;
    }

    public function saveRegions($geo = null)
    {
        if(is_array($geo) && $geo){
            $countries = [];
            $regions = [];

            if(isset($geo['country']) && $geo['country']){
                foreach($geo['country'] as $id){
                    if($country = Country::model()->findByPk($id))
                        $countries[] = $country->code;
                }
            }

            if(isset($geo['region']) && $geo['region']){
                foreach($geo['region'] as $id){
                    if($region = Region::model()->findByPk($id))
                        $regions[] = $region->code;
                }
            }

            if($countries)
                $this->setCountries($countries);
            if($regions)
                $this->setRegions($regions);

            return $this->update(['geo_countries', 'geo_regions']);
        }

        return true;
    }

    public function saveCategories($categories)
    {
        if(is_array($categories))
            $categories = $this->parseDbString($categories);
        $this->categories = $categories;

        return $this->update(['categories']);
    }

    public function setLists($black, $white)
    {
        if($black && $black->sites)
            $this->black_list = $black->sites;
        else
            $this->black_list = null;

        if($white && $white->sites)
            $this->white_list = $white->sites;
        else
            $this->white_list = null;

        return $this->update(['black_list', 'white_list']);
    }

    public function saveUa($ua = null){
        if(is_array($ua) && isset($ua['device']) && isset($ua['deviceModel']) && isset($ua['browser']) && isset($ua['os']) && isset($ua['osVer'])){
            $this->setDevice($ua['device']);
            $this->setDeviceModel($ua['deviceModel']);
            $this->setBrowser($ua['browser']);
            $this->setOs($ua['os']);
            $this->setOsVer($ua['osVer']);
        }

        return $this->update(['ua_device', 'ua_device_model', 'ua_browser', 'ua_os', 'ua_os_ver']);
    }

    public function saveTargets($targets = null){
        if($targets){
            $this->setTargetList($targets);
        }

        return $this->update(['targets']);
    }

    public function getQueryStatus()
    {
        $result = null;

        $stat = \Yii::app()->request->getQuery('adsStatus');

        if($stat !== null){
            $stat = intval($stat);

            if(isset(self::$_statuses[$stat]))
                $result = $stat;
        }

        return $result;
    }

    public function publish()
    {
        $this->status = self::STATUS_PUBLISHED;
        if($this->updateInfoBeforePublish())
            return $this->publisher->publish();
        else
            return false;
    }

    public function unPublish()
    {
        $this->status = $this->status == self::STATUS_PUBLISHED || $this->status === null ? self::STATUS_DISABLED : $this->status;

        if($this->update('status'))
            return $this->publisher->unPublish();
        else
            return false;
    }

    public function updateInfoBeforePublish($isSave = true)
    {
        if($campaign = $this->campaign){
            $this->categories = $this->parseDbString($campaign->categories);

            if(!$this->click_price)
                $this->click_price = $campaign->click_price;

            if(!$this->max_clicks)
                $this->max_clicks = $campaign->limit;

            if($countries = $campaign->getGeoCountries()){
                $codes = [];
                foreach($countries as $id){
                    $country = Country::model()->findByPk($id);
                    if($country)
                        $codes[] = $country->code;
                }

                if($codes)
                    $this->geo_countries = $this->parseDbString($codes);
            }

            if($regions = $campaign->getGeoRegions()){
                $codes = [];
                foreach($regions as $id){
                    $region = Region::model()->findByPk($id);
                    if($region)
                        $codes[] = $region->code;
                }

                if($codes)
                    $this->geo_regions = $this->parseDbString($codes);
            }

            $this->black_list = $this->parseDbString($campaign->black_list);
            $this->white_list = $this->parseDbString($campaign->white_list);

            if($ua = $campaign->getUserAgent()){
                if(isset($ua['device']) && $ua['device'])
                    $this->setDevice($ua['device']);
                if(isset($ua['deviceModel']) && $ua['deviceModel'])
                    $this->setDeviceModel($ua['deviceModel']);
                if(isset($ua['browser']) && $ua['browser'])
                    $this->setBrowser($ua['browser']);
                if(isset($ua['os']) && $ua['os'])
                    $this->setOs($ua['os']);
                if(isset($ua['osVer']) && $ua['osVer'])
                    $this->setOsVer($ua['osVer']);
            }

            $this->setTargetList($campaign->targets);
        }

        if(!$this->rating)
            $this->rating = $this->click_price;

        if($isSave)
            return $this->update(['categories', 'click_price', 'max_clicks', 'geo_countries', 'geo_regions', 'black_list', 'white_list', 'ua_device', 'ua_device_model', 'ua_browser', 'ua_os', 'ua_os_ver', 'targets', 'rating']);
        else
            return true;
    }
}