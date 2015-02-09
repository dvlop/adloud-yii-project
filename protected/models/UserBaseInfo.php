<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "user_base_info".
 *
 * The followings are the available columns in table 'user_base_info':
 * @property string $id
 * @property string $user_id
 * @property string $site_url
 * @property integer $desired_profit
 * @property string $stat_link
 * @property string $stat_login
 * @property string $stat_password
 * @property string $description
 */
class UserBaseInfo extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_base_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, site_url, stat_link, stat_login, stat_password', 'required'),
			array('desired_profit', 'numerical', 'integerOnly'=>true),
			array('site_url, stat_link, description', 'length', 'max'=>512),
			array('stat_login', 'length', 'max'=>56),
			array('stat_password', 'length', 'max'=>112),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, site_url, desired_profit, stat_link, stat_login, stat_password, description', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'site_url' => 'Site Url',
			'desired_profit' => 'Desired Profit',
			'stat_link' => 'Stat Link',
			'stat_login' => 'Stat Login',
			'stat_password' => 'Stat Password',
			'description' => 'Description',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('site_url',$this->site_url,true);
		$criteria->compare('desired_profit',$this->desired_profit);
		$criteria->compare('stat_link',$this->stat_link,true);
		$criteria->compare('stat_login',$this->stat_login,true);
		$criteria->compare('stat_password',$this->stat_password,true);
		$criteria->compare('description',$this->description,true);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserBaseInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
