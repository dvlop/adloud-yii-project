<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 31.07.14
 * Time: 10:50
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\Ticket;

class TicketController extends ControllerAdmin {

    public function actionList($status = null)
    {
        $model = new Ticket();
        $model->setStatus($status);

        $attributes = [];
        if($model->status !== Ticket::STATUS_ALL)
            $attributes['status'] = $model->status;

        $this->render('list', [
            'tickets' => $model->findAllByAttributes($attributes, ['order' => 'id desc']),
            'model' => $model,
        ]);
    }

    public function actionCloseTicket($id = null, $status = null)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['status'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => ''
            ];

            $ticket = Ticket::model()->findByPk($id);
            if(!$ticket){
                $json['error'] = 'Не удалось найти тикет ID '.$id;
                echo \CJSON::encode($json);
                \Yii::app()->end();
            }

            $ticket->status = Ticket::STATUS_CLOSED;

            if($ticket->update(['status'])){
                $json['success'] = 'Тикет закрыт';
                if($status == Ticket::STATUS_ALL || $status === '' || $status == $ticket->status)
                    $json['html'] = $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.admin.ticket._ticketTableRow', ['ticket' => $ticket], 1);
                else
                    $json['html'] = 'remove-this';
            }else{
                $json['error'] = $this->parseError($ticket->getErrors(), 'Не удалось найти тикет');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}