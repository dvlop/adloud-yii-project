<?php

Yii::import('application.modules.social.widgets.SocialWidget');

/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:24 PM
 * @property \application\components\ControllerBase $controller
 */
class SocialMenu extends SocialWidget
{
    public $class;
    public $ulClass;


    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $socialMenu = $this->menu;

        if($this->class === null)
            $this->class = isset($socialMenu['class']) ? $socialMenu['class']: 'nav';
        if($this->ulClass === null)
            $this->ulClass = isset($socialMenu['ulClass']) ? $socialMenu['ulClass']: 'menu';
        $menu = isset($socialMenu['elements']) ? $socialMenu['elements']: $socialMenu;

        $this->render('socialMenu', [
            'menu' => $menu,
            'ulClass' => $this->ulClass,
            'class' => $this->class,
        ]);
    }
}