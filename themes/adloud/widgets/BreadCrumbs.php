<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:24 PM
 * @property ControllerAdvertiser $controller
 */
class BreadCrumbs extends CWidget
{
    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run()
    {
        $this->render('breadCrumbs');
    }
}