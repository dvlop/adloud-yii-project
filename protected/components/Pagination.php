<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 05.05.14
 * Time: 16:56
 */

class Pagination extends CPagination
{
    public $filters = [];

    public function createPageUrl($controller,$page)
    {
        $params=$this->params===null ? $_GET : $this->params;

        if($page>0) // page 0 is the default
            $params[$this->pageVar]=$page+1;
        else
            unset($params[$this->pageVar]);
        return $controller->createUrl($this->route,$params);
    }
}