<?php
/**
 * Created by PhpStorm.
 * User: JanGolle
 * Date: 25.07.14
 * Time: 17:45
 */

namespace application\modules\advertiser\controllers;

use application\components\ControllerAdvertiser;
use application\models\ReferalStats;
use core\Session;

class ReferalsController extends ControllerAdvertiser
{
    public function actionIndex()
    {
        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/referals/index')] = \Yii::t('advertiser_referals', 'Рефералы');
        $this->pageName = \Yii::t('advertiser_referals', 'Рефералы');

        $model = new ReferalStats();
        $userId = \Yii::app()->user->id;

        $this->render('index', [
            'model' => $model,
            'referals' => $model->findAllByAttributes(['referer_id' => $userId]),
        ]);
    }
}