<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 26.07.14
 * Time: 15:33
 */

namespace core\clickFilter;


use core\ViewerSession;

class RefererFilter extends FilterAbstract {

    public function filter(){
        if(!isset($this->blockData['siteUrl'])){
            return ClickErrors::OK;
        }

        if(empty($_SERVER['HTTP_REFERER'])){
            return ClickErrors::WRONG_REFERER;
        }

        $referer = explode('/', $_SERVER['HTTP_REFERER']);
        if(!isset($referer[2])){
            return ClickErrors::WRONG_REFERER;
        }
        $referer = $referer[2];

        $site = explode('/', $this->blockData['siteUrl']);

        if(!isset($site[2])){
            return ClickErrors::WRONG_REFERER;
        }
        $site = $site[2];

        if($site != $referer){
            return ClickErrors::WRONG_REFERER;
        }

        return ClickErrors::OK;

    }

} 