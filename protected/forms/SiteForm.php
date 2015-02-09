<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 16.02.14
 * Time: 19:32
 * @property array $cats
 * @property array $categories
 * @property array  $bannedCats
 * @property array  $additionalCats
 * @property string categoryName
 * @property string additionalCategoryName
 * @property bool $isAllowedSwitch
 */

class SiteForm extends FormModel
{
    private $_categories;

    public $id;
    public $url;
    public $mirror;
    public $category = 1;
    public $bannedCategories;
    public $additionalCategory;
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

    public function attributeLabels()
    {
        return [
            'url' => \Yii::t('webmaster_site','Введите адрес сайта:'),
            'mirror' => \Yii::t('webmaster_site','Введите адрес зеркала сайта:'),
            'category' => Yii::t('webmaster_site','Выберите категорию вашего сайта'),
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
        ];
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            ['url, category', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['url', 'url', 'message' => 'У поля {attribute} не правильный формат'],
            ['mirror, additionalCategory, bannedCategories, description, statsUrl, statsLogin, statsPassword, containsAdult, allowShock, allowAdult, allowSms, allowAnimation, status', 'default'],
        );
    }

    public function initSite($id = null)
    {
        try{
            $site = \models\Site::getInstance();

            if($id !== null)
                $site->initById($id);

            $this->id = $site->getId();
            $this->url = $site->url;
            $this->mirror = $site->mirror;
            $this->category = $site->category;
            $this->additionalCategory = $site->additionalCategory;
            $this->bannedCategories = $site->bannedCategories;
            $this->description = $site->description;
            $this->statsUrl = $site->statsUrl;
            $this->statsLogin = $site->statsLogin;
            $this->statsPassword = $site->statsPassword;
            $this->containsAdult = $site->containsAdult;
            $this->allowShock = !$site->allowShock;
            $this->allowAdult = !$site->allowAdult;
            $this->allowSms = !$site->allowSms;
            $this->allowAnimation = !$site->allowAnimation;
            $this->status = $site->status;

            return true;
        }catch(Exception $e){
            $error = 'Возникла ошибка при изменении рекламной площадки';
            if(YII_DEBUG)
                $error .= $e->getMessage();
            else
                $error .= '. Попробуйте еще раз позже.';

            $this->addError(null, $error);
            return false;
        }
    }

    public function site($id = null)
    {
        if($this->bannedCategories !== null && !is_array($this->bannedCategories)){
            $this->bannedCategories = explode(',', $this->bannedCategories);
        }

        if($this->category && strpos($this->category, ',') !== false){
            $additionalCat = explode(',', $this->category);
            $this->category = $additionalCat[0];

            if(isset($additionalCat[1]) && $additionalCat[1])
                $this->additionalCategory = $additionalCat[1];
        }

        try{
            $site = \models\Site::getInstance();

            if($id !== null)
                $site->initById($id);

            $site->url = $this->url;
            $site->mirror = $this->mirror;
            $site->category = $this->category;
            $site->additionalCategory = $this->additionalCategory;
            $site->bannedCategories = $this->bannedCategories;
            $site->description = $this->description;
            $site->statsUrl = $this->statsUrl;
            $site->statsLogin = $this->statsLogin;
            $site->statsPassword = $this->statsPassword;
            $site->containsAdult = $this->containsAdult ? true : false;
            $site->allowShock = $this->allowShock ? false : true;
            $site->allowAdult = $this->allowAdult ? false : true;
            $site->allowSms = $this->allowSms ? false : true;
            $site->allowAnimation = $this->allowAnimation ? false : true;
            $site->status = $this->status;

            if(!$id){
                return $site->saveSite();
            }else{
                return $site->update();
            }
        }catch(Exception $e){
            $this->addError(null, 'Возникла ошибка: '.$e->getMessage());
            return false;
        }
    }

    public function getCategoryName($catId = null)
    {
        if($catId === null)
            $catId = $this->category;

        return \models\Site::getInstance()->getCategoryName($catId);
    }

    public function getAdditionalCategoryName()
    {
        if($this->additionalCategory)
            return $this->getCategoryName($this->additionalCategory);
        else
            return '';
    }

    public function getCategories()
    {
        $cats = [];

        foreach($this->cats as $cat){
            $cats[] = (object)[
                'id' => $cat->id,
                'name' => $cat->name,
                'isChecked' => $this->category == $cat->id,
            ];
        }

        return $cats;
    }

    public function getBannedCats()
    {
        $banedCats = [];

        foreach($this->cats as $cat){
            $banedCats[] = (object)[
                'id' => $cat->id,
                'name' => $cat->name,
                'isChecked' => is_array($this->bannedCategories) && in_array($cat->id, $this->bannedCategories),
            ];
        }

        return $banedCats;
    }

    public function getAdditionalCats()
    {
        $additionalCats = [];

        foreach($this->cats as $cat){
            $additionalCats[] = (object)[
                'id' => $cat->id,
                'name' => $cat->name,
                'isChecked' => $this->additionalCategory == $cat->id,
            ];
        }

        return $additionalCats;
    }

    public function getIsAllowedSwitch()
    {
        return \models\Site::getInstance()->getIsModerated($this->status);
    }

    public function getCats()
    {
        if($this->_categories === null){
            $categories = [];

            try{
                $categories = \models\Category::getInstance()->find(['id', 'description', 'where' => ['active' => 1]]);
            }catch(Exception $e){
                $error = 'Не удалось получить список категорий';
                if(YII_DEBUG)
                    $error .= ': '.$e->getMessage();

                $this->addError(null, $error);
            }

            foreach($categories as $cat){
                $this->_categories[] = (object)[
                    'id' => $cat->id,
                    'name' => $cat->description,
                ];
            }

            unset($categories);
        }

        return $this->_categories;
    }
}
