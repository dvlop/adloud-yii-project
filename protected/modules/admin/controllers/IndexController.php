<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.07.14
 * Time: 12:40
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;

class IndexController extends ControllerAdmin
{
    public function actionIndex()
    {
        $this->render('index');
    }

} 