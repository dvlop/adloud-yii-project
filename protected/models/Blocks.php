<?php

namespace application\models;

use application\components\BaseModel;
use application\models\entities\BlockContentEntity;
use application\models\Sites;
use core\RedisIO;

/**
 * This is the model class for table "blocks".
 *
 * The followings are the available columns in table 'blocks':
 * @property string $shows
 * @property integer $clicks
 * @property string $id
 * @property string $site_id
 * @property string $categories
 * @property string $description
 * @property string $ads_type
 * @property integer $status
 * @property string $color_scheme
 * @property string $fill_type
 * @property string $create_date
 * @property string $content
 * @property string $type
 * @property bool $allow_shock
 * @property bool $allow_adult
 * @property bool $allow_sms
 * @property bool $allow_animation
 *
 * @property \application\models\behaviors\PublishBehavior $publisher
 *
 * The followings are the available model relations:
 * @property Sites $site
 * @property WebmasterStats[] $webmasterStats
 * @property \stdClass[] $formats
 * @property \stdClass[] $sizes
 * @property string $format
 * @property string $formatName
 * @property string $size
 * @property \application\models\entities\BlockContentEntity $entity
 * @property integer $siteId
 * @property boolean $allowShock
 * @property boolean $allowAdult
 * @property boolean $allowSms
 * @property boolean $captionOpacity
 * @property boolean $textOpacity
 * @property boolean $buttonOpacity
 * @property boolean $backgroundOpacity
 * @property boolean $borderOpacity
 */
class Blocks extends BaseModel
{
    const FORMAT_MAIN = 'main';
    const FORMAT_SIMPLE = 'simple';
    const FORMAT_MARKET = 'market';

    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    private static $_formats = [
        self::FORMAT_MAIN => 'Основной',
        self::FORMAT_SIMPLE => 'Простой',
        self::FORMAT_MARKET => 'Товарный'
    ];

    private static $_sizes = [
        self::FORMAT_SIMPLE => [
            '160x280',
            '200x90',
            '200x350',
            '240x100',
            '240x120',
            '300x150',
            '320x160',
        ],
        self::FORMAT_MARKET => [
            '160x300',
            '200x300',
            '240x134',
            '240x300',
            '300x125',
            '300x150',
            '336x140',
        ],
    ];

    private $_content;
    private $_blockFormats;
    private $_blockSizes;
    private $_categories;
    private $_userId;

    private $_siteModel;
    private $_isNew = false;

