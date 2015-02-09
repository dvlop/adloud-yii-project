<?php

namespace models;

/**
 * Class BlockParams
 * @package models
 */
class BlockParams
{
    const FORMAT_MAIN = 'main';
    const FORMAT_MARKET = 'market';
    const FORMAT_SIMPLE = 'simple';

    public $description;
    public $format = '160x300';
    public $verticalCount = 1;
    public $horizontalCount = 1;
    public $captionColor = '#34495E';
    public $textColor = '#34495E';
    public $buttonColor = '#f664ff';
    public $backgroundColor = '#f1f1f1';
    public $borderColor = '#f1f1f1';
    public $border = 1;
    public $splitFormat = 'AdsStandard';
    public $captionOpacity = false;
    public $textOpacity = false;
    public $buttonOpacity = false;
    public $backgroundOpacity = false;
    public $borderOpacity = false;
    public $adsBorderOpacity = false;
    public $adsBackOpacity = false;
    public $backHoverOpacity = false;
    public $imgBorderOpacity = false;
    public $captionHoverOpacity = false;
    public $textHoverOpacity = false;

    public $width = '116px';
    public $font = 'Arial, Helvetica, sans-serif';
    public $borderType = 'solid';
    public $indentBorder = 0;
    public $adsBorderColor = '#f1f1f1';
    public $adsBorder = 0;
    public $asdBorderType = 'solid';
    public $adsBackColor = '#f1f1f1';
    public $textPosition = '';
    public $indentAds = 0;
    public $imgBorderWidth = 0;
    public $imgBorderType = 'solid';
    public $imgBorderColor = '#f1f1f1';
    public $imgWidth = '100%';
    public $borderRadius = 0;
    public $captionFontSize = 12;
    public $captionStyle;
    public $descFontSize = 12;
    public $descStyle;
    public $useDescription = false;
    public $backHoverColor = '#f1f1f1';
    public $captionHoverColor = '#f1f1f1';
    public $captionHoverFontSize = 12;
    public $captionHoverStyle;
    public $adsBorderType = 'solid';
    public $alignment = 'left';
    public $descLimit = 30;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getSerialized(){
        return json_encode($this);
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        foreach($params as $name => $value){
            $methodName = 'set'.ucfirst($name);

            if($value === null)
                continue;

            if(method_exists($this, $methodName)){
                $this->$methodName($value);
            }elseif(property_exists($this, $name)){
                $this->$name = $value;
            }

            $this->splitFormat = $this->getSplitFormat();
        }
    }

    public function getSplitFormat()
    {
        if($this->splitFormat == self::FORMAT_MAIN || $this->splitFormat == self::FORMAT_MARKET || $this->splitFormat == self::FORMAT_SIMPLE)
            $this->splitFormat = 'Ads'.ucfirst($this->splitFormat);

        return $this->splitFormat ? $this->splitFormat : 'AdsNewStandard';
    }

    public function checkMarketSimpleFormats()
    {
        $format = strtolower(str_replace('Ads', '', $this->splitFormat));
        return  $format == self::FORMAT_MARKET || $format == self::FORMAT_SIMPLE;
    }

    public function checkMainFormat()
    {
        $format = strtolower(str_replace('Ads', '', $this->splitFormat));
        return $format == self::FORMAT_MAIN;
    }

    public function checkSimpleFormat()
    {
        return strtolower(str_replace('Ads', '', $this->splitFormat)) == self::FORMAT_SIMPLE;
    }
}