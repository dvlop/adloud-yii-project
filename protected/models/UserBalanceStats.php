<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "user_balance_stats".
 *
 * The followings are the available columns in table 'user_balance_stats':
 * @property string $id
 * @property double $income
 * @property double $outcome
 * @property string $date
 * @property string $user_id
 * @property double $balance
 * @property double $ctr
 * @property integer $blocked_ips_count
 * @property string $geo_stats
 * @property string $referer_stats
 * @property double $click_time
 */
class UserBalanceStats extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_balance_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, user_id', 'required'),
			array('blocked_ips_count', 'numerical', 'integerOnly'=>true),
			array('income, outcome, balance, ctr, click_time', 'numerical'),
			array('geo_stats, referer_stats', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, income, outcome, date, user_id, balance, ctr, blocked_ips_count, geo_stats, referer_stats, click_time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'income' => 'Income',
			'outcome' => 'Outcome',
			'date' => 'Date',
			'user_id' => 'User',
			'balance' => 'Balance',
			'ctr' => 'Ctr',
			'blocked_ips_count' => 'Blocked Ips Count',
			'geo_stats' => 'Geo Stats',
			'referer_stats' => 'Referer Stats',
			'click_time' => 'Click Time',
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
		$criteria->compare('income',$this->income);
		$criteria->compare('outcome',$this->outcome);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('balance',$this->balance);
		$criteria->compare('ctr',$this->ctr);
		$criteria->compare('blocked_ips_count',$this->blocked_ips_count);
		$criteria->compare('geo_stats',$this->geo_stats,true);
		$criteria->compare('referer_stats',$this->referer_stats,true);
		$criteria->compare('click_time',$this->click_time);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserBalanceStats the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
