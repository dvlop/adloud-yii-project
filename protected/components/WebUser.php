<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.05.14
 * Time: 12:54
 * @property string $balance
 * @property string $error
 * @property string $moneyRequest
 * @property \models\User $model
 * @property string $avatar
 * @property string $userName
 * @property string $fullName
 * @property string $email
 * @property string $accountUrl
 * @property string $baseInfoUrl
 * @property string $enterInviteUrl
 * @property bool $isAdmin
 * @property bool $isFirstLogin
 * @property bool $isUser
 * @property bool $isAdvertiser
 * @property string $invite
 * @property string $helloName
 * @property \models\UserBaseInfo $baseInfo
 * @property int $id
 * @property string $language
 * @property string $lang
 */

class WebUser extends CWebUser
{
    private static $_languages = [
        'ru' => 'Русский',
        'en' => 'Английский',
    ];

    private $_id;
    private $_balance;
    private $_error;
    private $_model;
    private $_accountUrl = '/account/';
    private $_baseInfoUrl = '/account/baseInfo';
    private $_enterInviteUrl = '/account/enterInvite';
    private $_language;

    public $logoutUrl;

    public function getIsGuest()
    {
        return !$this->getModel();
    }

    public function getBalance()
    {
        return round($this->getFullBalance(), 2);
    }

    public function getFullBalance()
    {
        if($this->_balance === null){
            try{
                $this->_balance = $this->model->getMoneyBalance();
            }catch(Exception $e){
                $this->_error = $e->getMessage();
                return false;
            }
        }

        return $this->_balance;
    }

    public function getId()
    {
        if($this->_id === null)
            $this->setUserId();

        return $this->_id;
    }

