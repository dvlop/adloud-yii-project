<?php

namespace application\modules\webmaster\controllers;

use application\components\ControllerWebmaster;
use application\models\ReferalStats;

class ReferalsController extends ControllerWebmaster
{
    public function actionIndex()
    {
        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/referals/index')] = \Yii::t('webmaster_referals', 'Рефералы');
        $this->pageName = \Yii::t('webmaster_referals', 'Рефералы');

        $model = new ReferalStats();
        $userId = \Yii::app()->user->id;

        $this->render('index', [
            'model' => $model,
            'referals' => $model->findAllByAttributes(['referer_id' => $userId]),
        ]);
    }
}