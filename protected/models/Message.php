<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "message".
 *
 * The followings are the available columns in table 'message':
 * @property string $id
 * @property string $content
 * @property string $date
 * @property string $ticket_id
 * @property string $user_id
 * @property integer $status
 * @property boolean $is_admin
 *
 * @property integer $ticketId
 * @property integer $userId
 * @property bool $isAdmin
 *
 * The followings are the available model relations:
 * @property Ticket $ticket
 * @property Users $user
 */
class Message extends BaseModel
{
    const STATUS_OPENED = 1;
    const STATUS_CLOSED = 0;
    const STATUS_ALL = 1000;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, ticket_id, user_id', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('is_admin', 'safe'),
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
			'ticket' => array(self::BELONGS_TO, 'Ticket', 'ticket_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'Content',
			'date' => 'Date',
			'ticket_id' => 'Ticket',
			'user_id' => 'User',
			'status' => 'Status',
			'is_admin' => 'Is Admin',
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('ticket_id',$this->ticket_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_admin',$this->is_admin);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Message the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave()
    {
        if($this->getIsNewRecord()){
            $this->status = self::STATUS_OPENED;

            if($this->date === null){
                $this->date = (new \DateTime())->format(\Yii::app()->params['dateTimeFormat']);
            }
        }

        return parent::beforeSave();
    }

    public function setTicketId($id)
    {
        $this->ticket_id = (int)$id;
    }

    public function getTicketId()
    {
        return $this->ticket_id;
    }

    public function setUserId($id)
    {
        $this->user_id = (int)$id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setIsAdmin($bool)
    {
        $this->is_admin = (bool)$bool;
    }

    public function getIsAdmin()
    {
        return $this->is_admin;
    }
}
