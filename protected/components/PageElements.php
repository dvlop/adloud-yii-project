<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 12.05.14
 * Time: 17:06
 * @property array $all
 */

class PageElements extends CComponent
{
    public function init()
    {

    }

    public function getAll()
    {
        $controllerName = \Yii::app()->controller->id;

       if(Yii::app()->controller->module !== null)
           $controllerName = Yii::app()->controller->module->id.DIRECTORY_SEPARATOR.$controllerName;


        $fileName = \Yii::getPathOfAlias('application.components.pageElements').DIRECTORY_SEPARATOR.$controllerName.'.php';

        if(file_exists($fileName)){
            $elements = require $fileName;
            return $elements;
        }

        return [];
    }
}