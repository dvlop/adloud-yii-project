<?php

namespace application\components;

class ControllerTicket extends ControllerBase
{
    public $layout = '//layouts/ticket';

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('deny',
                'actions'=>array(),
                'users'=>array('?'),
            ),
        );
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $this->breadcrumbs[\Yii::app()->createUrl('ticket/list')] = 'Служба поддержки';
        return true;
    }
}