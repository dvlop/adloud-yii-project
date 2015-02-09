<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:24 PM
 * @property ControllerBase $controller
 */
class NavMenu extends CWidget
{
    public $class;
    public $navMenu;

    public $isBottom;

    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run()
    {
        if($this->class === null)
            $this->class = isset($this->controller->navMenu['class']) ? $this->controller->navMenu['class']: 'nav';
        if($this->navMenu === null)
            $this->navMenu = isset($this->controller->navMenu['navMenu']) ? $this->controller->navMenu['navMenu']: 'menu';
        $menu = isset($this->controller->navMenu['elements']) ? $this->controller->navMenu['elements']: $this->controller->navMenu;


        $this->render('navMenu', [
            'menu' => $menu,
            'navMenu' => $this->navMenu,
            'class' => $this->class,
            'isBottom' => $this->isBottom
        ]);
    }
}