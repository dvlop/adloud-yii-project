<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserRegistrationForm extends CFormModel {

    public $_identity;

    public $email;
    public $fullName;
    public $password;
    public $password2;
    public $agree;

    public function attributeLabels()
    {
        return array(
            'fullName' => 'Ваше имя',
            'email' => 'Введите ваш e-mail адрес',
            'password' => 'Придумайте пароль',
            'password2' => 'Повторите пароль',
            'agree' => 'Я прочитал и принимаю <a href="#" class="color-green">Правила пользования</a>',
        );
    }
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            ['email, password', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['email', 'email'],
            ['email', 'checkUniqueEmail'],
            ['fullName', 'default'],
            //['fullName', 'length', 'min'=>2, 'max'=>12],
            //['password', 'compare', 'compareAttribute'=>'password2', 'message' => 'Пароль и повторный пароль должны совпадать'],
            ['email, fullName, password, password2', 'filter', 'filter' => 'htmlspecialchars'],
        );
    }

    public function initUserByEmail()
    {
        $user = \models\User::getInstance();
        if(!$user->initByEmail($this->email)){
            $this->addError(null,'Не найден пользователь');
            return false;
        }else{
            return $user;
        }
    }

    public function checkUniqueEmail()
    {
        $user = \models\User::getInstance();

        if($user->initByEmail($this->email)){
            $this->addError('email', 'Такая электронная почта уже зарегистрирована');
        }
    }

    public function registration()
    {
        $user = \models\User::getInstance();

        $user->email = $this->email;
        $user->password = $this->password;

        if(isset($_COOKIE['referer']) && $_COOKIE['referer'])
            $user->referer = $_COOKIE['referer'];

        if($this->fullName){
            $user->fullName = $this->fullName;
        }else{
            $name = explode('@', $this->email);

            if(!empty($name))
                $user->fullName = array_shift($name);
        }

        try{
            if($result = $user->save()){
                $name = explode(' ', $user->fullName);
                $params = [
                    'email' => $user->email,
                    'firstName' => $name[0],
                ];

                if(isset($name[1]) && $name[1])
                    $params['lastName'] = $name[1];

                $result = $this->sendEmail($user);
                \Yii::app()->mailchimp->addUser($params);
            }
            return $result;
        }catch(Exception $e){
            $error = YII_DEBUG ? $e->getMessage() : 'Возникла ошибка при регистрации. Попробуйте еще раз позже.';
            $this->addError(null, $error);
            return false;
        }
    }

    public function sendEmail($user = null)
    {
        if($user === null){
            if(!$user = $this->initUserByEmail()){
                $this->addError(null,'Не удалось отправить почту.');
                return false;
            }
        }

        //set message body
        $body = [
            'userName'  => $user->fullName,
            'userLogin' => $user->email,
            'userPassword' => $this->password,
        ];

        //send mail
        \Yii::import('ext.yii-mail.YiiMailMessage');
        $message = new YiiMailMessage;
        $message->subject = 'Благодарим за регистрацию!';
        $message->view = 'afterRegistration';
        $message->setBody($body, 'text/html');
        $message->addTo($user->email);
        $message->from = \Yii::app()->params['fromMail'];

        return \Yii::app()->mail->send($message);
    }

    public function oldRegistration()
    {
        $user = \models\User::getInstance();

        $user->fullName = $this->fullName;
        $user->email = $this->email;
        $user->password = $this->password;

        try{
            $pass_salt = substr(md5('ps'. uniqid(rand(), true)), 0, 9);
            $pass_hash = sha1(md5($this->password . $pass_salt));
            $cpfvalues = '';

            $helpDesk = new TdMembers();
            $helpDesk->name = $this->email;
            $helpDesk->email = $this->email;
            $helpDesk->password = $pass_hash;
            $helpDesk->pass_salt = $pass_salt;
            $helpDesk->login_key = str_replace("=", "", base64_encode(strrev(crypt($this->password))));
            $helpDesk->joined = time();
            $helpDesk->ipadd = '';
            $helpDesk->email_notify = 1;
            $helpDesk->email_html = 1;
            $helpDesk->email_new_ticket = 1;
            $helpDesk->email_ticket_reply = 1;
            $helpDesk->email_announce = 1;
            $helpDesk->email_staff_new_ticket = 1;
            $helpDesk->email_staff_ticket_reply = 1;
            $helpDesk->email_val = 1;
            $helpDesk->admin_val = 1;
            $helpDesk->mgroup = 1;
            $helpDesk->use_rte = 1;
            $helpDesk->cpfields = serialize($cpfvalues);
            $helpDesk->rss_key = md5('rk' . uniqid(rand(), true));

            $helpDesk->signature = '-';

            if($helpDesk->save()){
                return $user->save();
            } else {
                return false;
            }
        } catch (Exception $e) {
            $error = YII_DEBUG ? $e->getMessage() : 'Возникла ошибка при регистрации. Попробуйте еще раз позже.';
            $this->addError(null, $error);
            return false;
        }
    }
}
