<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/9/14
 * Time: 1:12 AM
 * @property array $showButtonList
 * @property string $defaultImg
 */

class AdsForm extends FormModel {
    private static $_showButtonNames = [
        1 => [
            'text' => 'Использовать кнопку (Рекомедуеться)',
            'value' => 1,
        ],
        0 => [
            'text' => 'Не использовать кнопку',
            'value' => 0,
        ],
    ];

    private $_showButtons;
    private $_defaultImg = '/teaser_img.jpg';

    public $url;
    public $image;
    public $imageUrl;
    public $imageFile;
    public $caption;
    public $description;
    public $buttonText;
    public $clickPrice;
    public $mainCategory;
    public $additionalCategories = [1];
    public $geo;
    public $maxClicks;
    public $imageName;
    public $showButton = 1;
    public $imageUpload;
    public $cropParams;
    public $showUrl;
    public $type;
    public $blackList;
    public $whiteList;
    public $shock = false;
    public $adult = false;
    public $sms = false;
    public $animation = false;

    public function attributeLabels()
    {
        return array(
            'url' => 'Введите адрес рекламируемой страницы',
            'showUrl' => 'Введите URL отображаемый в объявлении',
            'image' => 'Изображение',
            'caption' => 'Введите заголовок',
            'description' => 'Введите описание',
            'buttonText' => 'Введите текст кнопки',
            'clickPrice' => 'Стоимость клика',
            'mainCategory' => 'Основная категория',
            'additionalCategories' => 'Тематика',
            'geo' => 'Гео',
            'maxClicks' => 'Максимальное количество переходов',
            'showButton' => 'Кнопка призыва к действию:',
            'type' => 'Размер блока',
            'imageUrl' => 'Изображение',
            'shock' => 'Товарный тизер',
            'adult' => 'Реклама для взрослых',
            'sms' => 'Смс-регистрация',
            'animation' => 'Анимированная реклама',
        );
    }

    public function rules()
    {
        return array(
            ['caption, url, clickPrice, showUrl', 'required', 'message' => 'Поле "{attribute}" должно быть заполнено'],
            ['clickPrice', 'numerical', 'min' => 0.01, 'message' => 'Поле {attribute} может содержать только числовые значения'],
            ['maxClicks', 'numerical', 'min' => 1, 'integerOnly' => true, 'message' => 'Поле {attribute} может содержать только числовые значения'],
            //['caption', 'length', 'max' => 30, 'message' => 'Длина названия не может превышать 30 символов'],
            //['description', 'length', 'max' => 70, 'message' => 'Длина описания не может превышать 70 символов'],
            ['url', 'url', 'message' => 'У поля {attribute} не правильный формат'],
            ['additionalCategories, geo, mainCategory', 'default'],
            ['buttonText, description, image, showButton, cropParams, additionalCategories, type, imageUrl, blackList, whiteList, shock, adult, sms, animation', 'removeWrongOptionAdditional','default'],
            ['caption, url, clickPrice, showUrl, clickPrice, maxClicks, buttonText, description, showButton, image, cropParams, type, imageUrl', 'filter', 'filter' => 'htmlspecialchars'],
        );
    }

    public function validate($attributes = NULL, $clearErrors = true)
    {

        if(!$this->imageUrl && (!$_FILES || !isset($_FILES['AdsForm']['tmp_name']['image']) || !$_FILES['AdsForm']['tmp_name']['image'])){
            $this->addError('image', 'Обязательно нужно выбрать изображение');
            return false;
        }

        return parent::validate($attributes, $clearErrors);
    }

    public function ads($campaignId = null, $id = null)
    {
        $adv = \models\Ads::getInstance();

        if($id){
            $adv->initById($id);
        }else{
            $adv->adsContent = new \ads\AdsStandard();
        }

        if($_FILES && isset($_FILES['AdsForm']['tmp_name']['image']) && !empty($_FILES['AdsForm']['tmp_name']['image'])){
            try{
                $this->cropParams = json_decode($this->cropParams, 1);

                $this->cropImage($_FILES['AdsForm']['tmp_name']['image'], $this->cropParams['x1'], $this->cropParams['y1'], $this->cropParams['width'], $this->cropParams['height']);
                $image = \core\ImageWorker::uploadImage($_FILES['AdsForm']['tmp_name']['image']);
            }catch(Exception $e){
                $this->addError('update', 'Не удалось загрузить изображение: '.$e->getMessage());
                $this->cropParams = null;
                return false;
            }
        }else{
            $image = is_array($this->imageUrl) && $this->imageUrl ? $this->imageUrl : [
                'image' => $this->_defaultImg,
                'addressAlias' => 'image1',
            ];
        }

        $adv->adsContent->caption = $this->caption;
        $adv->adsContent->url = $this->url;
        $adv->adsContent->showUrl = $this->showUrl;
        $adv->adsContent->description = $this->description;
        $adv->adsContent->buttonText = $this->buttonText;
        $adv->adsContent->imageUrl = $image;
        $adv->adsContent->showButton = (int)$this->showButton;
        $adv->campaignId = $campaignId;
        $adv->clickPrice = $this->clickPrice;
        $adv->maxClicks = $this->maxClicks;
        $adv->additionalCategories = $this->additionalCategories;
        $adv->blackList = $this->blackList;
        $adv->whiteList = $this->whiteList;
        $adv->shoshockck = (bool)$this->shock;
        $adv->adult = (bool)$this->adult;
        $adv->sms = (bool)$this->sms;
        $adv->animation = (bool)$this->animation;

        try{
            $geo = \models\Campaign::getInstance()->getGeoCodes($campaignId);

            if($geo->countries)
                $adv->geoCountries = $geo->countries;
            if($geo->regions)
                $adv->geoRegions = $geo->regions;

            if(!$id)
                return $adv->saveAds();
            else
                return $adv->updateAds();
        }catch(Exception $e) {
            $this->addError(null, $e->getMessage());
            return false;
        }
    }

