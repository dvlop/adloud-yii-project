<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 25.09.14
 * Time: 10:34
 */

namespace application\modules\ticket\controllers;

use application\components\ControllerTicket;
use application\models\Ticket;

class AdminController extends ControllerTicket
{
    public function actionTouser()
    {
        if(\Yii::app()->request->isPostRequest && isset($_POST['ticket'])){
            $model = new Ticket();

            if(isset($_POST['ticket']['redirectUrl'])){
                $redirectUrl = $_POST['ticket']['redirectUrl'];
                unset($_POST['ticket']['redirectUrl']);
            }else{
                $redirectUrl = \Yii::app()->createUrl('admin/user/list');
            }

            $model->setAttributes($_POST['ticket']);

            if($model->save())
                \Yii::app()->user->setFlash('success', 'Сообщение отправлено пользователю');
            else
                $this->setFlash($model->errors, 'Не удалось отправить пользоватлю сообщение');

            if($model->hasErrors())
                \Yii::app()->test->show($model->errors);

            $this->redirect($redirectUrl);
        }
    }
} 