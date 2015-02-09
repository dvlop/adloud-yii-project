<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserLoginForm extends CFormModel {

    public $_identity;

    public $id;
    public $email;
    public $password;
    public $remember = true;

    public function attributeLabels()
    {
        return array(
            'email' => 'Введите ваш логин',
            'password' => 'Введите ваш пароль',
            'remember' => ' Оставаться в системе ',
        );
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            ['email, password', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['email', 'email', 'message' => 'Введён неправильный e-mail'],
            ['remember', 'boolean'],
            ['password', 'authenticate'],
            ['email, password', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function authenticate()
    {
        $this->_identity = new UserIdentity($this->email, $this->password);
        $this->_identity->authenticate();
        $this->remember = true;

        switch($this->_identity->errorCode)
        {
            case UserIdentity::ERROR_NONE:
                $duration = $this->remember ? \Yii::app()->params['defaultUserSessionDuration'] : 0; // 30 days
                try{
                    \Yii::app()->user->login($this->_identity, $duration);
                }catch(Exception $e){
                    $this->addError(null, $e->getMessage());
                }
                break;
            case UserIdentity::ERROR_USERNAME_INVALID:
                $this->addError('email', 'Электронная почта не зарегистрирована');
                break;
            default: // UserIdentity::ERROR_PASSWORD_INVALID
                $this->addError('password', 'Пароль не правильный');
                break;
        }

        return !$this->hasErrors();
    }
}
