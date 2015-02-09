<?php

/**
 * Class CampaignForm
 * @property array $genderList;
 * @property array $subjects;
 * @property array $categoriesList
 * @property array $countries
 * @property array $regions
 * @property array $cities
 */
class CampaignForm extends FormModel {

    private static $_genderNames = [
        0 => 'Любой',
        1 => 'Мужской',
        2 => 'Женский',
    ];

    public $_identity;

    public $description;
    public $clickPrice;
    public $categories;
    public $startDate;
    public $stopDate;
    public $blackList;
    public $limit;
    public $dailyLimit;
    public $siteUrl;
    public $ageLimit = 0;
    public $gender = 0;
    public $siteId;
    public $subject;
    public $country = array();
    public $region = array();
    public $city = array();

    public function attributeLabels()
    {
        return array(
            'description' => 'Введите название кампании:',
            'clickPrice' => 'Установите цену за переход',
            'geo' => 'Гео',
            'siteId' => 'ID сайта',
            'startDate' => 'Время начала',
            'stopDate' => 'Время окончания',
            'blackList' => 'Блок-листы',
            'limit' => 'Установите суммарный бюджет рекламной кампании:',
            'dailyLimit' => 'Установите дневной бюджет рекламной кампании:',
            'geoZoneCountry' => 'Страна',
            'geoZoneRegion' => 'Регион',
            'geoZoneCity' => 'Город',
            'siteUrl' => 'Введите адрес рекламируемого сайта:',
            'ageLimit' => 'Планируете ли вы использовать контент для взрослых',
            'gender' => 'Демография',
            'subject' => 'Выберите тематику вашей рекламной кампании',
            'categories' => 'Выберите категории сайтов для показа ваших объявлений',
        );
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            // startDate, stopDate
            ['description, categories, clickPrice', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['clickPrice', 'numerical', 'min' => 0.01, 'message' => 'Поле {attribute} может содержать только числовые значения'],
            ['limit', 'numerical', 'min' => 0.01, 'message' => 'Поле {attribute} может содержать только числовые значения'],
            ['dailyLimit', 'numerical', 'min' => 0.01, 'message' => 'Поле {attribute} может содержать только числовые значения'],
            ['startDate, stopDate', 'date', 'format' => 'yyyy-mm-dd', 'message' => 'Поле {attribute} может содержать только дату'],
            ['categories', 'removeWrongOption'],
            //array('geoZoneCountry, geoZoneCity', 'type', 'string', 'message' => 'Поле {attribute} может содержать только числовые значения'),
            ['blackList, siteUrl, ageLimit, gender, country, region, city, subject, siteId', 'default'],
            ['description, siteUrl, clickPrice, limit, dailyLimit', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function setCampaign($campaignId = null)
    {
        if($campaignId !== null){
            $campaign = \models\Campaign::getInstance();
            $currentCampaign = $campaign->getById($campaignId, \Yii::app()->user->id);

            $this->description = $currentCampaign['description'];
            $this->clickPrice = $currentCampaign['clickPrice'];
            $this->categories = $currentCampaign['categories'];
            $this->startDate = $currentCampaign['startDate'];
            $this->stopDate = $currentCampaign['stopDate'];
            $this->limit = $currentCampaign['limit'];
            $this->dailyLimit = $currentCampaign['dailyLimit'];
            $this->siteUrl = $currentCampaign['site_url'];
            $this->ageLimit = $currentCampaign['age_limit'];
            $this->gender = $currentCampaign['gender'];
            $this->siteId = $currentCampaign['siteId'];
            $this->subject = isset($currentCampaign['subject']) && $currentCampaign['subject'] ? array_shift($currentCampaign['subject']) : null;
            $this->city = isset($currentCampaign['geo']['city']) && $currentCampaign['geo']['city'] ? $currentCampaign['geo']['city'] : null;
            $this->region = isset($currentCampaign['geo']['region']) && $currentCampaign['geo']['region'] ? $currentCampaign['geo']['region'] : null;
            $this->country = isset($currentCampaign['geo']['country']) && $currentCampaign['geo']['country'] ? $currentCampaign['geo']['country'] : null;
        }
    }

    public function campaign($id = null)
    {
        if($this->categories)
            $this->categories = array_filter($this->categories);

        $campaign = \models\Campaign::getInstance();

        if($id !== null){
            $campaign->initById($id);
        }

        $geo = [];
        if($this->country){
            $this->country = array_filter($this->country);
            $geo['country'] = $this->country;
        }
        if($this->region){
            $this->region = array_filter($this->region);
            $geo['region'] = $this->region;
        }
        if($this->city){
            $this->city = array_filter($this->city);
            $geo['city'] = $this->city;
        }

        $campaign->categories = $this->categories;
        $campaign->description = $this->description;
        $campaign->clickPrice = $this->clickPrice;
        $campaign->startDate =  $this->startDate;
        $campaign->stopDate =  $this->stopDate;
        $campaign->siteUrl = $this->siteUrl;
        $campaign->limit = $this->limit;
        $campaign->dailyLimit = $this->dailyLimit;
        $campaign->blackList = $this->blackList;
        $campaign->ageLimit = $this->ageLimit;
        $campaign->gender = $this->gender;
        $campaign->siteId = $this->siteId;
        $campaign->subject = [$this->subject];
        $campaign->geo = \CJSON::encode($geo);

        try{
            if(!$id){
                if(!$newId = $campaign->save()){
                    $this->addError(null, 'Возникла ошибка при создании рекламной кампании.');
                    return false;
                }
            }else{
                if(!$campaign->update()){
                    $this->addError(null, 'Возникла ошибка при редактировании рекламной кампании.');
                    return false;
                }

                $newId = $id;
            }

            return $newId;

        }catch(Exception $e){
            $this->addError(null, $e->getMessage());
            return false;
        }
    }

    public function getCategoriesList()
    {
        return CHtml::listData(\models\Category::getInstance()->getList(), 'id', 'description');
    }

    public function getCountries()
    {
        try{
            $countriesList = \models\Country::getInstance()->findAll(['id', 'name', 'where' => ['active' => 1]]);
            if($countriesList){
                if(!is_array($this->country))
                    $this->country = [];
                foreach($countriesList as $num=>$country){
                    $countriesList[$num]->isChecked = in_array($country->id, $this->country);
                }
            }
            return $countriesList;
        }catch(Exception $e){
            $error = YII_DEBUG ? $e->getMessage() : 'Не удалось получить список стран. Попробуйте позже.';
            $this->addError(null, $error);
            return array();
        }
    }

    public function getRegions($countriesId = [])
    {
        if(!$countriesId)
            return array();
        try{
            $regionsList = \models\Country::getInstance()->getRegions(['id', 'name', 'country_id'], $countriesId);
            if($regionsList){
                if(!is_array($this->region))
                    $this->region = [];
                foreach($regionsList as $num=>$region){
                    $regionsList[$num]->isChecked = in_array($region->id, $this->region);
                }
            }
            return $regionsList;
        }catch (Exception $e) {
            $this->addError(null, 'Не удалось получить список Регионо. Попробуйте позже.');
            return array();
        }
    }

    public function getCities($regionsId = [])
    {
        if(!$regionsId)
            return array();
        try{
            if(!is_array($this->city))
                $this->city = [];
            $citiesList = \models\Region::getInstance()->getCities(['id', 'name', 'region_id'], $regionsId);
            if($citiesList){
                foreach($citiesList as $num=>$city){
                    $citiesList[$num]->isChecked = in_array($city->id, $this->city);
                }
            }
            return $citiesList;
        }catch (Exception $e) {
            $error = YII_DEBUG ? $e->getMessage() : 'Не удалось получить список городов. Попробуйте позже.';
            $this->addError(null, $error);
            return array();
        }
    }

    public function getRegionsAndCountries($countriesId = [])
    {
        if(!$countriesId)
            return array();

        try{
            return \models\Country::getInstance()->getRegionsAndCountries($countriesId);
        }catch (Exception $e) {
            $error = YII_DEBUG ? $e->getMessage() : 'Не удалось получить список регионов. Попробуйте позже.';
            $this->addError(null, $error);
            return array();
        }
    }

    public function getCitiesAndRegions($regionsId = [])
    {
        if(!$regionsId)
            return array();

        try{
            return \models\Region::getInstance()->getCitiesAndRegions($regionsId);
        }catch (Exception $e) {
            $error = YII_DEBUG ? $e->getMessage() : 'Не удалось получить список городов. Попробуйте позже.';
            $this->addError(null, $error);
            return array();
        }
    }

    public function getGenderList()
    {
        return self::$_genderNames;
    }
}
