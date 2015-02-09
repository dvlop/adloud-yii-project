<?php
namespace ads;
use \config\Config;
abstract class AdsAbstract {

    private $id;
    protected $type;
    protected $size;
    public $url;
    public $showUrl;
    public $clickUrl;
    public $description;
    public $caption;
    public $buttonText;
    public $showButton;
    public $imageUrl;
    public $imageFile;


    private $viewsPath = array(
        'views' => 'views/ads',
    );

    public function __construct(array $content = null){
        if($content){
            foreach($content as $field => $value){
                $this->$field = $value;
            }
        }
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getCss(){
        $mainCss = Config::getInstance()->getBaseUrl() . "css/ads/{$this->type()}/{$this->size}.css";
        return $mainCss;
    }

    public function render(){

        $viewPath = $this->viewsPath['views'] . "/{$this->type()}/{$this->size}.php";


        ob_start();
        require($viewPath);
        $adsContent = ob_get_contents();
        ob_end_clean();
        $result = $adsContent;
        return  $result;
    }

    public function getSerialized(){
        return json_encode($this);
    }

    public function getFavicon(){
        return 'http://www.google.com/s2/favicons?domain=' . $this->showUrl ;
    }

    abstract public function type();


}