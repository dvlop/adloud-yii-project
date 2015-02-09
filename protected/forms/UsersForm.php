<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 28.05.14
 * Time: 11:54
 * @property string $role
 * @property array $users
 * @property bool $isActive
 * @property \models\UserBaseInfo $baseInfo
 * @property int $ctr
 */

class UsersForm extends CFormModel
{
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;
    const ROLE_NO_ACTIVE = 200;
    const ROLE_BANNED = 300;

    const SELECTOR_BANNED = 'banned';
    const SELECTOR_NORMAL = 'normal';
    const SELECTOR_ACTIVE = 'active';
    const SELECTOR_PASSIVE = 'passive';
    const SELECTOR_ALL = 'all';

    public $id;
    public $email;
    public $fullName;
    public $registerDate;
    public $lastLogin;
    public $webmoneyWmz;
    public $webmoneyWmr;
    public $isq;
    public $skype;
    public $yandexId;
    public $qiwiId;
    public $invite;
    public $password;
    public $status = self::SELECTOR_NORMAL;
    public $activity = self::SELECTOR_ACTIVE;


    public static $statusSelectors  = [
        self::SELECTOR_NORMAL => 'Нормальные',
        self::SELECTOR_BANNED => 'Забаненные',
        self::SELECTOR_ALL => 'Все',
    ];

    private static $activitySelectors  = [
        self::SELECTOR_ACTIVE => 'Активные',
        self::SELECTOR_PASSIVE => 'Пассивные',
        self::SELECTOR_ALL => 'Все',
    ];

    private $_role;
    private $_users;
    private $_baseInfo;
    private $_banSelectors;
    private $_activeSelectors;

    private static $rolesNames = [
        self::ROLE_USER => 'Пользователь',
        self::ROLE_ADMIN => 'Администратор',
        self::ROLE_NO_ACTIVE => 'Деактивирован',
        self::ROLE_BANNED => 'Забанен'
    ];

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'fullName' => 'Имя пользователя',
            'registerDate' => 'Дата регистрации',
            'lastLogin' => 'Последнее посещение',
            'role' => 'Роль пользователя',
            'isActive' => 'Активность',
            'webmoneyWmz' => 'Webmoney (WMZ)',
            'isq' => 'ISQ',
            'skype' => 'Skype',
            'webmoneyWmr' => 'Webmoney (WMR)',
            'yandexId' => 'Yandex',
            'qiwiId' => 'Qiwi',
            'invite' => 'Код',
            
