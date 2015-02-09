<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 31.07.14
 * Time: 17:37
 * @property \application\components\ControllerBase $controller
 */

class BottomMenu  extends CWidget
{
    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run()
    {
        $this->render('bottomMenu', ['menu' => $this->controller->bottomMenu]);
    }
}