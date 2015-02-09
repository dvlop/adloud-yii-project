<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 22.04.14
 * Time: 0:12
 */

namespace core\clickFilter;


class CookieFilter extends FilterAbstract {

    public function filter(){
        if(in_array($this->adsData['id'], $this->viewerSession->getClickedAds())){
            return ClickErrors::DOUBLE_CLICK;
        }
        return ClickErrors::OK;
    }

} 