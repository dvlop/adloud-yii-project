<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:24 PM
 */
class SideMenu extends CWidget
{
    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run()
    {
        $this->render('sideMenu', ['menu' => $this->controller->sideMenu]);
    }
}