<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 31.07.14
 * Time: 15:20
 */

use application\components\ControllerBase;

class LandingController extends ControllerBase
{
    public function actionLanding2()
    {
        $this->layout = '//layouts/landing2';
        $this->render('landing2');
    }

    public function actionForAdvertisers()
    {
        $this->layout = '//layouts/advertiserLanding';
        $this->render('landingAdv');
    }
}