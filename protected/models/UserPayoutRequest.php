<?php

namespace application\models;

use application\components\BaseModel;
use models\User;

/**
 * This is the model class for table "user_payout_request".
 *
 * The followings are the available columns in table 'user_payout_request':
 * @property string $id
 * @property string $user_id
 * @property double $amount
 * @property string $date_time
 * @property integer $status
 * @property double $actual_output
 * @property string $comment
 * @property string $payout_date
 * @property string $userId
 * @property string $requestDate
 * @property string $payoutDate
 * @property string $userName
 * @property Users $user
 * @property string $buttons
 * @property string $statusName
 * @property int $statusOpened
 * @property int $statusDone
 * @property int $statusRejected
 * @property UserPayoutRequest $activePayment
 * @property string $paymentDate
 * @property string $dateString
 * @property string $timeString
 * @property \application\models\UserPayoutRequest[] $list
 */
class UserPayoutRequest extends BaseModel
{
    const STATUS_NEW = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_DONE = 2;
    const STATUS_REJECTED = 200;
    const STATUS_ALL = 1000;

    const PAYOUT_DAY = 'next wednesday';

    private static $_statuses = [
        self::STATUS_NEW => 'Новая',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_DONE => 'Совершена',
        self::STATUS_REJECTED => 'Отклонена',
    ];

    private static $_webStatuses = [
        self::STATUS_NEW => 'Новые',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_DONE => 'Совершённые',
        self::STATUS_REJECTED => 'Отклонённые',
        self::STATUS_ALL => 'Все',
    ];

    private static $_buttons = [
        self::STATUS_NEW => '{open}{reset}',
        self::STATUS_IN_WORK => '{submit}{reset}',
        self::STATUS_DONE => '',
        self::STATUS_REJECTED => '{open}',
    ];

    private static $_dateText = [
        self::STATUS_NEW => 'Выплата не подтверждена',
        self::STATUS_DONE => 'Выплата совершена',
        self::STATUS_REJECTED => 'Выплата отклонена',
    ];

    private static $_listColumns = [];

    private static $_search = [];

    private static $_sortColumns = [];

    private $_paymentDate;
    private $_activePayment;
    private $_statusesList;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_payout_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('amount, actual_output', 'numerical'),
			array('date_time', 'length', 'max'=>6),
			array('comment', 'length', 'max'=>255),
			array('user_id, payout_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, amount, date_time, status, actual_output, comment, payout_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
            'user' => [self::BELONGS_TO, 'application\models\Users', 'user_id'],
        ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'userId' => 'User',
			'amount' => 'Сумма',
			'statusName' => 'Статус',
			'requestDate' => 'Дата запроса',
            'payoutDate' => 'Дата выплаты',
			'comment' => 'Комметаний',
            'userName' => 'Пользователь',
		];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserPayoutRequest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\UserPayoutRequest[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\UserPayoutRequest
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }


    public function getUserId()
    {
        return $this->user_id;
    }

    public function getRequestDate()
    {
        return $this->date_time;
    }

    public function getPayoutDate()
    {
        if($this->payout_date === null){
            $this->payout_date = date(\Yii::app()->params['dateTimeFormat'], strtotime(self::PAYOUT_DAY));
        }

        return $this->payout_date;
    }

    public function setUserId($id)
    {
        $this->user_id = $id;
    }

    public function setRequestDate($date = null)
    {
        if($date === null)
            $date = (new \DateTime())->format(\Yii::app()->params['dateTimeFormat']);
        $this->date_time = $date;
    }

    public function setPayoutDate($date = null)
    {
        if($date === null)
            $date = date(\Yii::app()->params['dateTimeFormat'], strtotime(self::PAYOUT_DAY));
        $this->payout_date = $date;
    }

    public function getUserName()
    {
        if($this->user !== null)
            return $this->user->name;
        else
            return 'Пользователь не найден в системе';
    }

