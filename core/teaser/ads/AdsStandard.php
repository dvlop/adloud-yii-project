<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 31.05.14
 * Time: 14:05
 */

namespace ads;


class AdsStandard extends AdsAbstract {
    public $url;
    public $showUrl;
    public $caption;
    public $description;
    public $buttonText;
    public $imageUrl;
    public $imageFile;
    public $showButton;

    public function type(){
        return \models\Block::FORMAT_ADS_STANDARD;
    }

} 