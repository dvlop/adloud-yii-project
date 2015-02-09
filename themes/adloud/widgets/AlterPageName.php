<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 05.05.14
 * Time: 17:43
 * @property \application\components\ControllerAdvertiser $controller
 */

class AlterPageName extends CWidget
{
    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run()
    {
        $this->render('alterPageName', ['names' => $this->controller->alterPageName]);
    }
}