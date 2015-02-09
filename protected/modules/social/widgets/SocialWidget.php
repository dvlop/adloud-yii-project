<?php

Yii::import('application.modules.social.SocialModule');

/**
 * Created by PhpStorm.
 * User: rem
 * Date: 31.07.14
 * Time: 16:46
 * @property array $menu
 */

class SocialWidget extends CWidget
{
    /**
     * @var SocialModule
     */
    protected $module;

    public $socialMenu = [
        'facebook',
        'twitter',
        'google',
        'vk',
    ];

    private static $socialLinksArray = [
        'facebook' => [
            'url' => 'https://www.facebook.com/pages/AdLoud/849905995022900',
            'liClass' => 'pull-left',
            'aClass' => 'adloud-fb',
            'spanClass' => 'fa fa-facebook',
        ],
        'twitter' => [
            'url' => 'https://twitter.com/AdLoudTeam',
            'liClass' => 'pull-left',
            'aClass' => 'adloud-tw',
            'spanClass' => 'fa fa-twitter',
        ],
        'google' => [
            'url' => 'https://plus.google.com/u/2/b/117159854526639225954/117159854526639225954/about',
            'liClass' => 'pull-left',
            'aClass' => 'adloud-gp',
            'spanClass' => 'fa fa-google-plus',
        ],
        'vk' => [
            'url' => 'https://vk.com/adloud',
            'liClass' => 'pull-left',
            'aClass' => 'adloud-vk',
            'spanClass' => 'fa fa-vk',
        ],
    ];

    private $_socialMenu;

    public function init()
    {
        $this->_socialMenu = [];

        if(property_exists($this->controller, 'socialMenu')){
            $this->_socialMenu = $this->controller->socialMenu;
        }else{
            foreach($this->socialMenu as $name){
                if(isset(self::$socialLinksArray[$name]))
                    $this->_socialMenu[$name] = self::$socialLinksArray[$name];
            }
        }
    }

    public function getMenu()
    {
        return $this->_socialMenu;
    }
} 