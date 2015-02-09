<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 22.08.14
 * Time: 13:34
 */

namespace application\models\entities;

use models\Block;
use models\BlockParams;
use models\BlockRenderer;
use application\models\Categories;

class BlockContentEntity extends AbstractEntity
{
    private static $_borderTypes = [
        'solid' => 'Сплошная',
        'dotted' => 'Точки',
        'dashed' => 'Пунктир',
    ];
    private static $_adsBorderTypes = [
        'solid' => 'Сплошная',
        'dotted' => 'Точки',
        'dashed' => 'Пунктир',
    ];
    private static $_alignments = [
        'left' => 'Слева',
        'center' => 'По центру',
        'right' => 'Справа',
    ];
    private static $_imgBorderTypes = [
        'solid' => 'Сплошная',
        'dotted' => 'Точки',
        'dashed' => 'Пунктир',
    ];
    private static $_fonts = [
        'Arial, Helvetica, sans-serif' => 'Arial',
        'Tahoma, Geneva, sans-serif' => 'Tahoma',
        'Verdana, Geneva, sans-serif' => 'Verdana',
        '\'Trebuchet MS\', Helvetica, sans-serif' => 'Trebuchet MS',
        'inherit' => 'Автоопределение',
    ];
    private static $_textPosition = [
        [
            'name' => 'Вверху',
            'selector' => '.adloud-tsr-item',
            'property' => 'float',
            'value' => 'none',
            'selector2' => '.adld-tsr-title',
            'property2' => 'display',
            'value2' => 'table-header-group',
        ],
        [
            'name' => 'Справа',
            'selector' => '.adloud-tsr-item',
            'property' => 'float',
            'value' => 'left',
            'selector2' => '.adld-tsr-title, .adld-tsr-description',
            'property2' => 'display',
            'value2' => 'block',
        ],
        [
            'name' => 'Снизу',
            'selector' => '.adloud-tsr-item',
            'property' => 'float',
            'value' => 'none',
            'selector2' => '.adld-tsr-title, .adld-tsr-description',
            'property2' => 'display',
            'value2' => 'table-row-group',
        ],
    ];

    private static $_mainFormInputs = [
        'caption',
        'width',
        'horizontalCount',
        'verticalCount',
        'backgroundColor',
        'borderColor',
        'border',
        'borderType',
        'indentAds',
        'indentBorder',
        'adsBorderColor',
        'adsBorder',
        'adsBorderType',
        'adsBackColor',
        'backHoverColor',
        'imgWidth',
        'borderRadius',
        'alignment',
        'imgBorderColor',
        'imgBorderWidth',
        'imgBorderType',
        'font',
        'useDescription',
        'textPosition',
        'captionColor',
        'captionFontSize',
        'captionHoverColor',
        'captionHoverFontSize',
        'textColor',
        'descLimit',
        'descFontSize',
        'borderOpacity',
        'adsBorderOpacity',
        'adsBackOpacity',
        'backHoverOpacity',
        'imgBorderOpacity',
        'captionHoverOpacity',
        'backgroundOpacity',
        'textOpacity',
        'widthStyle',
        'captionStyle',
        'captionHoverStyle',
        'descStyle',
        'captionOpacity',
        'backgroundOpacity',
    ];

    public $id;
    public $description;
    public $format;
    public $verticalCount = 1;
    public $horizontalCount = 1;
    public $captionColor = '#585e5e';
    public $textColor = '#7f8c8d';
    public $buttonColor = '#0088cc';
    public $backgroundColor = '#f1f1f1';
    public $borderColor = '#ffffff';
    public $splitFormat = 'simple';
    public $allowShock = false;
    public $allowAdult = false;
    public $allowSms = false;
    public $allowAnimation = false;
    public $border = 1;
    public $siteId;
    public $categories;
    public $userId;
    public $captionOpacity = true;
    public $textOpacity = true;
    public $buttonOpacity = true;
    public $backgroundOpacity = true;
    public $borderOpacity = true;
    public $adsBorderOpacity = true;
    public $adsBackOpacity = false;
    public $backHoverOpacity = false;
    public $imgBorderOpacity = true;
    public $captionHoverOpacity = true;
    public $borderType = 'solid';
    public $indentAds = 0;
    public $indentBorder = 0;
    public $adsBorderColor = '#f1f1f1';
    public $adsBorder = 0;
    public $adsBorderType = 'solid';
    public $adsBackColor = '#f1f1f1';
    public $backHoverColor = '#f1f1f1';
    public $imgWidth = '100%';
    public $borderRadius = 0;
    public $alignment = 'left';
    public $imgBorderColor = '#f1f1f1';
    public $imgBorderWidth = 0;
    public $imgBorderType = 'solid';
    public $font = 'Arial, Helvetica, sans-serif';
    public $useDescription = true;
    public $textPosition = 'table-row-group';
    public $captionFontSize = 12;
    public $captionStyle;
    public $captionHoverColor = '#000000';
    public $captionHoverFontSize = 12;
    public $captionHoverStyle;
    public $descLimit = 50;
    public $descFontSize = 12;
    public $descStyle;
    public $widthStyle = 'px';
    public $width = '160px';

