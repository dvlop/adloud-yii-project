<?php

namespace application\models;

use application\components\ActualModel;

/**
 * This is the model class for table "transactions".
 *
 * The followings are the available columns in table 'transactions':
 * @property double $amount
 * @property string $description
 * @property string $ip
 * @property string $referer
 * @property string $block_id
 * @property string $ads_id
 * @property string $id
 * @property string $recipient_id
 * @property string $sender_id
 * @property string $timestamp
 * @property string $from
 * @property string $to
 * @property double $sender_balance
 * @property double $recipient_balance
 *
 * @property \application\models\behaviors\DataTablesBehavior $dataTable
 */
class Transactions extends ActualModel
{
    const ADVERTISER_INDEX = 'advertiser';
    const WEBMASTER_INDEX = 'webmaster';

    private static  $_columnNames = [
        [
            'name' => 'id',
            'text' => 'ID',
            'type' => 'integer'
        ],
        [
            'name' => 'date',
            'text' => 'Дата',
            'sort' => 'timestamp',
            'type' => 'date',
        ],
        [
            'name' => 'ip',
            'text' => 'IP',
            'type' => 'string',
        ],
        [
            'name' => 'referer',
            'text' => 'Реферер',
            'type' => 'string',
        ],
        [
            'name' => 'adsId',
            'text' => 'ID тизера',
            'sort' => 'ads_id',
            'type' => 'integer'
        ],
        [
            'name' => 'blockId',
            'text' => 'ID блока',
            'sort' => 'block_id',
            'type' => 'integer'
        ],
        [
            'name' => 'amount',
            'text' => 'Сумма',
            'type' => 'integer'
        ],
        [
            'name' => 'senderId',
            'text' => 'ID Рекламодателя',
            'sort' => 'sender_id',
            'type' => 'integer'
        ],
        [
            'name' => 'recipientId',
            'text' => 'ID Вебмастера',
            'sort' => 'recipient_id',
            'type' => 'integer',
        ],
        [
            'name' => 'senderBalance',
            'text' => 'Баланс реклмодателя',
            'sort' => 'sender_balance',
            'type' => 'float',
        ],
        [
            'name' => 'recipientBalance',
            'text' => 'Баланс вебмастера',
            'sort' => 'recipient_balance',
            'type' => 'float',
        ],
    ];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			['amount', 'required'],
			['amount, sender_balance, recipient_balance', 'numerical'],
			['description', 'length', 'max'=>512],
			['ip', 'length', 'max'=>15],
			['referer', 'length', 'max'=>400],
			['timestamp', 'length', 'max'=>6],
			['block_id, ads_id, recipient_id, sender_id, from, to', 'safe'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [];
	}

    public function behaviors()
    {
        return [
            'dataTable' => [
                'class' => 'application\models\behaviors\DataTablesBehavior',
            ],
        ];
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'amount' => 'Amount',
			'description' => 'Description',
			'ip' => 'Ip',
			'referer' => 'Referer',
			'block_id' => 'Block',
			'ads_id' => 'Ads',
			'id' => 'ID',
			'recipient_id' => 'Recipient',
			'sender_id' => 'Sender',
			'timestamp' => 'Timestamp',
			'from' => 'From',
			'to' => 'To',
			'sender_balance' => 'Sender Balance',
			'recipient_balance' => 'Recipient Balance',
		];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transactions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Transactions[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Transactions
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function getDateTime()
    {
        return $this->timestamp;
    }

    public function getDate()
    {
        return (new \DateTime($this->getDateTime()))->format(\Yii::app()->params['dateFormat']);
    }

    public function setBlockId($id)
    {
        $this->block_id = (int)$id;
    }

    public function getBlockId()
    {
        return $this->block_id;
    }

    public function setAdsId($id)
    {
        $this->ads_id = (int)$id;
    }

    public function getAdsId()
    {
        return $this->ads_id;
    }

    public function setRecipientId($id)
    {
        $this->recipient_id = (int)$id;
    }

    public function getRecipientId()
    {
        return $this->recipient_id;
    }

    public function setSenderId($id)
    {
        $this->sender_id = (int)$id;
    }

    public function getSenderId()
    {
        return $this->sender_id;
    }

    public function getRecipientBalance()
    {
        return $this->recipient_balance;
    }

    public function getSenderBalance()
    {
        return $this->sender_balance;
    }

    public function getAjaxData($params)
    {
        return $this->dataTable->getAjaxData($params, $this->getDefaultAttributes());
    }

    /**
     * @return \stdClass[]
     */
    public function getTableColumns()
    {
        return $this->dataTable->getColumns();
    }

    public function getColumnsArray()
    {
        return self::$_columnNames;
    }

    private function getDefaultAttributes()
    {
        return [
            'from' => self::ADVERTISER_INDEX,
            'to' => self::WEBMASTER_INDEX,
        ];
    }
}