    public function getButtons()
    {
        return self::$_buttons[(int)$this->status];
    }

    public function getStatusName()
    {
        return self::$_statuses[(int)$this->status];
    }

    public function getStatusOpened()
    {
        return self::STATUS_IN_WORK;
    }

    public function getStatusDone()
    {
        return self::STATUS_DONE;
    }

    public function getStatusRejected()
    {
        return self::STATUS_REJECTED;
    }

    /**
     * @param null $userId
     * @return \application\models\UserPayoutRequest|null
     */
    public function getActivePayment($userId = null)
    {
        if($userId === null)
            $userId = \Yii::app()->user->id;

        if($this->_activePayment === null)
            $this->_activePayment = [];

        if(!isset($this->_activePayment[$userId])){
            $this->_activePayment[$userId] = $this->find('user_id = :user_id ORDER BY id DESC', ['user_id' => $userId]);
        }

        return $this->_activePayment[$userId];
    }

    public function getDateString()
    {
        return (new \DateTime($this->getPayoutDate()))->format(\Yii::app()->params['dateFormat']);
    }

    public function getTimeString()
    {
        return (new \DateTime($this->getPayoutDate()))->format(\Yii::app()->params['timeFormat']);
    }

    public function getPaymentDate()
    {
        if($this->_paymentDate === null){
            if($this->status == self::STATUS_IN_WORK)
                $this->_paymentDate = $this->getDateString();
            else
                $this->_paymentDate = self::$_dateText[$this->status];

        }

        return $this->_paymentDate;
    }

    public function getFormattedAmount()
    {
        return \Yii::app()->currency->getFormatted($this->amount);
    }

    public function setStatus($status)
    {
        $status = intval($status);
        if(!isset(self::$_webStatuses))
            $status = self::STATUS_NEW;

        $this->status = $status;
    }

    public function getInWork()
    {
        return $this->status == self::STATUS_IN_WORK;
    }

    public function getIsConfirm()
    {
        return $this->status == self::STATUS_NEW;
    }

    /**
     * @return \stdClass[]
     */
    public function getSelectorStatuses()
    {
        if($this->_statusesList === null){
            $this->_statusesList = [];

            foreach(self::$_webStatuses as $value => $name){
                $this->_statusesList[] = (object)[
                    'name' => $name,
                    'value' => $value,
                    'checked' => $this->status == $value,
                ];
            }
        }

        return $this->_statusesList;
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

    public function getAdditionalSearchColumns()
    {
        $attrs = [];

        if(!\Yii::app()->user->isAdmin){
            $attrs['user_id'] = [
                'name' => 'user_id',
                'value' => \Yii::app()->user->id,
                'partialMatch' => false,
                'operation' => 'AND'
            ];
        }

        return $attrs;
    }

    public function addPrepayment($userId = null)
    {
        if($userId === null){
            $userId = \Yii::app()->user->id;
            $user = \Yii::app()->user->model;
        }else{
            try{
                $user = User::getInstance();
                $user->initById($userId);
            }catch(\Exception $e){
                $this->addError(null, $e->getMessage());
                return false;
            }
        }

        if(!$user){
            $this->addError(null, 'Не удалось найти пользователя с ID '.$userId);
            return false;
        }

        $balanse = $user->getMoneyBalance();

        if($balanse <= 0){
            $this->addError(null, 'Нет денег на счету');
            return false;
        }

        if($this->getActivePayment($userId) && (int)$this->getActivePayment($userId)->status === self::STATUS_NEW){
            $this->addError(null, 'Выплата уже установлена');
            return false;
        }

        $this->user_id = $userId;
        $this->amount = $balanse;

        return $this->save(false);
    }

    public function beforeSave()
    {
        if($this->date_time === null)
            $this->date_time = date(\Yii::app()->params['dateTimeFormat']);

        if($this->status === null)
            $this->status = self::STATUS_NEW;

        return parent::beforeSave();
    }
}
