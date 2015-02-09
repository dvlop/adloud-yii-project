<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 28.03.14
 * Time: 0:11
 */

/**
 * Class UserInfoModel
 * @property string $avatar
 * @property string $hash
 */
class UserInfoModel extends CFormModel{
    public $id;
    public $fullName;
    public $email;
    public $wmz;
    public $wmr;
    public $yandex;
    public $qiwi;
    public $password;
    public $newPassword;
    public $newPassword2;
    public $_avatar;
    public $isq;
    public $skype;
    public $invite;
    public $image;
    public $cropParams;
    public $lang;

    /**
     * @var \models\User
     */
    public $user;

    private $_languages;

    public function attributeLabels()
    {
        return array(
            'email' => 'Электронная почта',
            'fullName' => 'Полное имя',
            'webmoneyId' => 'Кошелек webmoney',
            'invite' => 'Введите бета ключ',
        );
    }

    public function rules()
    {
        return [
            // username and password are required
            //array('email, fullName', 'required', 'message' => 'Поле {attribute} должно быть заполнено'),
            ['email', 'email'],
            ['fullName', 'length', 'min'=>2, 'max'=>12],
            ['newPassword', 'compare', 'compareAttribute'=>'newPassword2', 'message' => 'Пароли не совпадают!'],
            ['email, fullName, wmz, wmr, yandex, qiwi, isq, skype, password, newPassword, newPassword2, invite, image, cropParams', 'default'],
            ['email, fullName, wmz, wmr, yandex, qiwi, isq, skype, password, newPassword, newPassword2, invite, cropParams, lang', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function getAvatar()
    {
        return '/images'.$this->_avatar;
    }

    public function setAvatar($imageUrl = '')
    {
        $this->_avatar = (string)$imageUrl;
    }

    public function setRole($roleId = null)
    {
        $user = \models\User::getInstance();

        if(!$user->initById($this->id)){
            $this->addError('update', 'Не удалось найти пользователя с ID'.$this->id);
            return false;
        }

        $this->fullName = $user->fullName;
        $this->email = $user->email;

        try{
            if($roleId == \models\User::ACCESS_USER){
                if($invite = $user->activate()){
                    if($this->sendUserCode($invite))
                        \Yii::app()->user->setFlash('success', 'Пользователю на почту отправлено письмо с кодом');
                    return true;
                }else{
                    $this->addError('update', 'Не удалось сохранить данных');
                    return false;
                }
            }elseif($user->setRole($roleId)){
                if($roleId != \models\User::ACCESS_DISABLED){
                    $user->invite = $this->getRandomString();
                }else{
                    $user->invite = '';
                    return $user->update();
                }
            }else{
                $this->addError('update', 'Не удалось изменть статус пользователя с ID '.$this->id);
                return false;
            }
        }catch(Exception $e){
            $this->addError('update', $e->getMessage());
            return false;
        }

        return true;
    }

    public function initUser($userId = null, $email = false)
    {
        if($userId === null){
            $this->user = \Yii::app()->user->model;
        }else{
            try{
                if($email)
                    $this->user = \models\User::getInstance()->initByEmail($userId);
                else
                    $this->user = \models\User::getInstance()->initById($userId);
            }catch(Exception $e){
                $error = 'Пользователь не найден';
                if(YII_DEBUG)
                    $error .= ': '.$e->getMessage();

                $this->addError(null, $error);
            }
        }

        if($this->user === null){
            $this->addError(null, 'Пользователь не найден');
            return false;
        }

        $this->wmz = $this->user->wmz;
        $this->wmr = $this->user->wmr;
        $this->yandex = $this->user->yandex;
        $this->qiwi = $this->user->qiwi;
        $this->email = $this->user->email;
        $this->fullName = $this->user->fullName;
        $this->avatar = $this->user->avatar;
        $this->isq = $this->user->isq;
        $this->skype = $this->user->skype;
        $this->invite = $this->user->invite;
        $this->lang = $this->user->lang;

        return true;
    }

    public function update()
    {
        if($this->password && $this->newPassword && $this->newPassword2){
            if($this->newPassword !== $this->newPassword2){
                $this->addErrors([
                    'newPassword' => 'Пароли должны совпадать',
                    'newPassword2' => 'Пароли должны совпадать',
                ]);

                return false;
            }else{
                $this->user->password = $this->password;
                $this->user->newPassword = $this->newPassword;
            }
        }

        $this->user->fullName = $this->fullName;
        $this->user->email = $this->email;
        $this->user->wmz = $this->wmz;
        $this->user->wmr = $this->wmr;
        $this->user->yandex = $this->yandex;
        $this->user->qiwi = $this->qiwi;
        $this->user->isq = $this->isq;
        $this->user->skype = $this->skype;
        $this->user->lang = $this->lang;

        if($_FILES && isset($_FILES['UserInfoModel']['tmp_name']['image']) && $this->cropParams){
            $tmpFile = $_FILES['UserInfoModel']['tmp_name']['image'];
            $this->cropParams = \CJSON::decode($this->cropParams);
        }else{
            $tmpFile = null;
        }

        try{
            if($tmpFile !== null){
                \core\ImageWorker::resize($tmpFile, $this->cropParams);
                if($avatar = $this->_avatar = \core\ImageWorker::uploadImage($tmpFile)){
                    $this->avatar = $this->user->avatar = str_replace('\\', '/', $avatar['image']);
                }
            }

            $this->cropParams = null;

            return $this->user->update();
        }catch(Exception $e){
            switch($e->getMessage()){
                case 'email is not valid';
                    $this->addError('update', 'Не правильный имейл');
                    break;
                default:
                    $this->addError('update', 'Невозможно обновить данные');
                    break;
            }
            return false;
        }
    }

    public function checkInvite($invite = '')
    {
        if(!$invite)
            $invite = $this->invite;

        if(!$invite)
            return false;

        try{
            if(!$this->user->checkInvite($invite)){
                $this->addError(null, 'Введён неправильный код');
                return false;
            }else{
                return $this->user->setRole(\models\User::ACCESS_USER);
            }
        }catch(Exception $e){
            $this->addError('update', $e->getMessage());
            return false;
        }
    }

    public function checkHash($hash)
    {
        return $hash === $this->getHash();
    }

    public function sendUserCode($invite = null, $email = null, $name = null)
    {
        if($email === null)
            $email = $this->email;

        if($name === null)
            $name = $this->fullName;

        if(!$invite){
            $invite = $this->getRandomString();
        }

        //set message body
        $body = array(
            'userName' => $name,
            'invite' => $invite,
            'supportEmail' => \Yii::app()->params['supportEmail'],
            'supportSkype' => \Yii::app()->params['supportSkype'],
            'systemName' => \Yii::app()->name,
            'contacts' => \Yii::app()->controller->contactsString,
            'backUrl' => \Yii::app()->createAbsoluteUrl(\Yii::app()->user->enterInviteUrl, [
                    'email' => $email,
                    'hash' => $this->getHash(),
                ]),
        );

        //send mail
        \Yii::import('ext.yii-mail.YiiMailMessage');
        $message = new YiiMailMessage;
        $message->subject = 'Код для входа на сайт';
        $message->view = 'setUserInvite';
        $message->setBody($body, 'text/html');
        $message->addTo($email);
        $message->from = \Yii::app()->params['fromMail'];

        if(\Yii::app()->mail->send($message)){
            \Yii::app()->user->setFlash('success', 'Письмо с кодом входа на сайт отослано поользователю на почту');
            return true;
        }else{
            $this->addError(null, 'К сожалению произошла ошибка при попытке отправить почту. Поробуйте позже');
            return false;
        }
    }

    public function getRandomString($length = 15)
    {
        return \models\User::getInstance()->getRandomString($length);
    }

    protected function getHash($email = null, $salt = null)
    {
        if(!$email)
            $email = $this->email;
        if(!$salt)
            $salt = \Yii::app()->params['salt'];

        return hash(\Yii::app()->params['hashAlgo'], $email.$salt);
    }

    /**
     * @return \stdClass[]
     */
    public function getLanguages()
    {
        if($this->_languages === null){
            $this->_languages = [];

            foreach(Yii::app()->user->getLanguages() as $lang => $name){
                $this->_languages[] = (object)[
                    'value' => $lang,
                    'name' => $name,
                    'checked' => $lang == $this->user->lang || $lang == Yii::app()->user->getLanguage(),
                ];
            }
        }

        return $this->_languages;
    }
} 