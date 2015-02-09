<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 21.04.14
 * Time: 23:01
 */

namespace core;


class ViewerSession {

    private static $_instance;

    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function addClickedAds($adsId){
        $_SESSION['_TEASER_VIEWER_CLICKED_ADS'][] = $adsId;
    }

    public function getClickedAds(){
        return isset($_SESSION['_TEASER_VIEWER_CLICKED_ADS']) ? $_SESSION['_TEASER_VIEWER_CLICKED_ADS'] : array();
    }

} 