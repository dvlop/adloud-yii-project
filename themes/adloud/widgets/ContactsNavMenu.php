<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:24 PM
 * @property \application\components\ControllerBase $controller
 */
class ContactsNavMenu extends CWidget
{
    public $class;
    public $navMenu;

    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run()
    {
        if($this->class === null)
            $this->class = isset($this->controller->contactsNavMenu['class']) ? $this->controller->contactsNavMenu['class']: 'nav-list';
        if($this->navMenu === null)
            $this->navMenu = isset($this->controller->contactsNavMenu['navMenu']) ? $this->controller->contactsNavMenu['navMenu']: 'nav';
        $menu = isset($this->controller->contactsNavMenu['elements']) ? $this->controller->contactsNavMenu['elements']: $this->controller->contactsNavMenu;


        $this->render('contactsNavMenu', [
            'menu' => $menu,
            'navMenu' => $this->navMenu,
            'class' => $this->class,
        ]);
    }
}