    public function getShowButtonList()
    {
        if($this->_showButtons === null){
            foreach(self::$_showButtonNames as $button){
                $this->_showButtons[] = (object)[
                    'text' => $button['text'],
                    'name' => '['.__CLASS__.']show-button',
                    'value' => $button['value'],
                    'checked' => (bool)$button['value'] === (bool)$this->showButton,
                ];
            }
        }
        return $this->_showButtons;
    }

    public function getImageFormUrl($imageUrl = null)
    {
        $img = $this->defaultImg;

        if($imageUrl){
            if(strpos($imageUrl, '/files/') !== false)
                $img = '/images/'.substr($imageUrl, strpos($imageUrl, '/files/'));
        }
        return $img;
    }

    public function initByAdsId($id = null, $campaignId = null)
    {
        if($id){
            $ads = \models\Ads::getInstance();
            $ads->initById($id);

            $img = isset($ads->adsContent->imageUrl) ? $ads->adsContent->imageUrl : null;

            $this->url = $ads->adsContent->url;
            $this->description = isset($ads->adsContent->description) ? $ads->adsContent->description : null;
            $this->caption = isset($ads->adsContent->caption) ? $ads->adsContent->caption : null;
            $this->buttonText = isset($ads->adsContent->buttonText) ? $ads->adsContent->buttonText : null;
            $this->showUrl = isset($ads->adsContent->showUrl) ? $ads->adsContent->showUrl : null;
            $this->showButton = isset($ads->adsContent->showButton) ? (bool)$ads->adsContent->showButton : true;
            $this->imageUrl = $ads->adsContent->imageFile;
            $this->image = $this->getImageFormUrl($img);
            $this->imageFile = $ads->imageFile;
            $this->clickPrice = $ads->clickPrice;
            $this->maxClicks = $ads->maxClicks;
            $this->additionalCategories = $ads->additionalCategories;
            $this->whiteList = $ads->whiteList;
            $this->blackList = $ads->blackList;
            $this->shock = (bool)$ads->shock;
            $this->adult = (bool)$ads->adult;
            $this->sms = (bool)$ads->sms;
            $this->animation = (bool)$ads->animation;
            $this->isNew = false;
        }else{
            $campaign = \models\Campaign::getInstance();

            if(!$campaignId)
                $campaignId = \Yii::app()->request->getQuery('campaignId');

            $campaign->initById($campaignId);

            $this->clickPrice = $campaign->clickPrice;
            $this->additionalCategories = $campaign->categories;
            $this->blackList = $campaign->blackList;
            $this->whiteList = $campaign->whiteList;
        }
    }

    private function cropImage($image, $x_o, $y_o, $w_o, $h_o)
    {
        try{
            $imageMagick = new Imagick($image);
            $imageMagick = $imageMagick->coalesceImages();

            $i = 0;
            foreach($imageMagick as $frame){
                $frame->cropImage($w_o, $h_o, $x_o, $y_o);
                $frame->thumbnailImage($w_o, $h_o);
                $frame->setImagePage($w_o, $h_o, 0, 0);
                $i++;
            }

            if($i > 1)
                $this->animation = true;

            $imageMagick = $imageMagick->deconstructImages();
            $result = $imageMagick->writeImages($image, true);
        }catch(Exception $e){
            $this->addError('save', $e->getMessage());
            $result = false;
        }

        return $result;
        /*
        if (($x_o < 0) || ($y_o < 0) || ($w_o < 0) || ($h_o < 0)) {
            return false;
        }
        list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
        $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
        $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
        if ($ext) {
            $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
            $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
        } else {
            return false;
        }
        if ($x_o + $w_o > $w_i) $w_o = $w_i - $x_o; // Если ширина выходного изображения больше исходного (с учётом x_o), то уменьшаем её
        if ($y_o + $h_o > $h_i) $h_o = $h_i - $y_o; // Если высота выходного изображения больше исходного (с учётом y_o), то уменьшаем её
        $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
        imagecopy($img_o, $img_i, 0, 0, $x_o, $y_o, $w_o, $h_o); // Переносим часть изображения из исходного в выходное
        $func = 'image'.$ext; // Получаем функция для сохранения результата
        return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
        */
    }

    public function getDefaultImg()
    {
        return '/images'.$this->_defaultImg;
    }

}