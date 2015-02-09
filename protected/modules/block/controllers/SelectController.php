<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 20.08.14
 * Time: 15:18
 */

namespace application\modules\block\controllers;

use application\components\ControllerWebmaster;

class SelectController extends ControllerWebmaster
{
    public function actionFormat($siteId)
    {
        $this->layout = '//layouts/simple';
        $this->render('format', ['siteId' => $siteId]);
    }
}