    public $isUpdate = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'blocks';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('site_id', 'required'),
            array('clicks, status', 'numerical', 'integerOnly'=>true),
            array('description', 'length', 'max'=>255),
            array('ads_type, color_scheme, fill_type', 'length', 'max'=>64),
            array('type', 'length', 'max'=>15),
            array('shows, categories, create_date, params', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'site' => [self::BELONGS_TO, '\application\models\Sites', 'site_id'],
            'webmasterStats' => [self::HAS_MANY, '\application\models\WebmasterStats', 'block_id'],
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
            'shows' => \Yii::t('block_market', 'Shows'),
            'clicks' => \Yii::t('block_market', 'Clicks'),
            'id' => \Yii::t('block_market', 'ID'),
            'site_id' => \Yii::t('block_market', 'Site'),
            'categories' => \Yii::t('block_market', 'Categories'),
            'ads_type' => \Yii::t('block_market', 'Ads Type'),
            'status' => \Yii::t('block_market', 'Status'),
            'color_scheme' => \Yii::t('block_market', 'Color Scheme'),
            'fill_type' => \Yii::t('block_market', 'Fill Type'),
            'create_date' => \Yii::t('block_market', 'Create Date'),
            'params' => \Yii::t('block_market', 'Params'),
            'type' => \Yii::t('block_market', 'Type'),
            'caption' => \Yii::t('block_market', 'Введите название блока:'),
            'format' => '',
            'size' => \Yii::t('block_market', 'Выберите формат тизера:'),
            'verticalCount' => \Yii::t('block_market', 'Количество тизеров по вертикали:'),
            'horizontalCount' => \Yii::t('block_market', 'Количество тизеров по горизонтали:'),
            'captionColor' => \Yii::t('block_market', 'Цвет заголовка:'),
            'textColor' => \Yii::t('block_market', 'Цвет текста:'),
            'buttonColor' => \Yii::t('block_market', 'Цвет кнопки:'),
            'backgroundColor' => \Yii::t('block_market', 'Цвет заливки:'),
            'borderColor' => \Yii::t('block_market', 'Цвет границы:'),
            'border' => \Yii::t('block_market', 'Граница:'),
            'width' => \Yii::t('block_market', 'Выберите ширину рекламного блока:'),
            'borderType' => \Yii::t('block_market', 'Тип рамки блока:'),
            'indentAds' => \Yii::t('block_market', 'Отступы между тизерами:'),
            'indentBorder' => \Yii::t('block_market', 'Отступ от границы тизера:'),
            'adsBorderColor' => \Yii::t('block_market', 'Цвет границы тизера:'),
            'adsBorder' => \Yii::t('block_market', 'Размер границы тизера:'),
            'asdBorderType' => \Yii::t('block_market', 'Тип рамки тизера:'),
            'adsBackColor' => \Yii::t('block_market', 'Цвет фона тизера:'),
            'backHoverColor' => \Yii::t('block_market', 'Цвет фона при наведении:'),
            'imgWidth' => \Yii::t('block_market', 'Ширина изображения:'),
            'borderRadius' => \Yii::t('block_market', 'Скругление углов:'),
            'imgBorderColor' => \Yii::t('block_market', 'Цвет границы картинки:'),
            'imgBorderWidth' => \Yii::t('block_market', 'Размер границы картинки:'),
            'imgBorderType' => \Yii::t('block_market', 'Тип рамки картинки:'),
            'font' => \Yii::t('block_market', 'Выберите шрифт'),
            'useDescription' => \Yii::t('block_market', 'Использовать описание'),
            'textPosition' => \Yii::t('block_market', 'Позиция текста:'),
            'captionFontSize' => \Yii::t('block_market', 'Размер шрифта:'),
            'captionStyle' => \Yii::t('block_market', 'Стили'),
            'captionHoverColor' => \Yii::t('block_market', 'Цвет заголовка при наведении:'),
            'captionHoverFontSize' => \Yii::t('block_market', 'Размер шрифта:'),
            'captionHoverStyle' => \Yii::t('block_market', 'Стили'),
            'descLimit' => \Yii::t('block_market', 'Символов'),
            'descFontSize' => \Yii::t('block_market', 'Размер шрифта:'),
            'descStyle' => \Yii::t('block_market', 'Стили'),
            'alignment' => \Yii::t('block_market', 'Выравнивание'),
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Blocks the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Blocks[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Blocks
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function beforeSave()
    {
        $this->_isNew = $this->getIsNewRecord();

        if($this->_isNew){
            if($this->clicks === null)
                $this->clicks = 0;
            if($this->shows === null)
                $this->shows = 0;

            if($this->categories === null){
                if($this->site)
                    $site = $this->site;
                else
                    $site = Sites::model()->findByPk($this->getSiteId());

                $cats = [];
                if($site){
                    foreach($site->getSiteCats() as $cat){
                        $cats[] = $cat->id;
                    }
                }else{
                    $this->addError(null, 'Не удалось найти сайт блока');
                    return false;
                }

                $this->categories = $this->parseDbString($cats);
            }

            if($this->color_scheme === null)
                $this->color_scheme = 'cheerful';

            if($this->fill_type === null)
                $this->fill_type = 'gray';

            if($this->create_date === null)
                $this->create_date = date(\Yii::app()->params['dateFormat']);

            if($this->type === null){
                $this->type = $this->getEntity()->splitFormat;
            }

            if($this->ads_type === null && $this->type !== self::FORMAT_MAIN)
                $this->ads_type = $this->getEntity()->format;

            if($this->status === null)
                $this->status = self::STATUS_PUBLISHED;

            $this->allow_shock = $this->getSiteModel()->getAllowShock();

            $this->allow_adult = $this->getSiteModel()->getAllowAdult();

            $this->allow_sms = $this->getSiteModel()->getAllowSms();

            $this->allow_animation = $this->getSiteModel()->getAllowAnimation();
        }

        $this->content = \CJSON::encode($this->getEntity());

        return parent::beforeSave();
    }

    public function afterSave()
    {
        parent::afterSave();
    }

    public function setFormat($format)
    {
        if(!isset(self::$_formats[$format]))
            $format = $this->getFormat();

        $this->getEntity(['splitFormat' => $format])->splitFormat = $format;
        $this->type = $format;
    }

    public function getFormat()
    {
        return $this->getEntity()->splitFormat ? $this->getEntity()->splitFormat : self::FORMAT_SIMPLE;
    }

    public function getFormatName()
    {
        return isset(self::$_formats[$this->getFormat()]) ? self::$_formats[$this->getFormat()] : self::$_formats[self::FORMAT_SIMPLE];
    }

    public function setSize($size)
    {
        if(!$this->checkSize($size))
            $size = self::$_sizes[$this->getFormat()][0];

        $this->getEntity()->format = $size;
        $this->ads_type = $size;
    }

    public function getSize()
    {
        return $this->getEntity()->format ? $this->getEntity()->format : self::$_sizes[self::FORMAT_SIMPLE];
    }

    /**
     * @return \stdClass[]
     */
    public function getFormats()
    {
        if($this->_blockFormats === null){
            $this->_blockFormats = [];

            foreach(self::$_formats as $id => $name){
                $this->_blockFormats[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'checked' => $id == $this->getFormat(),
                ];
            }
        }

        return $this->_blockFormats;
    }

    /**
     * @return \stdClass[]
     */
    public function getSizes()
    {
        if($this->_blockSizes === null){
            $this->_blockSizes = [];

            foreach(self::$_sizes[$this->getFormat()] as $size){
                $this->_blockSizes[] = (object)[
                    'value' => $size,
                    'name' => $size,
                    'checked' => $size == $this->getSize(),
                    'width' => $this->getWH($size)->width,
                    'height' => $this->getWH($size)->height,
                ];
            }
        }

        return $this->_blockSizes;
    }

    public function checkFormat($format)
    {
        return isset(self::$_formats[$format]);
    }

    public function checkSize($size)
    {
        if($this->getFormat() == self::FORMAT_MAIN)
            return true;
        return in_array($size, self::$_sizes[$this->getFormat()]);
    }

    public function setCaption($description)
    {
        $description = (string)$description;
        $this->getEntity()->description = $description;
        $this->description = $description;
    }

    public function getCaption()
    {
        $description = trim($this->getEntity()->description);
        if($description)
            $this->description = $description;
        else
            $this->description = trim($this->description);
        return $this->description;
    }

    public function setVerticalCount($count)
    {
        $this->getEntity()->verticalCount = intval($count);
    }

    public function getVerticalCount()
    {
        return intval($this->getEntity()->verticalCount);
    }

    public function setHorizontalCount($count)
    {
        $this->getEntity()->horizontalCount = intval($count);
    }

    public function getHorizontalCount()
    {
        return intval($this->getEntity()->horizontalCount);
    }

    public function setCaptionColor($color)
    {
        $this->getEntity()->setCaptionColor($color);
    }

    public function getCaptionColor()
    {
        return trim($this->getEntity()->captionColor);
    }

    public function setTextColor($color)
    {
        $this->getEntity()->setTextColor($color);
    }

    public function getTextColor()
    {
        return trim($this->getEntity()->textColor);
    }

    public function setButtonColor($color)
    {
        $this->getEntity()->setButtonColor($color);
    }

    public function getButtonColor()
    {
        return trim($this->getEntity()->buttonColor);
    }

    public function setBackgroundColor($color)
    {
        $this->getEntity()->setBackgroundColor($color);
    }

    public function getBackgroundColor()
    {
        return trim($this->getEntity()->backgroundColor);
    }

    public function setBorderColor($color)
    {
        $this->getEntity()->setBorderColor($color);
    }

    public function getBorderColor()
    {
        return trim($this->getEntity()->borderColor);
    }

    public function setBorder($size)
    {
        $this->getEntity()->border = intval($size);
    }

    public function getBorder()
    {
        return intval($this->getEntity()->border);
    }

    public function setSiteId($id)
    {
        $this->site_id = intval($id);
        $this->getEntity()->siteId = $this->site_id;
    }

    public function getSiteId()
    {
        return intval($this->site_id);
    }

    public function getUserId()
    {
        if($this->_userId === null){
            $this->_userId = $this->site ? $this->site->getUserId() : \Yii::app()->user->id;
        }

        return $this->_userId;
    }

    public function setCaptionOpacity($value)
    {
        $this->getEntity()->setCaptionOpacity($value);
    }

    public function getCaptionOpacity()
    {
        return $this->getEntity()->getCaptionOpacity();
    }

    public function setTextOpacity($value)
    {
        $this->getEntity()->setTextOpacity($value);
    }

    public function getTextOpacity()
    {
        return $this->getEntity()->getTextOpacity();
    }

    public function setButtonOpacity($value)
    {
        $this->getEntity()->setButtonOpacity($value);
    }

    public function getButtonOpacity()
    {
        return $this->getEntity()->getButtonOpacity();
    }

    public function setBackgroundOpacity($value)
    {
        $this->getEntity()->setBackgroundOpacity($value);
    }

    public function getBackgroundOpacity()
    {
        return $this->getEntity()->getBackgroundOpacity();
    }

    public function setBorderOpacity($value)
    {
        $this->getEntity()->setBorderOpacity($value);
    }

    public function getBorderOpacity()
    {
        return $this->getEntity()->getBorderOpacity();
    }

    public function setAdsBorderOpacity($val)
    {
        $this->getEntity()->setAdsBorderOpacity($val);
    }

    public function getAdsBorderOpacity()
    {
        return $this->getEntity()->getAdsBorderOpacity();
    }

    public function setAdsBackOpacity($val)
    {
        $this->getEntity()->setAdsBackOpacity($val);
    }

    public function getAdsBackOpacity()
    {
        return $this->getEntity()->getAdsBackOpacity();
    }

    public function setBackHoverOpacity($val)
    {
        $this->getEntity()->setBackHoverOpacity($val);
    }

    public function getBackHoverOpacity()
    {
        return $this->getEntity()->getBackHoverOpacity();
    }

    public function setImgBorderOpacity($val)
    {
        $this->getEntity()->setImgBorderOpacity($val);
    }

    public function getImgBorderOpacity()
    {
        return $this->getEntity()->getImgBorderOpacity();
    }

    public function setCaptionHoverOpacity($val)
    {
        $this->getEntity()->setCaptionHoverOpacity($val);
    }

    public function getCaptionHoverOpacity()
    {
        return $this->getEntity()->getCaptionHoverOpacity();
    }

    public function setWidth($width)
    {
        $this->getEntity()->setWidth($width);
    }

    public function getWidth()
    {
        $width = $this->getEntity()->width;
        $width = str_replace('px', '', $width);
        $width = str_replace('%', '', $width);

        return $width;
    }

    public function setBorderType($type)
    {
        $this->getEntity()->setBorderType($type);
    }

    public function getBorderType()
    {
        return $this->getEntity()->borderType;
    }

    /**
     * @return \stdClass[]
     */
    public function getBorderTypes()
    {
        return $this->getEntity()->getBorderTypes();
    }

    public function setIndentAds($indent)
    {
        $this->getEntity()->indentAds = intval($indent);
    }

    public function getIndentAds()
    {
        return $this->getEntity()->indentAds;
    }

    public function setIndentBorder($indent)
    {
        $this->getEntity()->indentBorder = intval($indent);
    }

    public function getIndentBorder()
    {
        return $this->getEntity()->indentBorder;
    }

    public function setAdsBorderColor($color)
    {
        $this->getEntity()->setAdsBorderColor($color);
    }

    public function getAdsBorderColor()
    {
        return $this->getEntity()->adsBorderColor;
    }

    public function setAdsBorder($border)
    {
        $this->getEntity()->adsBorder = intval($border);
    }

    public function getAdsBorder()
    {
        return $this->getEntity()->adsBorder;
    }

    public function setAdsBorderType($type)
    {
        $this->getEntity()->setAdsBorderType($type);
    }

    /**
     * @return \stdClass[]
     */
    public function getAdsBorderTypes()
    {
        return $this->getEntity()->getAdsBorderTypes();
    }

    public function setAdsBackColor($color)
    {
        $this->getEntity()->setAdsBackColor($color);
    }

    public function getAdsBackColor()
    {
        return $this->getEntity()->adsBackColor;
    }

    public function setBackHoverColor($color)
    {
        $this->getEntity()->setBackHoverColor($color);
    }

    public function getBackHoverColor()
    {
        return $this->getEntity()->backHoverColor;
    }

    public function setImgWidth($width)
    {
        $this->getEntity()->imgWidth = intval($width);
    }

    public function getImgWidth()
    {
        $width = $this->getEntity()->imgWidth;
        $width = str_replace('px', '', $width);
        $width = str_replace('%', '', $width);

        return $width;
    }

    public function setBorderRadius($radius)
    {
        $this->getEntity()->borderRadius = intval($radius);
    }

    public function getBorderRadius()
    {
        return $this->getEntity()->borderRadius;
    }

    public function setImgBorderColor($color)
    {
        $this->getEntity()->setImgBorderColor($color);
    }

    public function getImgBorderColor()
    {
        return $this->getEntity()->imgBorderColor;
    }

    public function setImgBorderWidth($width)
    {
        $this->getEntity()->imgBorderWidth = intval($width);
    }

    public function getImgBorderWidth()
    {
        return $this->getEntity()->imgBorderWidth;
    }

    public function setImgBorderType($type)
    {
        $this->getEntity()->setImgBorderType($type);
    }

    public function setAlignment($alignment)
    {
        $this->getEntity()->setAlignment($alignment);
    }

    /**
     * @return \stdClass[]
     */
    public function getAlignments()
    {
        return $this->getEntity()->getAlignments();
    }

    /**
     * @return \stdClass[]
     */
    public function getImgBorderTypes()
    {
        return $this->getEntity()->getImgBorderTypes();
    }

    public function setFont($font)
    {
        $this->getEntity()->setFont($font);
    }

    /**
     * @return \stdClass[]
     */
    public function getFonts()
    {
        return $this->getEntity()->getFonts();
    }

    public function setUseDescription($desc)
    {
        $this->getEntity()->setUseDescription($desc);
    }

    public function getUseDescription()
    {
        return $this->getEntity()->useDescription;
    }

    public function setTextPosition($pos)
    {
        $this->getEntity()->setTextPosition($pos);
    }

    public function getTextPosition()
    {
        return $this->getEntity()->getTextPosition();
    }

    /**
     * @return \stdClass[]
     */
    public function getTextPositions()
    {
        return $this->getEntity()->getTextPositions($this->getIsNewRecord());
    }

    public function setCaptionFontSize($size)
    {
        $this->getEntity()->captionFontSize = intval($size);
    }

    public function getCaptionFontSize()
    {
        return $this->getEntity()->captionFontSize;
    }

    public function setCaptionStyle($style)
    {
        $this->getEntity()->captionStyle = (string)$style;
    }

    public function getCaptionStyle()
    {
        return $this->getEntity()->captionStyle;
    }

    public function setCaptionHoverColor($color)
    {
        $this->getEntity()->setCaptionHoverColor($color);
    }

    public function getCaptionHoverColor()
    {
        return $this->getEntity()->captionHoverColor;
    }

    public function setCaptionHoverFontSize($size)
    {
        $this->getEntity()->captionHoverFontSize = intval($size);
    }

    public function getCaptionHoverFontSize()
    {
        return $this->getEntity()->captionHoverFontSize;
    }

    public function setCaptionHoverStyle($style)
    {
        $this->getEntity()->captionHoverStyle = (string)$style;
    }

    public function getCaptionHoverStyle()
    {
        return $this->getEntity()->captionHoverStyle;
    }

    public function setDescLimit($limit)
    {
        $this->getEntity()->descLimit = intval($limit);
    }

    public function getDescLimit()
    {
        return $this->getEntity()->descLimit;
    }

    public function setDescFontSize($size)
    {
        $this->getEntity()->descFontSize = intval($size);
    }

    public function getDescFontSize()
    {
        return $this->getEntity()->descFontSize;
    }

    public function setDescStyle($style)
    {
        $this->getEntity()->descStyle = (string)$style;
    }

    public function getDescStyle()
    {
        return $this->getEntity()->descStyle;
    }

    public function setWidthStyle($style)
    {
        $this->getEntity()->setWidthStyle($style);
    }

    public function getWidthStyle()
    {
        return $this->getEntity()->widthStyle;
    }

    public function setAllowShock($boll)
    {
        $this->allow_shock = (bool)$boll;
    }

    public function getallowShock()
    {
        if($this->allow_shock === null){
            $this->allow_shock = $this->site ? (bool)$this->site->allowShock : false;
        }

        return $this->allow_shock;
    }

    public function setAllowAdult($boll)
    {
        $this->allow_adult = (bool)$boll;
    }

    public function getAllowAdult()
    {
        if($this->allow_adult === null){
            $this->allow_adult = $this->site ? (bool)$this->site->allowAdult : false;
        }

        return $this->allow_adult;
    }

    public function setAllowSms($boll)
    {
        $this->allow_sms = (bool)$boll;
    }

    public function getAllowSms()
    {
        if($this->allow_sms === null){
            $this->allow_sms = $this->site ? (bool)$this->site->allowSms : false;
        }

        return $this->allow_sms;
    }

    public function setAllowAnimation($boll)
    {
        $this->allow_animation = (bool)$boll;
    }

    public function getAllowAnimation()
    {
        if($this->allow_animation === null){
            $this->allow_animation = $this->site ? (bool)$this->site->allowAnimation : false;
        }

        return $this->allow_animation;
    }

    /**
     * @return string
     */
    public function getSerializedMainParams()
    {
        return $this->getEntity()->getSerializedMainParams();
    }

    /**
     * @return string
     */
    public function getSerializedMainFormIds()
    {
        return $this->getEntity()->getSerializedMainFormIds($this->getModelName());
    }

    /**
     * @return integer[]
     */
    public function getBlockCategories()
    {
        if($this->_categories === null){
            $this->_categories = $this->parseDbArray($this->categories);
        }

        return $this->_categories;
    }

    /**
     * @param array $params
     * @return \application\models\entities\BlockContentEntity
     */
    public function getEntity($params = [])
    {
        if($this->_content === null){
            if($this->content === null)
                $content = [];
            else
                $content = \CJSON::decode($this->content);

            if($this->id)
                $params = array_merge($params, ['id' => $this->id]);

            $this->_content = new BlockContentEntity(array_merge($content, $params));
            $this->_content->siteId = $this->getSiteId();
            $this->_content->categories = $this->getBlockCategories();
            $this->_content->userId = $this->getUserId();
            $this->_content->allowShock = $this->getallowShock();
            $this->_content->allowAdult = $this->getAllowAdult();
            $this->_content->allowSms = $this->getAllowSms();
            $this->_content->allowAnimation = $this->getAllowAnimation();
        }

        return $this->_content;
    }

    /**
     * @param array|null $params
     * @return array|null
     */
    public function getPreview($params = null)
    {
        if($params !== null && is_array($params)){
            if(!isset($params['useDescription']))
                $params['useDescription'] = '';

            $this->setAttributes($params);
        }

        return $this->getEntity()->getPreview($this->id);
    }

    /**
     * @param string $size
     * @return \stdClass
     */
    private function getWH($size)
    {
        $wh = explode('x', $size);
        return (object)[
            'width' => isset($wh[0]) ? intval($wh[0]) : 0,
            'height' => isset($wh[1]) ? intval($wh[1]) : 0,
        ];
    }

    public function getBlockTotalTraffic(){
        $result = [
            'id' => $this->id,
            'shows' => 0,
            'clicks' => 0,
        ];
        $result['shows'] += RedisIO::get("block-shows:{$this->id}") ? RedisIO::get("block-shows:{$this->id}") : ($this->shows ? $this->shows : 0);
        $result['clicks'] += RedisIO::get("block-clicks:{$this->id}") ? RedisIO::get("block-clicks:{$this->id}") : ($this->clicks ? $this->clicks : 0);

        return $result;
    }

    public function getBlockBeforeTraffic($startDate = null, $endDate = null){
        $result = [
            'id' => $this->id,
            'shows' => 0,
            'clicks' => 0,
        ];

        $sql = 'SELECT shows,clicks FROM webmaster_stats WHERE block_id = :blockId';
        $params = [':blockId' => $this->id];

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

    public function getBlockTodayTraffic(){
        $total = $this->getBlockTotalTraffic();
        $before = $this->getBlockBeforeTraffic();

        return [
            'id' => $this->id,
            'shows' => ($total['shows'] - $before['shows']),
            'clicks' => ($total['clicks'] - $before['clicks'])
        ];
    }

    public function getBlockPeriodTraffic($startDate, $endDate){
        $today = (new \DateTime('now'))->format(\Yii::app()->params->dateFormat);
        $periodTraffic = [
            'id' => $this->id,
            'description' => $this->description,
            'shows' => 0,
            'clicks' => 0
        ];

        if($endDate == $today){
            $periodTraffic = $this->getBlockTodayTraffic();
        }

        $beforeTraffic = $this->getBlockBeforeTraffic($startDate, $endDate);

        $periodTraffic['shows'] += $beforeTraffic['shows'];
        $periodTraffic['clicks'] += $beforeTraffic['clicks'];

        return $periodTraffic;
    }

    public function getPeriodData($startDate,$endDate){
        $systemCom = 0.2;
        $webmasterGet = 1 - $systemCom;

        $traffic = $this->getBlockPeriodTraffic($startDate,$endDate);
        $income = round($this->getPeriodIncome($startDate,$endDate),2);
        $advertiserPaid = round($income/$webmasterGet,2);

        return [
            'id' => $this->id,
            'description' => $this->description,
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

        $sql = 'SELECT costs FROM webmaster_stats WHERE block_id = :blockId';
        $params = [':blockId' => $this->id];

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
        return RedisIO::get("block-income:{$this->id}") ? RedisIO::get("block-income:{$this->id}") : 0;
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

    /**
     * @return Sites
     */
    public function getSiteModel()
    {
        if($this->_siteModel === null){
            $this->_siteModel = Sites::model()->findByPk($this->getSiteId());
            if(!$this->_siteModel){
                $sites = Sites::model()->findAll();
                if($sites)
                    $this->_siteModel = end($sites);
                else
                    $this->_siteModel = new Sites();
            }
        }

        return $this->_siteModel;
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
        $this->status = self::STATUS_DISABLED;
        if($this->update(['status']))
            return $this->publisher->unPublish();
        else
            return false;
    }

    public function updateInfoBeforePublish($isSave = true)
    {
        if($this->status === null)
            $this->status = self::STATUS_PUBLISHED;

        $this->allow_shock = $this->getSiteModel()->getAllowShock();
        $this->allow_adult = $this->getSiteModel()->getAllowAdult();
        $this->allow_sms = $this->getSiteModel()->getAllowSms();
        $this->allow_animation = $this->getSiteModel()->getAllowAnimation();

        $cats = [];
        foreach($this->getSiteModel()->getSiteCats() as $cat){
            $cats[] = $cat->id;
        }

        $this->categories = $this->parseDbString($cats);

        if($isSave)
            return $this->update(['status', 'allow_shock', 'allow_adult', 'allow_sms', 'allow_animation', 'categories']);
        else
            return true;
    }
}
