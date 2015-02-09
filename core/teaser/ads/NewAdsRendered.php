<?php

namespace ads;

use config\Config;
use models\BlockParams;

/**
 * Created by PhpStorm.
 * User: rem
 * Date: 24.07.14
 * Time: 19:12
 */
class NewAdsRendered
{
    /**
     * @var BlockParams
     */
    private $blockParams;

    /**
     * @var AdsAbstract[]
     */
    private $ads;

    /**
     * @var int
     */
    private $blockId;

    /**
     * @var string[]
     */
    private $css;

    /**
     * @var string
     */
    private $gridPath = 'newView/grid/';

    /**
     * @var string
     */
    private $adsPath = 'newView/ads/';

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->blockId = $params['blockId'];
        $this->blockParams = new BlockParams($params);

        $splitFormat = $this->blockParams->getSplitFormat();
        $size = $this->blockParams->format;

        if($this->blockParams->checkMainFormat()){
            $this->css[] = Config::getInstance()->getBaseUrl()."newCss/ads/{$splitFormat}/default.css";
        }elseif($this->blockParams->checkMarketSimpleFormats()){
            $this->css[] = Config::getInstance()->getBaseUrl()."newCss/ads/{$splitFormat}/default.css";
            $this->css[] = Config::getInstance()->getBaseUrl()."newCss/ads/{$splitFormat}/{$size}.css";
            $this->css[] = Config::getInstance()->getBaseUrl()."newCss/ads/{$splitFormat}/custom.css";
        }else{
            $this->css[] = Config::getInstance()->getBaseUrl()."newCss/default.css";
            $this->css[] = Config::getInstance()->getBaseUrl()."newCss/ads/{$splitFormat}/{$size}.css";
            $this->css[] = Config::getInstance()->getBaseUrl()."newCss/standart.css";
        }
    }

    /**
     * @param AdsAbstract $ads
     */
    public function addAds(AdsAbstract $ads)
    {
        $ads->setSize($this->blockParams->format);
        $ads->showUrl = $this->getSiteAddress($ads->showUrl);
        $ads->clickUrl = Config::getInstance()->getBaseUrl()."click.php?id={$ads->getId()}&type={$this->blockParams->splitFormat}_{$this->blockParams->format}&block={$this->blockId}&url=".base64_encode($ads->url);

        $this->ads[] = $ads;
    }

    /**
     * @return string[]
     */
    public function getStyles()
    {
        return $this->css;
    }

    /**
     * @return string
     */
    public function render()
    {
        $viewPath = __DIR__.'/'.$this->gridPath.$this->blockParams->splitFormat.'.php';

        $adsContent = [];

        foreach($this->ads as $ad){
            $adsContent[] = [
                'clickUrl' => $ad->clickUrl,
                'html' => $this->renderAds($ad)
            ];
        }

        $tmpArr = explode('x', $this->blockParams->format);


        $border = (int)$this->blockParams->border;
        $width = isset($tmpArr[0]) ? $tmpArr[0] : 300;
        $height = isset($tmpArr[1]) ? $tmpArr[1] : 150;
        $hCount = $this->blockParams->horizontalCount;
        $vCount =  $this->blockParams->verticalCount;

        if($this->blockParams->checkSimpleFormat() && $border > 0){
            $width = $width*$hCount + 2*$border*$hCount;
        }elseif($this->blockParams->checkMainFormat()){
            $width = $this->blockParams->width;
        }else{
            $width = $width*$hCount;
        }

        return $this->renderFile($viewPath, [
            'ads' => $adsContent,
            'width' => $width,
            'height' => $height,
            'hCount' => $hCount,
            'vCount' => $vCount,
            'border' => $border,
            'borderColor' => $this->hex2rgba($this->blockParams->borderColor, $this->blockParams->borderOpacity),
            'backgroundColor' => $this->hex2rgba($this->blockParams->backgroundColor, $this->blockParams->backgroundOpacity),
            'size' => $this->blockParams->format,
            'blockWidth' => $this->blockParams->width,
            'font' => $this->blockParams->font,
            'borderStyle' => $this->blockParams->borderType,
            'backHoverColor' => $this->hex2rgba($this->blockParams->backHoverColor, $this->blockParams->backHoverOpacity),
            'captionHoverColor' => $this->hex2rgba($this->blockParams->captionHoverColor, $this->blockParams->captionHoverOpacity),
            'captionHoverFontSize' => $this->blockParams->captionHoverFontSize,
            'captionHoverStyleB' => strpos($this->blockParams->captionHoverStyle, 'B') !== false,
            'captionHoverStyleU' => strpos($this->blockParams->captionHoverStyle, 'U') !== false,
            'indentAds' => $this->blockParams->indentAds,
            'adsBorder' => $this->blockParams->adsBorder,
            'adsBorderType' => $this->blockParams->adsBorderType,
            'adsBorderColor' => $this->hex2rgba($this->blockParams->adsBorderColor, $this->blockParams->adsBorderOpacity),
            'adsBackColor' => $this->hex2rgba($this->blockParams->adsBackColor, $this->blockParams->adsBackOpacity),
            'textPosition' => $this->blockParams->textPosition,
            'indentBorder' => $this->blockParams->indentBorder,
            'imgBorderWidth' => $this->blockParams->imgBorderWidth,
            'imgBorderType' => $this->blockParams->imgBorderType,
            'imgBorderColor' => $this->hex2rgba($this->blockParams->imgBorderColor, $this->blockParams->imgBorderOpacity),
            'imgWidth' => $this->blockParams->imgWidth,
            'borderRadius' => $this->blockParams->borderRadius,
            'captionColor' => $this->hex2rgba($this->blockParams->captionColor, $this->blockParams->captionOpacity),
            'captionFontSize' => $this->blockParams->captionFontSize,
            'captionStyleB' => strpos($this->blockParams->captionStyle, 'B') !== false,
            'captionStyleU' => strpos($this->blockParams->captionStyle, 'U') !== false,
            'descStyleB' => strpos($this->blockParams->descStyle, 'B') !== false,
            'descStyleU' => strpos($this->blockParams->descStyle, 'U') !== false,
            'textColor' => $this->hex2rgba($this->blockParams->textColor, $this->blockParams->textOpacity),
            'descFontSize' => $this->blockParams->descFontSize,
            'alignment' => $this->blockParams->alignment,
            'useDescription' => (bool)$this->blockParams->useDescription,
            'horizontalCount' => intval($this->blockParams->horizontalCount),
        ]);
    }

    /**
     * @param string $fullUrl
     * @return string
     */
    private function getSiteAddress($fullUrl)
    {
        $temp = explode('/', $fullUrl);

        if(isset($temp[2]))
            return $temp[2];
        else
            return $fullUrl;
    }

    /**
     * @param AdsAbstract $ads
     * @return string
     */
    private function renderAds($ads)
    {
        if($this->blockParams->checkMainFormat())
            $viewPath = __DIR__.'/'.$this->adsPath.$this->blockParams->splitFormat.'/main.php';
        else
            $viewPath = __DIR__.'/'.$this->adsPath.$this->blockParams->splitFormat.'/'.$this->blockParams->format.'.php';

        $limit = intval($this->blockParams->descLimit);
        $limitedDesc = $limitedDesc = $limit ? mb_substr($ads->description, 0, $limit, 'utf-8') : $ads->description;

        return $this->renderFile($viewPath, [
            'ads' => $ads,
            'size' => $this->blockParams->format,
		    'textColor' => $this->hex2rgba($this->blockParams->textColor, $this->blockParams->textOpacity),
		    'buttonColor' => $this->hex2rgba($this->blockParams->buttonColor, $this->blockParams->buttonOpacity),
            'backgroundColor' => $this->hex2rgba($this->blockParams->backgroundColor, $this->blockParams->backgroundOpacity),
            'captionColor' => $this->hex2rgba($this->blockParams->captionColor, $this->blockParams->captionOpacity),
            'asdBorderType' => $this->blockParams->asdBorderType,
            'width' => $this->blockParams->width,
            'limitedText' => $limitedDesc,
        ]);
    }

    /**
     * @param $viewPath
     * @param array $params
     * @return bool|string
     */
    private function renderFile($viewPath, $params = [])
    {
        if(!file_exists($viewPath))
            return false;

        ob_start();
        foreach($params as $name=>$value){
            $$name = $value;
        }
        require($viewPath);
        $adsContent = ob_get_contents();
        ob_end_clean();

        return $adsContent;
    }

    private function hex2rgba($color, $opacity = false)
    {
        $default = 'rgb(0,0,0)';
        $opacity = (int)$opacity;

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        if(abs($opacity) > 1)
            $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';

        //Return rgb(a) color string
        return $output;
    }

} 