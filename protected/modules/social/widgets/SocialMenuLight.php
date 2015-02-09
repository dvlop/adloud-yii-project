<?php

Yii::import('application.modules.social.widgets.SocialWidget');

/**
 * Created by PhpStorm.
 * User: rem
 * Date: 31.07.14
 * Time: 16:43
 * @property \application\components\ControllerBase $controller
 */
class SocialMenuLight extends SocialWidget
{
    public $container = 'li';
    public $class = 'footer-nav-item footer-social-links';
    public $aClass = 'footer-nav-link';

    private static $_classes = [
        'facebook' => [
            'liClass' => 'fa fa-facebook',
            'aClass' => 'footer-nav-link fb-link',
        ],
        'twitter' => [
            'liClass' => 'fa fa-twitter',
            'aClass' => 'footer-nav-link tw-link',
        ],
        'google' => [
            'liClass' => 'fa fa-google-plus',
            'aClass' => 'footer-nav-link gp-link',
        ],
        'vk' => [
            'liClass' => 'fa fa-vk',
            'aClass' => 'footer-nav-link vk-link',
        ],
    ];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $menu = $this->menu;

        foreach(self::$_classes as $name=>$class){
            if(isset($menu[$name])){
                $menu[$name] = array_merge($menu[$name], [
                    'aClass' => $class['aClass'],
                    'liClass' => $class['liClass'],
                ]);
            }
        }

        $this->render('socialMenuLight', [
            'menu' => $menu,
            'container' => $this->container,
            'class' => $this->class,
            'aClass' => $this->aClass,
        ]);
    }
} 