            'sitesCount' => 'Количество сайтов',
            'shows' => 'Показы',
            'clicks' => 'Клики',
            'ctr' => 'CTR',
            'costs' => 'Заработок',
        ];
    }

    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['email', 'email'],
            ['fullName, registerDate, lastLogin, webmoneyWmz, isq, skype, webmoneyWmr, yandexId, qiwiId, invite', 'type', 'type'=>'string'],
            //array('siteUrl, statLink, statLogin, statPassword', 'required', 'message' => 'Поле {attribute} должно быть заполнено'),
            //array('siteUrl, statLink', 'length', 'min'=>1, 'max'=>126),
            //array('statLogin, statPassword', 'length', 'min'=>1, 'max'=>15),
            //array('description', 'length', 'max'=>512),
            //array('desiredProfit, description', 'default'),
        ];
    }

    public function getRole($roleId = null)
    {
        if($roleId === null)
            $roleId = $this->_role;

        return self::$rolesNames[$roleId];
    }

    public function getIsUser($userId = null)
    {
        if($userId === null)
            $userId = $this->id;

        if(!isset($this->users[$userId])){
            $this->addError(null, 'Не существует пользователя с ID '.$userId);
            return false;
        }

        $user = $this->users[$userId];

        return $user->access_level != \models\User::ACCESS_DISABLED && $user->invite !== null && $user->invite !== '' && $user->invite !== '0';
    }

    public function setStatus($status)
    {
        $status = htmlspecialchars($status);
        if(isset(self::$statusSelectors[$status]))
            $this->status = $status;
    }

    public function setActivity($activity)
    {
        $activity = htmlspecialchars($activity);
        if(isset(self::$activitySelectors[$activity]))
            $this->activity = $activity;
    }

    public function getUsers()
    {
        if($this->_users === null){
            $params = [
                'sortBy' => null,
                'sortOrder' => null,
                'status' => $this->status,
                'activity' => $this->activity,
            ];

            try{
                $users = \models\User::getInstance()->getStats($params);
                $this->_users = [];

                if($users){
                    foreach($users as $user){
                        $this->_users[$user->id] = $user;
                    }
                }
            }catch(Exception $e){
                $this->addError(null, $e->getMessage());
            }
        }

        return $this->_users;
    }

    public function getSortOrder($sortBy)
    {
        $sortOrder = 'asc';

        if($this->arParams['sortBy'] == $sortBy && $this->arParams['sortOrder'] == 'asc')
        {
            $sortOrder = 'desc';
        }
        return $sortOrder;
    }

    public function getSortLink($sortBy)
    {
        return '?sortBy='.$sortBy.'&sortOrder='.$this->getSortOrder($sortBy);
    }

    public function getIsActive($id = null)
    {
        if($id === null){
            $user = $this;
            $role = $this->role;
        }else{
            $user = $this->users[$id];
            $role = $user->access_level;
        }

        return $role != \models\User::ACCESS_DISABLED && $user->invite !== '' && $user->invite !== null && $user->invite !== '0' ? true : false;
    }

    public function getIsBanned($id = null)
    {
        if($id === null){
            $user = $this;
            $role = $this->role;
        }elseif($this->getUsers()){
            $user = isset($this->getUsers()[$id]) ? $this->getUsers()[$id] : end($this->getUsers());
            $role = $user->access_level;
        }else{
            $user = end($this->getUsers());
            $role = $user->access_level;
        }

        return $role == \models\User::ACCESS_BANNED && $user->invite !== '' && $user->invite !== null && $user->invite !== '0' ? true : false;
    }

    public function initUser($id = null)
    {
        if($id === null)
            $id = \Yii::app()->user->id;

        try{
            if(!$user = \models\User::getInstance()->findById($id)){
                $this->addError('null', 'В базе данных отсутствует пользоваель с id '.$id);
                return false;
            }

            $this->id = $id;
            $this->email = trim($user->email);
            $this->fullName = $user->full_name;
            $this->registerDate = $user->register_date;
            $this->lastLogin = trim($user->last_login);
            $this->_role = $user->access_level;
            $this->webmoneyWmz = trim($user->webmoney_wmz);
            $this->isq = trim($user->isq);
            $this->skype = trim($user->skype);
            $this->webmoneyWmr = trim($user->webmoney_wmr);
            $this->yandexId = trim($user->yandex_id);
            $this->qiwiId = trim($user->qiwi_id);
            $this->invite = trim($user->invite);

            return true;
        }catch(Exception $e){
            $this->addError('null', $e->getMessage());
            return false;
        }
    }

    public function getBaseInfo()
    {
        if($this->_baseInfo === null){
            $this->_baseInfo = \Yii::app()->user->getBaseInfo($this->id);
        }
    }

    /**
     * @return \stdClass[]
     */
    public function getStatusSelectors()
    {
        if($this->_activeSelectors === null){
            $this->_activeSelectors = [];

            foreach(self::$statusSelectors as $value => $name){
                $this->_activeSelectors[] = (object)[
                    'name' => $name,
                    'value' => $value,
                    'checked' => $this->status == $value,
                ];
            }
        }

        return $this->_activeSelectors;
    }

    /**
     * @return \stdClass[]
     */
    public function getActivitySelectors()
    {
        if($this->_banSelectors === null){
            $this->_banSelectors = [];

            foreach(self::$activitySelectors as $value => $name){
                $this->_banSelectors[] = (object)[
                    'name' => $name,
                    'value' => $value,
                    'checked' => $this->activity == $value,
                ];
            }
        }

        return $this->_banSelectors;
    }

    public function save()
    {
        $user = \models\User::getInstance();

        if($this->id !== null){
            $user->initById($this->id);
        }

        unset($user->password);

        $user->fullName = trim($this->fullName);
        $user->email = trim($this->email);
        $user->wmz = trim($this->webmoneyWmz);
        $user->wmr = trim($this->webmoneyWmr);
        $user->yandex = trim($this->yandexId);
        $user->qiwi = trim($this->qiwiId);
        $user->isq = trim($this->isq);
        $user->skype = trim($this->skype);
        $user->invite = trim($this->invite) ? trim($this->invite) : null;

        try{
            if($user->getId())
                $result = $user->update();
            else
                $result = $user->save();

            if($result && $user->invite !== null && $user->invite !== '' && $user->invite !== '0'){
                $userInfo = new UserInfoModel();
                $userInfo->id = $this->id;
                $userInfo->email = $this->email;
                $userInfo->fullName = $this->fullName;

                $result = $userInfo->sendUserCode($user->invite);
                if($userInfo->hasErrors())
                    $this->addErrors($userInfo->errors);
            }

            return $result;
        }catch(Exception $e){
            $this->addError('save', $e->getMessage());
            return false;
        }
    }
}