    private $_bTypes;
    private $_adsBTypes;
    private $_aligs;
    private $_imgBTypes;
    private $_fs;
    private $_textPs;
    private $_params;
    private $_serializedParams;
    private $_serializedNames;

    public function initialise($content = [])
    {
        if( $this->id === null){
            if($this->splitFormat == BlockParams::FORMAT_MARKET){
                $this->captionColor = '#34495e';
                $this->textColor = '#34495e';
                $this->buttonColor = '#f39c12';
                $this->backgroundColor = '#ffffff';
                $this->borderColor = '#f1f1f1';
                $this->border = 0;
            }elseif($this->splitFormat == BlockParams::FORMAT_MAIN){
                $this->captionColor = '#000000';
            }
        }

    }

    /**
     * @param integer|null $id
     * @return array|null
     */
    public function getPreview($id = null)
    {
        $blockData = [];

        $model = Block::getInstance();
        if($id){
            try{
                if($model->initById($id))
                    $blockData = BlockRenderer::getInstance()->newRender($id, $this->getParams(), true);
            }catch(\Exception $e){
                $this->_error = $e->getMessage();
            }
        }

        if(!$blockData){
            $blockParams = [
                'categories' => $this->getCategories(),
                'id' => $id,
                'siteId' => $this->siteId,
                'userId' => $this->userId,
                'allowShock' => $this->allowShock,
                'allowAdult' => $this->allowAdult,
                'allowSms' => $this->allowSms,
                'allowAnimation' => $this->allowAnimation,
            ];

            $blockData = BlockRenderer::getInstance()->newRender($id, $this->getParams(), $blockParams);
        }

        return $blockData ? $blockData : null;
    }

    public function getCategories()
    {
        if($this->categories === null){
            foreach(Categories::model()->findAll() as $cat){
                $this->categories[] = $cat->id;
            }
        }

        return $this->categories;
    }

    public function getParams()
    {
        if($this->_params === null){
            $this->_params = [];
            $notAllowed = [
                '_bTypes',
                '_adsBTypes',
                '_aligs',
                '_imgBTypes',
                '_fs',
                '_textPs',
                '_error',
                '_e',
                '_m',
                '_params',
                '_serializedParams',
                '_serializedNames',
            ];

            foreach($this as $name => $value){
                $continue = false;

                foreach($notAllowed as $key){
                    if(strpos($name, $key) !== false){
                        $continue = true;
                        unset($notAllowed[$key]);
                        continue;
                    }
                }

                if($continue)
                    continue;

                if(strripos($name, 'color') !== false && strpos($value, '#') === false && strpos($value, 'rgb') === false)
                    $value = '#'.$value;

                $this->_params[$name] = $value;
            }
        }

        return $this->_params;
    }

    public function setCaptionColor($color)
    {
        $this->setColorValue('captionColor', $color);
    }

    public function setTextColor($color)
    {
        $this->setColorValue('textColor', $color);
    }

    public function setButtonColor($color)
    {
        $this->setColorValue('buttonColor', $color);
    }

    public function setBackgroundColor($color)
    {
        $this->setColorValue('backgroundColor', $color);
    }

    public function setBorderColor($color)
    {
        $this->setColorValue('borderColor', $color);
    }

