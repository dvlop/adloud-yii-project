<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 07.10.14
 * Time: 11:18
 */

class UrlManager extends CUrlManager
{
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if(!isset($params['language'])){
            $language = isset($_GET['language']) ? htmlspecialchars($_GET['language']) : Yii::app()->user->getLanguage();
            $params['language'] = $language;
        }

        return parent::createUrl($route, $params, $ampersand);
    }
} 