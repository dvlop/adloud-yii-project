<?php

namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\UserDataSource;

/**
 * @property UserDataSource $nextLayer
 */
class User extends \MLF\layers\Logic implements CRUDInterface
{
    const ACCESS_USER = 0;
    const ACCESS_ADMIN = 1;
    const ACCESS_DISABLED = 200;
    const ACCESS_BANNED = 300;

    public $fullName;
    public $email;
    public $password;
    public $newPassword;
    public $wmz;
    public $wmr;
    public $yandex;
    public $qiwi;
    public $avatar = '/files/def.avatar-img.png';
    public $isq;
    public $skype;
    public $invite;
    public $referer;
    public $lastController;
    public $lang;

    private $id;
    private $registerDate;
    private $passwordHash;
    private $helpDescPassword;
    private $role = 0;
    private $_baseInfo;

    /**
     * @return \models\User
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @return mixed
     */
    public function getHelpDescPassword()
    {
        return $this->helpDescPassword;
    }

    public function save()
    {
        $hdPass = md5(microtime());
        $data = array(
            'fullName' => htmlspecialchars($this->fullName),
            'email' => $this->email,
            'password' => crypt($this->password),
            'helpDescPassword' => $hdPass,
            'webmoney_wmz' => htmlspecialchars($this->wmz),
            'webmoney_wmr' => htmlspecialchars($this->wmr),
            'yndex_id' => htmlspecialchars($this->yandex),
            'qiwi_id' => htmlspecialchars($this->qiwi),
            'isq' => htmlspecialchars($this->isq),
            'skype' => htmlspecialchars($this->skype),
            'invite' => $this->invite ? $this->invite : $this->getRandomString(),
            'access_level' => self::ACCESS_USER,
            'referer' => $this->referer,
            'lang' => $this->lang,
        );

        $id = $this->nextLayer->save($data);
        $this->id = $id;
        $this->helpDescPassword = $hdPass;
        return $id;
    }

    public function initById($id = null)
    {
        if(!$id){
            $id = Session::getInstance()->getUserId();
        }

        $userData = $this->nextLayer->getById($id);

        if (!$userData) {
            return false;
        }
        $this->init($userData);
        return true;
    }

    public function initByEmail($email)
    {
        $userData = $this->nextLayer->getByEmail($email);
        if (!$userData) {
            return false;
        }
        $this->init($userData);
        return $this;
    }

    public function checkPassword($password)
    {
        $password = htmlspecialchars($password);
        if(crypt($password, $this->passwordHash) == $this->passwordHash){
            return true;
        }
        return false;
    }

    public function getMoneyBalance()
    {
        return $this->nextLayer->getMoney($this->id);
    }

    public function getList()
    {
        if(Session::getInstance()->getUserAccessLevel() !== User::ACCESS_ADMIN){
            throw new \LogicException('user is not an admin');
        }

        return $this->findAll();
    }

    public function addMoneyBalance($amount, $description)
    {
        return $this->nextLayer->addMoneyBalance($amount, $description, $this->id);
    }

    public function addMoneyRequest($amount){
        return $this->nextLayer->addMoneyRequest($amount, $this->id);
    }

    public function getReferals()
    {
        $userId = Session::getInstance()->getUserId();
        return $this->nextLayer->getReferals($userId);
    }

    public function getTransactionLog(array $params) {
        $params['user_id'] = Session::getInstance()->getUserId();
        return $this->nextLayer->getTransactionLog($params);
    }

    public function update($id = null)
    {
        $params = [];

        if($this->fullName){
            $params['full_name'] = htmlspecialchars($this->fullName);
        }
        if($this->email){
            $params['email'] = $this->email;
        }
        if($this->wmz){
            $params['webmoney_wmz'] = htmlspecialchars($this->wmz);
        }
        if($this->wmr){
            $params['webmoney_wmr'] = htmlspecialchars($this->wmr);
        }
        if($this->yandex){
            $params['yandex_id'] = htmlspecialchars($this->yandex);
        }
        if($this->qiwi){
            $params['qiwi_id'] = htmlspecialchars($this->qiwi);
        }
        if($this->password && $this->newPassword){
            if(!$this->checkPassword($this->password)){
                throw new \LogicException('not correct password');
            }

            $params['password'] = crypt($this->newPassword);
        }
        if($this->isq){
            $params['isq'] = htmlspecialchars($this->isq);
        }
        if($this->skype){
            $params['skype'] = htmlspecialchars($this->skype);
        }
        if($this->invite){
            $params['invite'] = $this->invite;
        }
        if($this->avatar){
            $params['avatar'] = $this->avatar;
        }
        if($this->lang){
            $params['lang'] = $this->lang;
        }

        if(!$params){
            return false;
        }

        return $this->nextLayer->update($params, $id ? $id : $this->id);
    }

