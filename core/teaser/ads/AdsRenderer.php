<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 31.05.14
 * Time: 14:04
 */

namespace ads;
use config\Config;
use models\Block;

/**
 * Class TemplateManager
 * @package ads
 */
class AdsRenderer {

    private $type;
    private $format;
    private $colorScheme;
    private $fillScheme;
    private $styles;
    private $blockId;
    private $adsCss;
    /**
     * @var AdsAbstract[]
     */
    public $ads;

    private $viewPath = 'views/grids/';


    public function __construct($format, $type, $colorScheme, $fillScheme, $blockId){
        $this->type = $type;
        $this->colorScheme = $colorScheme;
        $this->fillScheme = $fillScheme;
        $this->blockId = $blockId;
        $this->format = $format;
        $defaultCss = Config::getInstance()->getBaseUrl() . "css/default.css";
        $colorThemeCss = Config::getInstance()->getBaseUrl() . "css/styleSchemes/colorSchemes/{$this->colorScheme}-colors.css";
        $gridCss = Config::getInstance()->getBaseUrl() . "css/grids/{$format}/{$this->type}.css";
        $fillThemeCss = Config::getInstance()->getBaseUrl() . "css/styleSchemes/background/{$this->fillScheme}-bg.css";
        $this->styles = [$defaultCss,$gridCss,$colorThemeCss,$fillThemeCss];
    }

    public function addAds(AdsAbstract $ads){
        $ads->setSize($this->type);
        $ads->showUrl = $this->getSiteAddress($ads->showUrl);
        $ads->clickUrl = Config::getInstance()->getBaseUrl() .
            "click.php?id={$ads->getId()}&type={$this->format}_{$this->type}&block={$this->blockId}&url=".base64_encode($ads->url);
        $this->ads[] = $ads;
        $this->adsCss[get_class($ads)] = $ads->getCss();
    }

    public function getStyles(){
        return array_merge($this->styles, array_values($this->adsCss), [Config::getInstance()->getBaseUrl() . "css/load-check.css"]);
    }

    private function getSiteAddress($fullUrl){
        $temp = explode('/', $fullUrl);
        if(isset($temp[2])){
            return $temp[2];
        }
        return $fullUrl;
    }

    public function render(){

        ob_start();
        $viewPath = $this->viewPath . "{$this->format}/{$this->type}.php";
        require($viewPath);
        $adsContent = ob_get_contents();
        ob_end_clean();
        $baseUrl = Config::getInstance()->getBaseUrl();
        return $adsContent;
    }





} 