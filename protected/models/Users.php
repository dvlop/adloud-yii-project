<?php

namespace application\models;

use application\components\BaseModel;
use models\Ads as Ad;
use models\Block;
use core\RedisIO;

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $full_name
 * @property string $register_date
 * @property string $last_login
 * @property integer $access_level
 * @property string $help_desc_password
 * @property string $webmoney_wmz
 * @property string $isq
 * @property string $skype
 * @property string $webmoney_wmr
 * @property string $yandex_id
 * @property string $qiwi_id
 * @property string $invite
 * @property string $avatar
 * @property string $fullName
 * @property string $name
 *
 * The followings are the available model relations:
 * @property \application\models\Sites[] $sites
 * @property \application\models\AdvertiserStats[] $advertiserStats
 * @property \application\models\WebmasterStats[] $webmasterStats
 * @property \application\models\Ticket[] $tickets
 * @property \application\models\Message[] $messages
 * @property \application\models\string $accessLevel
 * @property \application\models\array $statuses
 * @property \application\models\array $activities
 * @property \application\models\Campaign[] $campaign
 * @property float $money
 */
class Users extends BaseModel
{
    const REDIS_MONEY_KEY = 'money:';

    public $shows;
    public $clicks;
    public $costs;
    public $sites_cost;

    const ACCESS_USER = 0;
    const ACCESS_ADMIN = 1;
    const ACCESS_DISABLED = 200;
    const ACCESS_BANNED = 300;

    const ACTIVE_USERS = 1;
    const PASSIVE_USERS = 2;

    private static $_rolesNames = [
        self::ACCESS_USER => 'Пользователь',
        self::ACCESS_ADMIN => 'Администратор',
        self::ACCESS_DISABLED => 'Отключён',
        self::ACCESS_BANNED => 'Забанен',
    ];

    private static $_ativitiesNames = [
        self::ACTIVE_USERS => 'Активные',
        self::PASSIVE_USERS => 'Пассивные'
    ];

    private static $_listColumns = [
        'id',
        'email',
        'full_name',
        'access_level' => [
            'value' => 'role',
        ],
        'sites_cost',
        'shows',
        'clicks',
        'shows',
        'costs'
    ];

    private static $_search = [
        'id' => [
            'partialMatch' => false
        ],
        'email',
        'full_name',
    ];

    private static $_sortColumns = [
        'id',
        'email',
        'full_name',
        'access_level',
    ];

