<?php

/**
 * SetNewPasswordForm class.
 * SetNewPasswordForm is the data structure for keeping
 * user set new password form data. It is used by the 'SetNewPassword' action of 'SiteController'.
 */
class UserSetNewPasswordForm extends CFormModel {
    public $email;
    public $password;
    public $password2;

    public function attributeLabels()
    {
        return array(
            'email' => 'Электронная почта',
            'password' => 'Введите ваш новый пароль',
            'password2' => 'Подтвердите ваш новый пароль',
        );
    }

    public function rules()
    {
        return [
            ['email, password, password2', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['email', 'email', 'message' => 'Электронная почта должна быть указана верно'],
            ['password', 'compare', 'compareAttribute'=>'password2', 'message' => 'Пароль и повторный пароль должны совпадать'],
            ['email, password, password2', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function setNewPassword()
    {
        $user = \models\User::getInstance();
        if($user->initByEmail($this->email)){
            try{
                if(!$result = $user->setPassword($this->password))
                    $this->addError(null, 'Не удалось сохранить новый пароль. Попробуйте позже.');

                return $result;
            }catch(Exception $e){
                $error = YII_DEBUG ? $e->getMessage() : 'Произошла ошибка при попытке изменить пароль. Попробуйте позже.';
                $this->addError(null, $error);
                return false;
            }
        }else{
            $this->addError(null, 'Электронная почта не зарегистрирована');
            return false;
        }
    }
}