    public function delete($id)
    {
        return true;
    }

    public function requestMoneyPayout($amount){
        if($this->getMoneyBalance() < $amount){
            throw new \LogicException('not enough money');
        }

        $requests = $this->nextLayer->getMoneyPayoutRequestList($this->id, 1, 0);

        $hasActive = false;

        foreach($requests as $request){
            if($request['status'] == 0){
                $hasActive = true;
                break;
            }
        }

        if($hasActive){
            throw new \LogicException('you have active requests');
        }

        return $this->nextLayer->requestMoneyPayout($amount, $this->id);
    }

    public function getMoneyPayoutRequestList($limit = 100, $offset = 0){
        return $this->nextLayer->getMoneyPayoutRequestList($this->id, $limit, $offset);
    }

    public function getBaseInfo($id = null)
    {
        if($id === null){
            if($this->id === null)
                $this->id = \core\Session::getInstance()->getUserId();
            $id = $this->id;
        }

        if($this->_baseInfo === null){
            $this->_baseInfo = UserBaseInfo::getInstance()->initById($id);
        }
        return $this->_baseInfo;
    }

    public function checkInvite($invite = '')
    {
        return $invite === $this->invite;
    }

    public function setRole($roleId = self::ACCESS_DISABLED)
    {
        $roleId = (int)$roleId;

        if($roleId !== self::ACCESS_DISABLED && $roleId !== self::ACCESS_USER && $roleId !== self::ACCESS_ADMIN && $roleId !== self::ACCESS_BANNED)
            throw new \LogicException('not correct role ID');

        $userId = $this->id ? $this->id : \core\Session::getInstance()->getUserId();

        return $this->nextLayer->setRole($roleId, $userId);
    }

    public function activate($userId = null, $invite = '')
    {
        $roleId = self::ACCESS_DISABLED;
        if(!$userId)
            $userId = $this->id ? $this->id : \core\Session::getInstance()->getUserId();
        if(!$invite)
            $invite = $this->getRandomString();

        $result = $this->nextLayer->activateUser($userId, $invite, $roleId);
        if($result)
            return $invite;
        else
            return false;
    }

    public function setPassword($password, $id = null)
    {
        if($id === null){
            if($this->id === null)
                $this->id = \core\Session::getInstance()->getUserId();
            $id = $this->id;
        }

        if(!$password = crypt((string)$password))
            throw new \LogicException('invalid password');

        $hdPass = md5(microtime());

        return $this->nextLayer->setPassword($password, $hdPass, $id);
    }

    public function getRandomString($length = 15)
    {
        $chars = '-abdefhiknrstyzABDEFGHKNQRSTYZ0123456789';
        $numChars = strlen($chars);
        $str = '';
        for($i = 0; $i < $length; $i++){
            $str .= substr($chars, rand(1, $numChars)-1, 1);
        }
        return $str;
    }

    public function login()
    {
        \core\Session::getInstance()->setUserId($this->id);
        return true;
    }

    public function getStats($arParams = [])
    {
        if(Session::getInstance()->getUserAccessLevel() !== User::ACCESS_ADMIN){
            throw new \LogicException('user is not an admin');
        }

        return $this->nextLayer->getStats($arParams);
    }

    private function init(array $userData)
    {
        $this->id = $userData['id'];
        $this->email = htmlspecialchars($userData['email']);
        $this->fullName = $userData['full_name'];
        $this->passwordHash = trim($userData['password']);
        $this->registerDate = $userData['register_date'];
        $this->helpDescPassword = $userData['help_desc_password'];
        $this->wmz = trim($userData['webmoney_wmz']);
        $this->wmr = trim($userData['webmoney_wmr']);
        $this->yandex = trim($userData['yandex_id']);
        $this->qiwi = trim($userData['qiwi_id']);
        $this->role = $userData['access_level'];
        $this->isq = trim($userData['isq']);
        $this->skype = trim($userData['skype']);
        $this->invite = htmlspecialchars(trim($userData['invite']));
        $this->referer = $userData['referer'];
        $this->lastController = isset($userData['last_controller']) ? $userData['last_controller'] : null;
        $this->lang = trim($userData['lang']);

        $avatar = trim($userData['avatar']);
        if($avatar)
            $this->avatar = $avatar;
    }
}