    private static $_listQuery = [
        'select' => [
            't.id',
            't.email',
            't.full_name',
            't.access_level',
            't.invite',
            't.access_level',
            'coalesce(SUM(ws.shows), 0) AS shows',
            'coalesce(SUM(ws.clicks), 0) AS clicks',
            '(CASE
                WHEN coalesce(SUM(ws.shows), 0) > 0
                    THEN coalesce(SUM(ws.clicks), 0)/coalesce(SUM(WS.shows), 1)*100
                ELSE 0
             END) AS ctr',
            'coalesce(SUM(ws.costs), 0) AS costs',
            '(SELECT COUNT (*) FROM sites WHERE sites.user_id = t.id) AS sites_cost',
        ],
        'join' => 'LEFT JOIN webmaster_stats AS ws ON t.id = ws.user_id',
        'group' => 't.id',
    ];

    private $_name;
    private $_statuses;
    private $_activities;
    private $_money;

    public function init()
    {
        $this->access_level = null;
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['email, password, register_date', 'required'],
			['access_level', 'numerical', 'integerOnly'=>true],
			['email', 'length', 'max'=>100],
			['password', 'length', 'max'=>102],
			['full_name', 'length', 'max'=>255],
			['last_login', 'length', 'max'=>6],
			['help_desc_password', 'length', 'max'=>32],
			['webmoney_wmz, webmoney_wmr, yandex_id, qiwi_id', 'length', 'max'=>200],
			['isq, skype', 'length', 'max'=>48],
			['invite', 'length', 'max'=>256],
			['avatar', 'length', 'max'=>512],
            ['email, password, full_name, webmoney_wmz, isq, skype, webmoney_wmr, yandex_id, qiwi_id, invite', 'filter', 'filter' => 'htmlspecialchars'],
        ];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'sites' => [self::HAS_MANY, '\application\models\Sites', 'user_id'],
			'advertiserStats' => [self::HAS_MANY, '\application\models\AdvertiserStats', 'user_id'],
			'webmasterStats' => [self::HAS_MANY, '\application\models\WebmasterStats', 'user_id'],
			'tickets' => [self::HAS_MANY, '\application\models\Ticket', 'user_id'],
			'messages' => [self::HAS_MANY, '\application\models\Message', 'user_id'],
            'campaign' => [self::HAS_MANY, '\application\models\Campaign', 'user_id'],
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'email' => 'Email',
			'password' => 'Пароль',
			'full_name' => 'Имя',
			'register_date' => 'Дата регистрации',
			'last_login' => 'Последнаяя активность',
			'access_level' => 'Роль',
			'help_desc_password' => 'Help Desc Password',
			'webmoney_wmz' => 'Кошелёк Webmoney Wmz',
			'isq' => 'Isq',
			'skype' => 'Skype',
			'webmoney_wmr' => 'Кошелёк Webmoney Wmr',
			'yandex_id' => 'Кошелёк Yandex-деньги',
			'qiwi_id' => 'Кошелёк Qiwi',
			'invite' => 'Код активации',
			'avatar' => 'Фото',
            'clicks' => 'Клики',
            'shows' => 'Показы',
            'ctr' => 'CTR',
            'costs' => 'Зароботок',
            'sitesCost' => 'Количество сайтов',
		];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Users[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Users
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function getName()
    {
        if($this->_name === null){
            if($this->full_name){
                $this->_name = $this->full_name;
            }else{
                $name = explode('@', $this->email);
                $this->_name = $name[0];
            }
        }

        return $this->_name;
    }

    public function getAds(array $params){
        $ads = [];
        foreach($this->campaign as $camp){
            foreach($camp->ads as $ad){
                if($params['status'] == 'archived'){
                    if($ad->status == Ad::STATUS_ARCHIVED){
                        $ads[] = $ad;
                    }
                } else {
                    if($ad->status != Ad::STATUS_ARCHIVED){
                        $ads[] = $ad;
                    }
                }
            }
        }
        return $ads;
    }

    public function getAdsByCampaign(array $params){
        $ads = [];
        $camp = Campaign::model()->findByPk($params['id']);
        foreach($camp->ads as $ad){
            if($params['status'] == 'archived'){
                if($ad->status == Ad::STATUS_ARCHIVED){
                    $ads[] = $ad;
                }
            } else {
                if($ad->status != Ad::STATUS_ARCHIVED){
                    $ads[] = $ad;
                }
            }
        }
        return $ads;
    }

    public function getAdsById(array $params){
        $ads = Ads::model()->findAllByPk($params['id']);
        return $ads;
    }

    public function getBlocks(array $params){
        $blocks = [];
        foreach($this->sites as $site){
            foreach($site->blocks as $block){
                if($params['status'] == 'archived'){
                    if($block->status == Block::STATUS_ARCHIVED){
                        $blocks[] = $block;
                    }
                } else {
                    if($block->status != Block::STATUS_ARCHIVED){
                        $blocks[] = $block;
                    }
                }
            }
        }
        return $blocks;
    }

    public function getBlocksBySite(array $params){
        $blocks = [];
        $site = Sites::model()->findByPk($params['id']);
        foreach($site->blocks as $block){
            if($params['status'] == 'archived'){
                if($block->status == Block::STATUS_ARCHIVED){
                    $blocks[] = $block;
                }
            } else {
                if($block->status != Block::STATUS_ARCHIVED){
                    $blocks[] = $block;
                }
            }
        }
        return $blocks;
    }

    public function getBlocksById(array $params){
        $blocks = Blocks::model()->findAllByPk($params['id']);
        return $blocks;
    }

    public function getListColumns()
    {
        return self::$_listColumns;
    }

    public function getSearchColumns()
    {
        return self::$_search;
    }

    public function getSortColumns()
    {
        return self::$_sortColumns;
    }

    public function getAccessLevel()
    {
        return $this->access_level;
    }

    public function setAccessLevel($level)
    {
        if(!in_array($level, self::$_rolesNames)){
            $this->addError(null, 'Не бывает такой роли');
            return;
        }
        $this->access_level = $level;
    }

    public function getMoney()
    {
        if($this->_money === null){
            $this->_money = floatval(RedisIO::get(self::REDIS_MONEY_KEY.$this->id));
        }

        return $this->_money;
    }

    public function getRole()
    {
        $lavel = $this->access_level !== null ? $this->access_level : self::ACCESS_USER;
        return self::$_rolesNames[$lavel];
    }

    public function getStatuses()
    {
        if($this->_statuses === null){
            $this->_statuses = [];

            $statuses = [
                1000 => 'Любой'
            ];

            foreach(self::$_rolesNames as $id => $name){
                $statuses[$id] = $name;
            }

            $role = $this->getRoleQuery();

            foreach($statuses as $id => $name){
                $this->_statuses[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'selected' => $role !== null && $id == $role
                ];
            }
        }

        return $this->_statuses;
    }

    public function getActivities()
    {
        if($this->_activities === null){
            $this->_activities = [];

            $activities = [
                1000 => 'Все'
            ];

            foreach(self::$_ativitiesNames as $id => $name){
                $activities[$id] = $name;
            }

            $activity = $this->getActivityQuery();

            foreach($activities as $id => $name){
                $this->_activities[] = (object)[
                    'value' => $id,
                    'name' => $name,
                    'selected' => $activity !== null && $id == $activity
                ];
            }
        }

        return $this->_activities;
    }

    public function getSitesCost()
    {
        return $this->sites_cost;
    }

    public function beforeSearch()
    {
        return parent::beforeSearch();
    }

    public function getAdditionalSearchColumns()
    {
        $query = self::$_listQuery;

        $attrs = [];

        $role = $this->getRoleQuery();
        if($role !== null){
            $attrs['access_level'] = [
                'name' => 'access_level',
                'value' => $role,
                'partialMatch' => false,
                'operation' => 'AND'
            ];
        }

        $activity = $this->getActivityQuery();
        if($activity !== null){
            if($activity == self::ACTIVE_USERS){
                $attrs['shows'] = [
                    'name' => 'shows',
                    'value' => '> 0',
                    'partialMatch' => false,
                    'operation' => 'AND'
                ];
            }elseif($activity == self::PASSIVE_USERS){
                $attrs['shows'] = [
                    'name' => 'shows',
                    'value' => 0,
                    'partialMatch' => false,
                    'operation' => 'AND'
                ];
            }
        }

        $attrs[] = [
            'query' => $query,
        ];

        return $attrs;
    }

    private function getRoleQuery()
    {
        $result = null;
        $userStatus = \Yii::app()->request->getQuery('userStatus');

        if($userStatus !== null){
            $userStatus = intval($userStatus);
            if(isset(self::$_rolesNames[$userStatus]))
                $result = $userStatus;
        }

        return $result;
    }

    private function getActivityQuery()
    {
        $result = null;
        $userActivity = \Yii::app()->request->getQuery('userActivity');

        if($userActivity !== null){
            $userActivity = intval($userActivity);
            if(isset(self::$_ativitiesNames[$userActivity]))
                $result = $userActivity;
        }

        return $result;
    }
}
