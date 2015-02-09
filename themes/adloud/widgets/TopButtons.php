<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.05.14
 * Time: 16:02
 * @property ControllerAdvertiser $controller
 */

class TopButtons extends CWidget
{
    public function init()
    {

    }

    public function run()
    {
        $this->render('topButtons', ['buttons' => $this->controller->topButtons]);
    }
} 