<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 30.07.14
 * Time: 12:26
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\ticket\controllers;

use application\components\ControllerTicket;
use application\models\Message;
use application\models\Ticket;
use application\models\TicketCategory;

class IndexController  extends ControllerTicket
{
    public function actionIndex($id = null)
    {
        $ticket = Ticket::model()->findByPk($id);

        if($ticket->user_id != \Yii::app()->user->id)
            $this->redirect($this->redirect(\Yii::app()->createUrl('ticket/index/index')));

        $this->breadcrumbs[\Yii::app()->createUrl('ticket/index/index', ['id' => $id])] = $ticket->name;
        $messages = Message::model()->findAllByAttributes([
           'ticket_id' => $id
        ], [
            'order' => 'date asc'
        ]);

        $admin_last = Message::model()->findAllByAttributes([
            'ticket_id' => $id,
            'is_admin' => true,
            'status' => 1
        ], [
            'order' => 'id desc'
        ]);

        foreach($admin_last as $mes){
            Message::model()->updateByPk($mes['id'],[
                'status' => 0
            ]);
        }

        $avatar = \Yii::app()->user->getAvatar();
        $theme = \Yii::app()->theme->baseUrl;

        $this->render('index',[
            'ticket' => $ticket,
            'messages' => $messages,
            'avatar' => $avatar,
            'theme' => $theme,
            'isAdmin' => false
        ]);
    }

    public function actionAdmin($id = null)
    {
        if(!\Yii::app()->user->isAdmin){
            $this->redirect('/ticket');
        }

        $ticket = Ticket::model()->findByPk($id);

        $this->breadcrumbs[\Yii::app()->createUrl('ticket/index/index', ['id' => $id])] = $ticket->name;
        $messages = Message::model()->findAllByAttributes([
            'ticket_id' => $id
        ], [
            'order' => 'date asc'
        ]);

        $user_last = Message::model()->findAllByAttributes([
            'ticket_id' => $id,
            'is_admin' => false,
            'status' => 1
        ], [
            'order' => 'id desc'
        ]);

        foreach($user_last as $mes){
            Message::model()->updateByPk($mes['id'],[
                'status' => 0
            ]);
        }

        $theme = \Yii::app()->theme->baseUrl;

        $this->render('index',[
            'ticket' => $ticket,
            'messages' => $messages,
            'avatar' => \Yii::app()->user->avatar,
            'theme' => $theme,
            'isAdmin' => true
        ]);
    }

    public function actionList()
    {
        $attributes = [
            'user_id' => \Yii::app()->user->getId()
        ];

        $ticketList = Ticket::model()->findAllByAttributes($attributes, ['order' => 'date asc']);

        $categoryList = TicketCategory::model()->findAll([]);

        $this->render('list',[
            'ticketList' => $ticketList,
            'categoryList' => $categoryList
        ]);
    }

    public function actionCreate()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);

        if(\Yii::app()->request->isAjaxRequest && isset($_POST['category'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => ''
            ];

            $userId = \Yii::app()->user->id;

            $model = new Ticket();
            $model->name = $_POST['name'];
            $model->category_id = $_POST['category'];
            $model->user_id = $userId;
            $model->status = 1;

            if(!$model->save()){
                $json['error'] = $this->parseError($model->errors);
            }

            $message = new Message();
            $message->content = $_POST['message'];
            $message->ticket_id = intval($model->id);
            $message->user_id = $userId;
            $message->status = 1;
            $message->is_admin = false;

            if(!$message->save()){
                $json['error'] = $this->parseError($message->errors);
            }

            if(!$json['error']){
                $body = [];
                $body['date'] = $model->date;
                $body['userId'] = \Yii::app()->user->id;
                $body['userName'] = \Yii::app()->user->name;
                $body['text'] = $message->content;
                $body['title'] = $model->name;
                $body['url'] = \Yii::app()->createAbsoluteUrl('ticket/index/index', ['id' => $model->id]);

                //send mail
                \Yii::import('ext.yii-mail.YiiMailMessage');
                $message = new \YiiMailMessage;
                $message->subject = 'Новый тикет!';
                $message->view = 'ticket';
                $message->setBody(['data' => $body], 'text/html');
                $message->addTo(\Yii::app()->params['adloudSupportEmail']);
                $message->from = \Yii::app()->params['supportEmail'];

                \Yii::app()->mail->send($message);
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionSaveImage()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['img'])){
            echo json_encode(['image' => $this->base64_to_jpeg($_POST['img'])]);
        }

        \Yii::app()->end();
    }

    public function actionAnswer()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['message'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => ''
            ];

            $content = $_POST['message'];
//            $this->replaceTempImage($content);

            $today = new \DateTime();
            $today = $today->format(\Yii::app()->params['dateTimeFormat']);
            $userId = \Yii::app()->user->id;

            $message = new Message();
            $message->content = $content;
            $message->date = $today;
            $message->ticket_id = intval($_POST['ticket_id']);
            $message->user_id = $userId;
            $message->status = 1;
            $message->is_admin = false;

            if(!$message->save()){
                $json['error'] = $this->parseError($message->errors);
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionAdminAnswer()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['message'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => ''
            ];

            $content = $_POST['message'];
//            $this->replaceTempImage($content);

            $today = new \DateTime();
            $today = $today->format(\Yii::app()->params['dateTimeFormat']);
            $userId = \Yii::app()->user->id;

            $message = new Message();
            $message->content = $content;
            $message->date = $today;
            $message->ticket_id = intval($_POST['ticket_id']);
            $message->user_id = $userId;
            $message->status = 1;
            $message->is_admin = true;

            if(!$message->save()){
                $json['error'] = $this->parseError($message->errors);
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    private function base64_to_jpeg($base64_string) {
        $output_file = uniqid();

        $data = explode(',', $base64_string);
        if(strpos($data[0],'data:image/' === false)){
            return false;
        }
        if(strpos($data[0],'jpeg')){
            $output_file .= '.jpg';
        }elseif(strpos($data[0],'png')){
            $output_file .= '.png';
        }

//        $path = 'images/tickets/temp/'.$output_file;
        $path = 'images/tickets/'.$output_file;
        $ifp = fopen($path, "wb");

        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);

        return '/'.$path;
    }

    private function replaceTempImage(&$html){
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;

        $images = $dom->getElementsByTagName('img');

        foreach($images as $img){
            $oldPath = substr($img->getAttribute('src'),1);
            $path_parts = explode('/',$oldPath);
            $new_path = [];
            foreach($path_parts as $key => $part){
                $prelast = count($path_parts) - 2;
                $last = $prelast + 1;

                if($key == $last){
                    continue;
                } elseif($key == $prelast){
                    $new_path[$key] = $path_parts[$last];
                    continue;
                }
                $new_path[$key] = $path_parts[$key];
            }
            $new_path = implode('/',$new_path);
//            rename($oldPath,$new_path);
        }

//        $html = str_replace('temp/','',$html);
    }
}