    public function setAdsBorderColor($color)
    {
        $this->setColorValue('adsBorderColor', $color);
    }

    public function setAdsBackColor($color)
    {
        $this->setColorValue('adsBackColor', $color);
    }

    public function setBackHoverColor($color)
    {
        $this->setColorValue('backHoverColor', $color);
    }

    public function setImgBorderColor($color)
    {
        $this->setColorValue('imgBorderColor', $color);
    }

    public function setCaptionHoverColor($color)
    {
        $this->setColorValue('captionHoverColor', $color);
    }

    public function setCaptionOpacity($val)
    {
        $this->setOpacityValue('captionOpacity', $val);
    }

    public function getCaptionOpacity()
    {
        return $this->getOpacityValue('captionOpacity');
    }

    public function setTextOpacity($val)
    {
        $this->setOpacityValue('textOpacity', $val);
    }

    public function getTextOpacity()
    {
        return $this->getOpacityValue('textOpacity');
    }

    public function setButtonOpacity($val)
    {
        $this->setOpacityValue('buttonOpacity', $val);
    }

    public function getButtonOpacity()
    {
        return $this->getOpacityValue('buttonOpacity');
    }

    public function setBackgroundOpacity($val)
    {
        $this->setOpacityValue('backgroundOpacity', $val);
    }

    public function getBackgroundOpacity()
    {
        return $this->getOpacityValue('backgroundOpacity');
    }

    public function setBorderOpacity($val)
    {
        $this->setOpacityValue('borderOpacity', $val);
    }

    public function getBorderOpacity()
    {
        return $this->getOpacityValue('borderOpacity');
    }

    public function setAdsBorderOpacity($val)
    {
        $this->setOpacityValue('adsBorderOpacity', $val);
    }

    public function getAdsBorderOpacity()
    {
        return $this->getOpacityValue('adsBorderOpacity');
    }

    public function setAdsBackOpacity($val)
    {
        $this->setOpacityValue('adsBackOpacity', $val);
    }

    public function getAdsBackOpacity()
    {
        return $this->getOpacityValue('adsBackOpacity');
    }

    public function setBackHoverOpacity($val)
    {
        $this->setOpacityValue('backHoverOpacity', $val);
    }

    public function getBackHoverOpacity()
    {
        return $this->getOpacityValue('backHoverOpacity');
    }

    public function setImgBorderOpacity($val)
    {
        $this->setOpacityValue('imgBorderOpacity', $val);
    }

    public function getImgBorderOpacity()
    {
        return $this->getOpacityValue('imgBorderOpacity');
    }

    public function setCaptionHoverOpacity($val)
    {
        $this->setOpacityValue('captionHoverOpacity', $val);
    }

    public function getCaptionHoverOpacity()
    {
        return $this->getOpacityValue('captionHoverOpacity');
    }

    public function setWidth($width)
    {
        $width = (string)$width;

        if($width){
            $width = intval($width);
            $this->width = $width.$this->widthStyle;
        }
    }

    public function setWidthStyle($style)
    {
        $style = (string)$style;

        if($style){
            $this->widthStyle = $style;
            $this->width = intval($this->width).$style;
        }
    }

    public function setBorderType($type)
    {
        $type = (string)$type;

        if(isset(self::$_borderTypes[$type]))
            $this->borderType = $type;
    }

    /**
     * @return \stdClass[]
     */
    public function getBorderTypes()
    {
        if($this->_bTypes === null){
            $this->_bTypes = [];

            foreach(self::$_borderTypes as $id => $name){
                $this->_bTypes[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'checked' => $id == $this->borderType,
                ];
            }
        }

        return $this->_bTypes;
    }

    public function setAdsBorderType($type)
    {
        $type = (string)$type;

        if(isset(self::$_adsBorderTypes[$type]))
            $this->adsBorderType = $type;
    }

    /**
     * @return \stdClass[]
     */
    public function getAdsBorderTypes()
    {
        if($this->_adsBTypes === null){
            $this->_adsBTypes = [];

            foreach(self::$_adsBorderTypes as $id => $name){
                $this->_adsBTypes[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'checked' => $id == $this->adsBorderType,
                ];
            }
        }

        return $this->_adsBTypes;
    }

