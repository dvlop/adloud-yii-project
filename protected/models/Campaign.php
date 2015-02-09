<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property string $description
 * @property string $user_id
 * @property string $id
 * @property double $click_price
 * @property array $categories
 * @property string $start_date
 * @property string $stop_date
 * @property integer $daily_limit
 * @property double $expenses
 * @property string $geo
 * @property integer $publish
 * @property string $site_url
 * @property integer $age_limit
 * @property integer $gender
 * @property string $subject
 * @property string $create_date
 * @property string $site_id
 * @property string $black_list
 * @property string $white_list
 * @property integer $limit
 * @property string $ua
 * @property string $targets
 * @property string $labels_id
 *
 * The followings are the available model relations:
 * @property \application\models\Users $user
 * @property \application\models\Ads[] $ads
 * @property \application\models\behaviors\PublishBehavior $publisher
 * @property \application\models\Label[] $labels
 *
 * @property string $siteUrl
 * @property float $clickPrice
 * @property integer $ageLimit
 * @property integer $dailyLimit
 * @property \application\models\Country[] $countries
 * @property \application\models\Categories[] $categoriesList
 * @property integer[] $geoCountries
 * @property integer[] $geoRegions
 * @property array $geography
 * @property array $labelsId
 * @property integer $labelId
 */
class Campaign extends BaseModel
{
    const STATUS_DISABLED = 0;
    const STATUS_NO_MODERATED = 300;
    const STATUS_PUBLISHED = 1;
    const STATUS_PROHIBITED = 200;
    const STATUS_ARCHIVED = 500;

    const MIN_CLICK_PRICE = 0.01;

    private static $_genderNames = [
        0 => 'Любой',
        1 => 'Мужской',
        2 => 'Женский',
    ];

    private static $_colors = [
        'red' => 'Красный',
        'green' => 'Зелёный',
        'black' => 'Чёрный',
    ];

    private $geoChange = false;
    private $uaChange = false;

    private $_geography;
    private $_geoCountries;
    private $_geoRegions;

    private $_userAgent;
    private $_uaDevices;
    private $_uaDevicesModel;
    private $_uaBrowsers;
    private $_uaOS;
    private $_uaOSversion;

    private $_allDevices;
    private $_allBrowsers;
    private $_allOS;
    private $_devices;
    private $_devicesModel;
    private $_browsers;
    private $_os;
    private $_osVersion;
    private $_allCountries;
    private $_allCategories;
    private $_subjects;
    private $_countries;
    private $_regions;
    private $_categories;
    private $_showCategories;
    private $_list;
    private $_targets;
    private $_labels;

