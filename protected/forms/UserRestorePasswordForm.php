<?php

/**
 * RestorePasswordForm class.
 * RestorePasswordForm is the data structure for keeping
 * user restore password form data. It is used by the 'restorePassword' action of 'SiteController'.
 */
class UserRestorePasswordForm extends CFormModel {

    public $email;

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
        ];
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['email', 'email', 'message' => 'Электронная почта должна быть указана верно'],
            ['email', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function initUserByEmail()
    {
        $user = \models\User::getInstance();
        if(!$user->initByEmail($this->email)){
            $this->addError(null,'Электронная почта не зарегистрирована');
            return false;
        }else{
            return $user;
        }
    }

    public function sendEmail()
    {
        if(!$user = $this->initUserByEmail()){
            $this->addError(null,'Не удалось отправить почту. Пользователь не найден.');
            return false;
        }

        //url for set new password
        $url = \Yii::app()->controller->createAbsoluteUrl('index/restorePassword', [
            'email'     => $this->email,
            'confirm'   => hash(\Yii::app()->params['hashAlgo'], $this->email.\Yii::app()->params['salt']),
        ]);

        //set message body
        $body = array(
            'url'       => $url,
            'userName'  => $user->fullName,
        );

        //send mail
        \Yii::import('ext.yii-mail.YiiMailMessage');
        $message = new YiiMailMessage;
        $message->subject = 'Сслылка на восстановление пароля';
        $message->view = 'restorePassword';
        $message->setBody($body, 'text/html');
        $message->addTo($user->email);
        $message->from = \Yii::app()->params['fromMail'];

        return \Yii::app()->mail->send($message);
    }
}
