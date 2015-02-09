<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.05.14
 * Time: 11:12
 */

class AssetManager extends CAssetManager
{
    public function init()
    {
        parent::init();

        $this->baseUrl = \Yii::app()->theme->baseUrl.'/assets';
        $this->basePath = \Yii::app()->theme->basePath.'/assets';
    }
}