    /**
     * @var \application\models\Label
     */
    private $_label;
    private $_labelUpdate = false;
    private $_labelsInputs;
    public $_labelColors;
    public $_publishAds = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'campaign';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['publish, age_limit, gender', 'numerical', 'integerOnly'=>true],
            ['expenses, limit, daily_limit', 'numerical'],
            ['click_price', 'numerical', 'min' => self::MIN_CLICK_PRICE],
            ['description', 'length', 'max'=>255],
            ['site_url', 'length', 'max'=>512],
            ['user_id, categories, start_date, stop_date, geo, ua, subject, create_date, site_id, black_list, white_list, targets', 'safe'],
            ['description, click_price, daily_limit', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'user' => [self::BELONGS_TO, '\application\models\Users', 'user_id'],
            'ads' => [self::HAS_MANY, '\application\models\Ads', 'campaign_id'],
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
            'description' => 'Description',
            'user_id' => 'User',
            'id' => 'ID',
            'click_price' => 'Click Price',
            'categories' => 'Categories',
            'start_date' => 'Start Date',
            'stop_date' => 'Stop Date',
            'daily_limit' => 'Daily Limit',
            'expenses' => 'Expenses',
            'geo' => 'Geo',
            'publish' => 'Publish',
            'site_url' => 'Site Url',
            'age_limit' => 'Age Limit',
            'gender' => 'Gender',
            'subject' => 'Subject',
            'create_date' => 'Create Date',
            'site_id' => 'Site',
            'black_list' => 'Black List',
            'white_list' => 'White List',
            'limit' => 'Limit',
            'ua' => 'User Agent',
            'targets' => 'Retargeting'
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return \CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new \CDbCriteria;

        $criteria->compare('description',$this->description,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('id',$this->id,true);
        $criteria->compare('click_price',$this->click_price);
        $criteria->compare('categories',$this->categories,true);
        $criteria->compare('start_date',$this->start_date,true);
        $criteria->compare('stop_date',$this->stop_date,true);
        $criteria->compare('daily_limit',$this->daily_limit);
        $criteria->compare('expenses',$this->expenses);
        $criteria->compare('geo',$this->geo,true);
        $criteria->compare('publish',$this->publish);
        $criteria->compare('site_url',$this->site_url,true);
        $criteria->compare('age_limit',$this->age_limit);
        $criteria->compare('gender',$this->gender);
        $criteria->compare('subject',$this->subject,true);
        $criteria->compare('create_date',$this->create_date,true);
        $criteria->compare('site_id',$this->site_id,true);
        $criteria->compare('black_list',$this->black_list,true);
        $criteria->compare('white_list',$this->white_list,true);
        $criteria->compare('limit',$this->limit);
        $criteria->compare('ua',$this->ua,true);
        $criteria->compare('targets',$this->targets,true);

        return new \CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return \application\models\Campaign the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Campaign[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Campaign
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function setSiteUrl($url)
    {
        $this->site_url = (string)$url;
    }

    public function getSiteUrl()
    {
        return $this->site_url;
    }

    public function setClickPrice($price)
    {
        $this->click_price = floatval($price);
    }

    public function getClickPrice()
    {
        return $this->click_price ? $this->click_price : self::MIN_CLICK_PRICE;
    }

    public function getAgeLimit()
    {
        return intval($this->age_limit);
    }

    public function setAgeLimit($lim)
    {
        $this->age_limit = (int)$lim ? 1 : 0;
    }

    public function getDailyLimit()
    {
        return $this->daily_limit;
    }

    public function setDailyLimit($lim)
    {
        $this->daily_limit = floatval($lim);
    }

    public function setUserId($id)
    {
        $id = intval($id);
        if($id)
            $this->user_id = $id;
    }

    public function getUserId()
    {
        if($this->user_id === null)
            $this->user_id = \Yii::app()->user->id;
        return $this->user_id;
    }

    public function setLabelsId($ids)
    {
        $this->labels_id = $this->parseDbString($ids);
    }

    public function getLabelsId()
    {
        return $this->parseDbArray($this->labels_id);
    }

    public function setLabelId($id)
    {
        $this->setLabelsId([(int)$id]);
    }

    public function getLabelId()
    {
        $labels = $this->getLabelsId();
        return $labels ? $labels[0] : null;
    }

    public function addLabelId($id)
    {
        $labels = $this->getLabelsId();
        if($labels === null)
            $labels = [];

        $labels[] = (int)$id;
        $this->setLabelsId($labels);
    }

    /**
     * @return array
     */
    public function getGeography()
    {
        if($this->_geography === null){
            if($this->geo){
                $this->_geography = \CJSON::decode($this->geo);
            }else{
                $this->_geography = ['country' => null, 'region' => null];
            }
        }

        return $this->_geography;
    }

    /**
     * @return array
     */
    public function getUserAgent()
    {
        if($this->_userAgent === null){
            if($this->ua)
                $this->_userAgent = \CJSON::decode($this->ua);
            else
                $this->_userAgent = ['device' => [], 'deviceModel' => [], 'browser' => [], 'os' => [], 'osVer' => []];
        }

        return $this->_userAgent;
    }

    /**
     * @return \application\models\Country[]
     */
    public function getAllCountries()
    {
        if($this->_allCountries === null){
            $this->_allCountries = Country::model()->findAll();
        }
        return $this->_allCountries;
    }

    /**
     * @return \application\models\Region[]
     */
    public function getAllRegions()
    {
        if($this->_regions === null){
            $this->_regions = [];

            foreach(Region::model()->findAll() as $region){
                if($this->getGeoRegions() && in_array($region->id, $this->getGeoRegions()))
                    $region->checked = true;

                $this->_regions[$region->getCountryId()][] = $region;
            }
        }

        return $this->_regions;
    }

    /**
     * @return \application\models\Country[]
     */
    public function getCountries()
    {
        if($this->_countries === null){
            $this->_countries = [];

            foreach($this->getAllCountries() as $country){
                if(!$country->active)
                    continue;

                if($this->getGeoCountries() && in_array($country->id, $this->getGeoCountries()))
                    $country->checked = true;

                $this->_countries[] = $country;
            }
        }

        return $this->_countries;
    }

    /**
     * @param $id
     * @return \application\models\Region[]|null
     */
    public function getRegions($id)
    {
        return isset($this->getAllRegions()[$id]) ? $this->getAllRegions()[$id] : null;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getAllDevices(){
        if($this->_allDevices === null){
            $this->_allDevices = UserAgent::model()->findAllBySql('SELECT name as "name" FROM user_agent WHERE type = :type AND is_checked IS TRUE GROUP BY name',[
                ':type' => 'device'
            ]);
        }
        return $this->_allDevices;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getAllDevicesModel()
    {
        if($this->_devicesModel === null){
            $this->_devicesModel = [];

            foreach(UserAgent::model()->findAllByAttributes(['type' => 'device', 'is_checked' => TRUE]) as $devmodel){
                if($this->getUaDevices() && in_array($devmodel->name, $this->getUaDevices()))
                    $devmodel->checked = true;

                if($this->getUaDevicesModel() && in_array($devmodel->id, $this->getUaDevicesModel()))
                    $devmodel->checked = true;

                $this->_devicesModel[$devmodel->name][] = $devmodel;
            }
        }

        return $this->_devicesModel;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getAllBrowsers(){
        if($this->_allBrowsers === null){
            $this->_allBrowsers = UserAgent::model()->findAllBySql('SELECT name as "name" FROM user_agent WHERE type = :type AND is_checked IS TRUE GROUP BY name',[
                ':type' => 'browser'
            ]);
        }
        return $this->_allBrowsers;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getAllOS(){
        if($this->_allOS === null){
            $this->_allOS = UserAgent::model()->findAllBySql('SELECT name as "name" FROM user_agent WHERE type = :type AND is_checked IS TRUE GROUP BY name',[
                ':type' => 'os'
            ]);
        }
        return $this->_allOS;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getAllOSVersion()
    {
        if($this->_osVersion === null){
            $this->_osVersion = [];

            foreach(UserAgent::model()->findAllByAttributes(['type' => 'os', 'is_checked' => TRUE]) as $osver){
                if($this->getUaOS() && in_array($osver->name, $this->getUaOS()))
                    $osver->checked = true;

                if($this->getUaOSVersion() && in_array($osver->id, $this->getUaOSVersion()))
                    $osver->checked = true;

                $this->_devicesModel[$osver->name][] = $osver;
            }
        }

        return $this->_devicesModel;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getDevices(){
        if($this->_devices === null){
            $this->_devices = [];

            foreach($this->getAllDevices() as $device){
                if($this->getUaDevices() && in_array($device->name, $this->getUaDevices()))
                    $device->checked = true;

                $this->_devices[] = $device;
            }
        }

        return $this->_devices;
    }

    public function getDevicesModel($id){
        return isset($this->getAllDevicesModel()[$id]) ? $this->getAllDevicesModel()[$id] : null;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getBrowsers(){
        if($this->_browsers === null){
            $this->_browsers = [];

            foreach($this->getAllBrowsers() as $browser){
                if($this->getUaBrowsers() && in_array($browser->name, $this->getUaBrowsers()))
                    $browser->checked = true;

                $this->_browsers[] = $browser;
            }
        }

        return $this->_browsers;
    }

    /**
     * @return \application\models\UserAgent[]
     */
    public function getOS(){
        if($this->_os === null){
            $this->_os = [];

            foreach($this->getAllOS() as $os){
                if($this->getUaOS() && in_array($os->name, $this->getUaOS()))
                    $os->checked = true;

                $this->_os[] = $os;
            }
        }

        return $this->_os;
    }

    public function getOSVersion($id){
        return isset($this->getAllOSVersion()[$id]) ? $this->getAllOSVersion()[$id] : null;
    }

    /**
     * return string[]
     */
    public function getUaDevices(){
        if($this->_uaDevices === null){
            $this->_uaDevices = $this->getUserAgent() ? $this->getUserAgent()['device'] : [];
        }

        return $this->_uaDevices;
    }

    /**
     * return string[]
     */
    public function getUaBrowsers(){
        if($this->_uaBrowsers === null){
            $this->_uaBrowsers = $this->getUserAgent() ? $this->getUserAgent()['browser'] : [];
        }

        return $this->_uaBrowsers;
    }

    /**
     * return string[]
     */
    public function getUaOS(){
        if($this->_uaOS === null){
            $this->_uaOS = $this->getUserAgent() ? $this->getUserAgent()['os'] : [];
        }

        return $this->_uaOS;
    }

    /**
     * return integer[]
     */
    public function getUaDevicesModel(){
        if($this->_uaDevicesModel === null){
            $this->_uaDevicesModel = $this->getUserAgent() ? $this->getUserAgent()['deviceModel'] : [];
        }

        return $this->_uaDevicesModel;
    }

    /**
     * return integer[]
     */
    public function getUaOSVersion(){
        if($this->_uaOSversion === null){
            $this->_uaOSversion = $this->getUserAgent() ? $this->getUserAgent()['osVer'] : [];
        }

        return $this->_uaOSversion;
    }

    /**
     * return integer[]
     */
    public function getGeoCountries()
    {
        if($this->_geoCountries === null){
            $this->_geoCountries = $this->getGeography() ? $this->getGeography()['country'] : [];
        }

        return $this->_geoCountries;
    }

    /**
     * return integer[]
     */
    public function getGeoRegions()
    {
        if($this->_geoRegions === null){
            $this->_geoRegions = $this->getGeography() ? $this->getGeography()['region'] : [];
        }

        return $this->_geoRegions;
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getCategoriesList()
    {
        if($this->_categories === null){
            foreach(Categories::model()->findAll() as $category){
                if(!$category->active)
                    continue;

                if(in_array($category->id, $this->getSubjects()))
                    $category->checked = true;

                $this->_categories[] = $category;
            }
        }

        return $this->_categories;
    }

    /**
     * @return \application\models\Categories[]
     */
    public function getShowCategories()
    {
        if($this->_showCategories === null){
            foreach(Categories::model()->findAll() as $cat){
                if(!$cat->active)
                    continue;

                if(in_array($cat->id, $this->getAllCategories()))
                    $cat->checked = true;

                $this->_showCategories[] = $cat;
            }
        }

        return $this->_showCategories;
    }

    public function setCategories($categories = null)
    {
        if($categories === null)
            $categories = $this->categories;

        if(is_array($categories))
            $this->categories = '{'.implode(',', array_filter($categories)).'}';
        elseif((is_string($categories) && strpos($categories, '{') === false) || is_integer($categories))
            $this->categories = '{'.$categories.'}';
    }

    public function setSubject($categories = null)
    {
        if($categories === null)
            $categories = $this->subject;

        if(is_array($categories))
            $this->subject = '{'.implode(',', array_filter($categories)).'}';
        elseif((is_string($categories) && strpos($categories, '{') === false) || is_integer($categories))
            $this->subject = '{'.$categories.'}';
    }

    public function setCountry($country)
    {
        $geo = $this->getGeography();
        $this->geoChange = true;

        if(is_array($country)){
            $geo['country'] = array_filter($country);
            $this->_geography = $geo;
        }
    }

    public function setOs($item)
    {
        $ua = $this->getUserAgent();
        $this->uaChange = true;

        if(is_array($item)){
            $ua['os'] = array_filter($item);
            $this->_userAgent = $ua;
        }
    }

    public function setDevice($item)
    {
        $ua = $this->getUserAgent();
        $this->uaChange = true;

        if(is_array($item)){
            $ua['device'] = array_filter($item);
            $this->_userAgent = $ua;
        }
    }

    public function setBrowser($item)
    {
        $ua = $this->getUserAgent();
        $this->uaChange = true;

        if(is_array($item)){
            $ua['browser'] = array_filter($item);
            $this->_userAgent = $ua;
        }
    }

    public function setDeviceModel($item)
    {
        $ua = $this->getUserAgent();
        $this->uaChange = true;

        if(is_array($item)){
            $ua['deviceModel'] = array_filter($item);
            $this->_userAgent = $ua;
        }
    }

    public function setOsVer($item)
    {
        $ua = $this->getUserAgent();
        $this->uaChange = true;

        if(is_array($item)){
            $ua['osVer'] = array_filter($item);
            $this->_userAgent = $ua;
        }
    }

    public function setRegion($region)
    {
        $geo = $this->getGeography();
        $this->geoChange = true;

        if(is_array($region)){
            $geo['region'] = array_filter($region);
            $this->_geography = $geo;
        }
    }

    /**
     * @return integer[]
     */
    public function getAllCategories()
    {
        if($this->_allCategories === null){
            $categories = (string)$this->categories;
            $categories = str_replace('{', '', $categories);
            $categories = str_replace('}', '', $categories);
            $this->_allCategories = explode(',', $categories);
        }

        return $this->_allCategories;
    }

    /**
     * @return integer[]
     */
    public function getSubjects()
    {
        if($this->_subjects === null){
            $categories = (string)$this->subject;
            $categories = str_replace('{', '', $categories);
            $categories = str_replace('}', '', $categories);
            $this->_subjects = explode(',', $categories);
        }

        return $this->_subjects;
    }

    /**
     * @return string[]
     */
    public function getGenderList()
    {
        return self::$_genderNames;
    }

    /**
     * @return \application\models\TargetList|false
     */
    public function getAllTargets()
    {
        if($this->_targets === null){
            $this->_targets = [];

            $targets = $this->targets ? $this->targets : [];

            foreach(TargetList::model()->findAllByAttributes(['user_id' => $this->user_id]) as $target){
                if(in_array($target->id,$this->parseDbArray($targets))){
                    $target->checked = true;
                }

                $this->_targets[] = $target;
            }
        }

        return $this->_targets;
    }

    /**
     * @return \application\models\TargetList|false
     */
    public function getTargetList()
    {
        $list = [];

        if($this->getAllTargets()){
            foreach($this->_targets as $target){
                $list[] = $target->id;
            }
        }

        return $list;
    }

    public function setLabel($newLabel)
    {
        if(!$label = $this->getLabel())
            $label = new Label();
        if(isset($newLabel['delete']) && $newLabel['delete']){
            $this->labels_id = null;
        }elseif(isset($newLabel['existing']) && intval($newLabel['existing'])){
            $this->labels_id = $this->parseDbString([$newLabel['existing']]);
        }else{
            $label->setAttributes($newLabel);
            $label->setUserId($this->getUserId());

            if($label->validate()){
                $this->_label = $label;
            }
        }
    }

    /**
     * @return \application\models\Label|null
     */
    public function getLabel()
    {
        return $this->getLabels() ? $this->getLabels()[0] : null;
    }

    /**
     * Set Labels
     *
     * @param array $labels
     */
    public function setLabels($labels)
    {

    }

    /**
     * @return \application\models\Label[]
     */
    public function getLabels()
    {
        if($this->_labels === null){
            $this->_labels = [];

            $labelsId = $this->getLabelsId();
            if($labelsId){
                foreach($labelsId as $id){
                    $label = Label::model()->findByPk($id);
                    if($label)
                        $this->_labels[] = $label;
                }
            }
        }

        return $this->_labels;
    }

    /**
     * @return \stdClass[]
     */
    public function getLabelsInputs()
    {
        if($this->_labelsInputs === null){
            $this->_labelsInputs = [];

            foreach(Label::model()->findAllByAttributes(['user_id' => $this->getUserId()]) as $label){
                $labelsId = $this->getLabelsId() ? $this->getLabelsId() : [];
                $this->_labelsInputs[] = (object)[
                    'value' => $label->id,
                    'name' => $label->name,
                    'color' => $label->color,
                    'checked' => in_array($label->id, $labelsId),
                ];
            }
        }

        return $this->_labelsInputs;
    }

    /**
     * @return \stdClass[]
     */
    public function getLabelColors()
    {
        if($this->_labelColors === null){
            $this->_labelColors = [];

            $label = $this->getLabel();

            foreach(self::$_colors as $value => $name){
                $this->_labelColors[] = (object)[
                    'name' => $name,
                    'value' => $value,
                    'checked' => $label && trim($label->color) == trim($value),
                ];
            }
        }

        return $this->_labelColors;
    }

    public function removeLabel()
    {
        $this->labels_id = null;
        return $this->update(['labels_id']);
    }

    /**
     * @return \application\models\Lists|false
     */
    public function getList()
    {
        if($this->_list === null){
            $this->_list = false;

            foreach(Lists::model()->findAll() as $list){
                if(in_array($this->id, $list->getCampaignsIds())){
                    $this->_list = $list;
                    break;
                }
            }
        }

        return $this->_list;
    }

    /**
     * @return \application\models\Lists|false
     */
    public function getWhiteList()
    {
        $list = false;
        if($this->getList()){
            if($this->getList()->type == Lists::WHITE_LIST)
                $list = $this->getList();
        }

        return $list;
    }

    /**
     * @return \application\models\Lists|false
     */
    public function getBlackList()
    {
        $list = false;
        if($this->getList()){
            if($this->getList()->type == Lists::BLACK_LIST)
                $list = $this->getList();
        }

        return $list;
    }

    public function beforeSave()
    {
        if($this->_label && !$this->_labelUpdate){
            if($this->_label->save(false)){
                $this->_labelUpdate = true;
                $this->labels_id = $this->parseDbString([$this->_label->id]);
            }
        }

        if($this->geoChange && $geo = $this->getGeography()){
            if(!$geo['country'] && !$geo['region']){
                $this->geo = null;
            }else{
                $this->geo = \CJSON::encode($geo);
            }
        }

        if($this->uaChange && $ua = $this->getUserAgent()){
            if(!$ua['device'] && !$ua['deviceModel'] && !$ua['browser'] && !$ua['os'] && !$ua['osVer']){
                $this->ua = null;
            }else{
                $this->ua = \CJSON::encode($ua);
            }
        }
        $this->targets = $this->parseDbString($this->targets);

        if($this->getIsNewRecord()){
            if($this->create_date === null){
                $this->create_date = date(\Yii::app()->params['dateFormat']);
            }

            if($this->start_date === null){
                $this->start_date = (new \DateTime())->format(\Yii::app()->params['dateFormat']);
            }

            if($this->black_list === null && $this->getBlackList()){
                $this->black_list = $this->parseDbString($this->getBlackList()->getSitesIds());
            }

            if($this->white_list === null && $this->getWhiteList()){
                $this->white_list = $this->parseDbString($this->getWhiteList()->getSitesIds());
            }

            if($this->user_id === null){
                $this->user_id = \Yii::app()->user->id;
            }

            $this->labels_id = $this->parseDbString($this->labels_id);
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if($this->updateInfoBeforePublish(false) && !$this->_publishAds){
            if($this->publish == self::STATUS_PUBLISHED && $this->ads){
                foreach($this->ads as $ad){
                    $ad->publisher->unPublish();
                    $ad->publisher->publish();
                }
            }
        }
        parent::afterSave();
    }

    public function getIsAllowPublish()
    {
        return $this->publish == self::STATUS_PUBLISHED ||  $this->publish == self::STATUS_DISABLED;
    }

    public function delete()
    {
        $this->publisher->unPublish();

        $this->publish = self::STATUS_ARCHIVED;

        return $this->update(['publish']);
    }

    public function publish()
    {
        $this->publish = self::STATUS_PUBLISHED;
        $this->_publishAds =true;

        if($this->updateInfoBeforePublish(true, true))
            return $this->publisher->publish();
        else
            return false;
    }

    public function unPublish()
    {
        $this->publish = self::STATUS_DISABLED;

        if($this->update(['publish'])){
            $result = $this->publisher->unPublish();

            if($result && $this->ads){
                foreach($this->ads as $ad){
                    if($ad->status == Ads::STATUS_PUBLISHED){
                        $ad->status = Ads::STATUS_DISABLED;
                        $ad->update(['status']);
                    }
                }
            }

            return $result;
        }else{
            return false;
        }
    }

    public function updateInfoBeforePublish($isSave = true, $publishAds = false)
    {
        $result = true;
        if($this->publish === null){
            if(\Yii::app()->user->isAdmin)
                $this->publish = self::STATUS_PUBLISHED;
            else
                $this->publish = self::STATUS_NO_MODERATED;
        }

        if($isSave)
            $result = $this->update(['publish']);

        if($result && $this->ads){
            $publish = $this->publish == self::STATUS_PUBLISHED && $publishAds;

            foreach($this->ads as $ad){
                if($publish && $ad->status == Ads::STATUS_DISABLED)
                    $ad->status = Ads::STATUS_PUBLISHED;

                $ad->updateInfoBeforePublish();
            }
        }

        return $result;
    }
}