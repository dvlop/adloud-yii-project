<?php

namespace application\models;

use application\components\BaseModel;
use application\models\Message;

/**
 * This is the model class for table "ticket".
 *
 * The followings are the available columns in table 'ticket':
 * @property string $id
 * @property string $name
 * @property string $date
 * @property string $category_id
 * @property string $user_id
 * @property integer $status
 *
 * @property integer $userId
 *
 * The followings are the available model relations:
 * @property \application\models\TicketCategory $category
 * @property \application\models\Users $user
 * @property \application\models\Message[] $messages
 */
class Ticket extends BaseModel
{
    const STATUS_OPENED = 1;
    const STATUS_CLOSED = 0;
    const STATUS_ALL = 1000;

    const LINK_OPENED = 'opened';
    const LINK_CLOSED = 'closed';
    const LINK_ALL = 'all';

    private static $statussesNames = [
        self::STATUS_OPENED => 'Открыт',
        self::STATUS_CLOSED => 'Закрыт',
        self::STATUS_ALL => 'Все'
    ];

    private static $linksStatussesNames = [
        self::LINK_OPENED => 'Открытые',
        self::LINK_CLOSED => 'Закрытые',
        self::LINK_ALL => 'Все'
    ];

    private $_statusses;

    /**
     * @var \application\models\Message
     */
    private $_message;

    private $_isNew = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ticket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, category_id, user_id', 'required'),
			array('status, category_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, '\application\models\TicketCategory', 'category_id'),
			'user' => array(self::BELONGS_TO, '\application\models\Users', 'user_id'),
			'messages' => array(self::HAS_MANY, '\application\models\Message', 'ticket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'date' => 'Date',
			'category_id' => 'Category',
			'user_id' => 'User',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return \CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new \CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('status',$this->status);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ticket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave()
    {
        if($this->getIsNewRecord()){
            $this->_isNew = true;

            $this->status = self::STATUS_OPENED;

            if($this->date === null){
                $this->date = (new \DateTime())->format(\Yii::app()->params['dateTimeFormat']);
            }
            if($this->user_id === null){
                $this->addError('user_id', 'Не казан ID пользователя');
                return false;
            }
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if($this->_isNew){
            if($this->_message){
                $this->_message->userId = $this->getUserId();
                $this->_message->ticketId = $this->id;
                $this->_message->isAdmin = true;
                $this->_message->date = $this->date;

                if(!$this->_message->save()){
                    $this->errors = $this->_message->errors;
                }
            }
        }

        parent::afterSave();
    }

    /**
     * @return boolean isNewMessage
     */
    public function isNewMessage(){
        return (boolean)Message::model()->findByAttributes([
            'ticket_id' => $this->id,
            'status' => 1,
            'is_admin' => true
        ]);
    }

    /**
     * @return boolean isNewMessageForAdmin
     */
    public function isNewMessageForAdmin(){
        return (boolean)Message::model()->findByAttributes([
            'ticket_id' => $this->id,
            'status' => 1,
            'is_admin' => false
        ]);
    }

    public function getIsOpened()
    {
        return $this->status == self::STATUS_OPENED;
    }

    /**
     * @return \stdClass[]
     */
    public function getStatusSelectors()
    {
        if($this->_statusses === null){
            $this->_statusses = [];

            foreach(self::$linksStatussesNames as $value => $name){
                $this->_statusses[] = (object)[
                    'value' => $value,
                    'name' => $name,
                    'checked' => $this->status == $this->getStatusByLinkName($value),
                ];
            }
        }

        return $this->_statusses;
    }

    public function getStatusByLinkName($name)
    {
        switch($name){
            case self::LINK_OPENED:
                $status = self::STATUS_OPENED;
                break;
            case self::LINK_CLOSED:
                $status = self::STATUS_CLOSED;
                break;
            case self::LINK_ALL:
                $status = self::STATUS_ALL;
                break;
            default:
                $status = self::STATUS_OPENED;
        }

        return $status;
    }

    public function getLinkNameByStatus($status)
    {
        switch($status){
            case self::STATUS_OPENED:
                $name = self::LINK_OPENED;
                break;
            case self::STATUS_CLOSED:
                $name = self::LINK_CLOSED;
                break;
            case self::STATUS_ALL:
                $name = self::LINK_ALL;
                break;
            default:
                $name = self::LINK_OPENED;
        }

        return $name;
    }

    public function setStatus($status)
    {
        if(is_string($status) && strlen($status) > 1 && $status !== (string)self::STATUS_ALL)
            $status = $this->getStatusByLinkName($status);

        if(!isset(self::$statussesNames[$status]))
            $status = self::STATUS_OPENED;

        $this->status = $status;
    }

    public function setCategory($id)
    {
        $this->category_id = (int)$id;
    }

    public function setUserId($id)
    {
        $this->user_id = (int)$id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setText($text)
    {
        $this->_message = new Message();
        $this->_message->content = htmlspecialchars($text);
    }
}