    public function setAlignment($alignment)
    {
        $alignment = (string)$alignment;

        if(isset(self::$_alignments[$alignment]))
            $this->alignment = $alignment;
    }

    /**
     * @return \stdClass[]
     */
    public function getAlignments()
    {
        if($this->_aligs === null){
            $this->_aligs = [];

            foreach(self::$_alignments as $id => $name){
                $this->_aligs[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'checked' => $id == $this->alignment,
                ];
            }
        }

        return $this->_aligs;
    }

    public function setImgBorderType($imgBorderType)
    {
        $imgBorderType = (string)$imgBorderType;

        if(isset(self::$_imgBorderTypes[$imgBorderType]))
            $this->imgBorderType = $imgBorderType;
    }

    /**
     * @return \stdClass[]
     */
    public function getImgBorderTypes()
    {
        if($this->_imgBTypes === null){
            $this->_imgBTypes = [];

            foreach(self::$_imgBorderTypes as $id => $name){
                $this->_imgBTypes[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'checked' => $id == $this->imgBorderType,
                ];
            }
        }

        return $this->_imgBTypes;
    }

    public function setFont($font)
    {
        $font = (string)$font;

        if(isset(self::$_fonts[$font]))
            $this->font = $font;
    }

    /**
     * @return \stdClass[]
     */
    public function getFonts()
    {
        if($this->_fs === null){
            $this->_fs = [];

            foreach(self::$_fonts as $id => $name){
                $this->_fs[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'checked' => $id == $this->font,
                ];
            }
        }

        return $this->_fs;
    }

    public function setTextPosition($textPosition)
    {
        $this->textPosition = (string)$textPosition;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }

    /**
     * @param bool $default
     * @return array
     */
    public function getTextPositions($default = false)
    {
        if($this->_textPs === null){
            $this->_textPs = [];
            $lastNum = count(self::$_textPosition) - 1;

            foreach(self::$_textPosition as $j => $pos){
                if(!$default){
                    $pos['checked'] = $res = strpos($this->textPosition, $pos['value2']) !== false;
                }else{
                    $pos['checked'] = $lastNum == $j;
                }

                $this->_textPs[] = (object)$pos;
            }
        }

        return $this->_textPs;
    }

    public function setUseDescription($desc)
    {
        $this->setOpacityValue('useDescription', $desc);
        $this->useDescription = !$this->useDescription;
    }

    /**
     * @return string
     */
    public function getSerializedMainParams()
    {
        if($this->_serializedParams === null){
            $this->_serializedParams = '';
            $serializedParams = [];

            foreach(self::$_mainFormInputs as $param){
                $method = 'get'.ucfirst($param);
                if(method_exists($this, $param))
                    $serializedParams[$param] = $this->$method();
                elseif(property_exists($this, $param))
                    $serializedParams[$param] = $this->$param;
            }

            if($serializedParams){
                $this->_serializedParams = \CJSON::encode($serializedParams);
                $this->_serializedParams = str_replace('{', '', $this->_serializedParams);
                $this->_serializedParams = str_replace('}', '', $this->_serializedParams);
            }
        }

        return $this->_serializedParams;
    }

    /**
     * @param string $modelsName
     * @return string
     */
    public function getSerializedMainFormIds($modelsName)
    {
        if($this->_serializedNames === null){
            $this->_serializedNames = '';
            $serializedNames = [];

            foreach(self::$_mainFormInputs as $param){
                $serializedNames[$param] = '#'.$modelsName.'_'.$param;
            }

            if($serializedNames){
                $this->_serializedNames = \CJSON::encode($serializedNames);
                $this->_serializedNames = str_replace('{', '', $this->_serializedNames);
                $this->_serializedNames = str_replace('}', '', $this->_serializedNames);
            }
        }

        return $this->_serializedNames;
    }

    private function setOpacityValue($attr, $val)
    {
        $val = (string)$val;
        $this->$attr = (int)($val === '' || $val === 'false' || $val === '0');
    }

    private function getOpacityValue($attr)
    {
        $attr = (bool)$this->$attr;
        return !$attr;
    }

    private function setColorValue($attr, $color)
    {
        if(strpos($color, '#') === false && strpos($color, 'rgb') === false)
            $color = '#'.$color;

        $this->$attr = htmlspecialchars($color);
    }
}