    public function setBalance($money)
    {
        try{
            return $this->model->addMoneyBalance($money, rand(10000, 99999));
        }catch(Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function setMoneyRequest($money)
    {
        try{
            return $this->model->addMoneyRequest($money);
        }catch(Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function setInvite($invite = null)
    {
        $this->model->invite = $invite;
    }

    public function setRole($roleId = 200)
    {
        try{
            return $this->model->setRole($roleId);
        }catch(Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getIsAdmin()
    {
        if($this->getIsGuest())
            return false;
        else
            return (int)$this->model->getRole() === 1;
    }

    public function getIsBanned()
    {
        if($this->getIsGuest())
            return false;
        else
            return (int)$this->model->getRole() === 300;
    }

    public function getIsFirstLogin()
    {
        return (!$this->model->invite) && $this->model->getRole() === \models\User::ACCESS_DISABLED;
    }

    public function getIsUser()
    {
        return $this->model->getRole() != \models\User::ACCESS_DISABLED && $this->model->invite !== null && $this->model->invite !== '' && $this->model->invite !== '0';
    }

    public function getIsAdvertiser()
    {
        if(!$this->getIsAdmin())
            return false;
        else
            return true;
    }

    public function getError()
    {
        return $this->_error;
    }

    public function getModel()
    {
        if($this->_model === null){
            try{
                $user = \models\User::getInstance();
                $user->initById($this->getId());
                $this->_model = $user;
            }catch(Exception $e){
                $this->_error = $e->getMessage();
                return false;
            }
        }

        return $this->_model;
    }

    public function getAccountUrl()
    {
        return \Yii::app()->createUrl($this->_accountUrl);
    }

    public function getBaseInfoUrl()
    {
        return \Yii::app()->createUrl($this->_baseInfoUrl);
    }

    public function getEnterInviteUrl()
    {
        return \Yii::app()->createUrl($this->_enterInviteUrl);
    }

    public function getAvatar()
    {
        return '/images'.$this->model->avatar;
    }

    public function getUserName()
    {
        return $this->name;
    }

    public function getFullName()
    {
        return $this->model->fullName;
    }

    public function getLastController(){
        return $this->model->lastController;
    }

    public function getEmail()
    {
        return $this->model->email;
    }

    public function getHelloName()
    {
        if($this->getModel() && $this->getModel()->fullName){
            return $this->getModel()->fullName;
        }elseif($this->getModel() && $this->getModel()->email){
            $name = explode('@', $this->getModel()->email);
            return $name[0];
        }else{
            return '';
        }
    }

    public function getInvite()
    {
        return $this->model->invite;
    }

    public function getBaseInfo($id = null)
    {
        try{
            return $this->model->getBaseInfo($id);
        }catch(Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getLang()
    {
        return $this->model->lang;
    }

    public function getLanguage()
    {
        if($this->_language === null){
            if(!$this->getIsGuest()){
                $this->_language = $this->lang;
            }

            if(!$this->_language || !isset(self::$_languages[$this->_language])){
                $list = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                $languages = [];
                $langs = [
                    'ru' => ['ru', 'uk']
                ];

                if($list){
                    if(preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list)){
                        $languages = array_combine($list[1], $list[2]);

                        foreach($languages as $n => $v){
                            $languages[$n] = $v ? $v : 1;
                        }

                        arsort($languages, SORT_NUMERIC);
                    }
                }

                $tmpLangs = [];

                foreach($langs as $lang => $alias){
                    if(is_array($alias)){
                        foreach ($alias as $alias_lang) {
                            $tmpLangs[strtolower($alias_lang)] = strtolower($lang);
                        }
                    }else{
                        $tmpLangs[strtolower($alias)] = strtolower($lang);
                    }
                }

                foreach($languages as $l => $v){
                    $s = strtok($l, '-');

                    if(isset($tmpLangs[$s])){
                        $this->_language = $tmpLangs[$s];
                        break;
                    }
                }
            }

            if(!$this->_language || !isset(self::$_languages[$this->_language]))
                $this->_language = 'en';
        }

        return $this->_language;
    }

    public function getLanguages()
    {
        return self::$_languages;
    }

    public function addMoneyToBalance($money, $message)
    {
        $money = floatval($money);

        if($money <= 0){
            $this->_error = 'Неверная сумма: '.$money;
            return false;
        }

        return $this->getModel()->addMoneyBalance($money, $message);
    }

    public function addMoneyBalance($money, $params = [])
    {
        return $this->addMoneyToBalance($money, $params);
    }

    public function checkInvite($invite = '')
    {
        return $this->model->checkInvite($invite);
    }

    public function update($id = null)
    {
        return $this->model->update($id);
    }

    public function login($identity, $duration=0)
    {
        if(!$this->_model = \models\User::getInstance()->initByEmail($identity->username)){
            $this->_error = 'Email-адтес ';
            return false;
        }

        parent::login($identity, $duration);
        return $this->setUserId($this->_model->getId());
    }

    public function noPasswordLogin($email)
    {
        try{
            $user = \models\User::getInstance();

            if($user->initByEmail($email))
                return $user->login();
            else
                $this->_error = 'not correct email';
        }catch(Exception $e){
            $this->_error = $e->getMessage();
        }

        return false;
    }

    public function loginById($id)
    {
        try{
            $user = \models\User::getInstance();

            if($user->initById($id))
                return $user->login();
            else
                $this->_error = 'not correct id';
        }catch(Exception $e){
            $this->_error = $e->getMessage();
        }

        return false;
    }

    public function setUserId($id = null)
    {
        if($this->_id === null){
            $id = intval($id);

            try{
                if(!$id)
                    $id = \core\Session::getInstance()->getUserId();

                if(!$id){
                    if($this->_model)
                        $id = $this->_model->getId();
                    else
                        $id = parent::getId();
                }

                if(!$id)
                    return false;

                $this->_id = $id;

                $user = \models\User::getInstance();
                if($user->initById($id)){
                    if($this->_model === null)
                        $this->_model = $user;

                    \core\Session::getInstance()->setUserId($id);
                }
                else{
                    $this->_error = 'Не удалось найти пользователя ID '.$id;
                }
            }catch(Exception $e){
                $this->_error = $e->getMessage();
                return false;
            }
        }

        return !$this->_error ? $this->_id : false;
    }

    public function setLang($lang)
    {
        $lang = (string)$lang;
        if(!$lang || !isset(self::$_languages[$lang]))
            $lang = 'en';

        $this->model->lang = $lang;
    }

    public function changePassword($oldPass, $newPass)
    {
        if(!$this->model->checkPassword($oldPass)){
            $this->_error = 'Не правильный старый пароль';
            return false;
        }

        $newPass = htmlspecialchars($newPass);
        $newPass = (string)$newPass;

        if(!$newPass){
            $this->_error = 'Не правильный новый пароль';
            return false;
        }

        return $this->model->setPassword($newPass);
    }

    public function checkUserId($id)
    {
        return \models\User::getInstance()->initById($id);
    }

    public function setFlash($key, $value = null, $addition = null){
        if($value){
            $value .= $addition ? $addition : '';
            \application\models\Notification::create([
                'user_id' => $this->getId(),
                'is_new' => false,
                'text' => $value,
                'type' => $key,
                'is_shown' => true
            ]);
        }

        return parent::setFlash($key,$value,$addition);
    }
}