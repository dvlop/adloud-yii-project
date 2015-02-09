<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 12.06.14
 * Time: 11:01
 * @property ImageLoader $loader
 */

class ImageClass extends CApplicationComponent
{
    private $_loader;

    public $defaultExt;

    public function init()
    {

    }

    public function getLoader()
    {
        if($this->_loader === null){
            $file = \\Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'teaser'.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'ImageLoader'.DIRECTORY_SEPARATOR.'ImageLoader.php';
            if(file_exists($file)){
                require_once($file);
                $this->_loader = new ImageLoader($this->defaultExt);
            }
        }

        return $this->_loader;
    }
}