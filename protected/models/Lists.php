<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "lists".
 *
 * The followings are the available columns in table 'lists':
 * @property string $id
 * @property string $name
 * @property integer $type
 * @property string $user_id
 * @property string $sites
 * @property string $campaigns
 * @property string $description
 */
class Lists extends BaseModel
{
    const WHITE_LIST = 1;
    const BLACK_LIST = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, user_id, sites, campaigns', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>240),
			array('description', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, user_id, sites, campaigns, description', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'type' => 'Type',
			'user_id' => 'User',
			'sites' => 'Sites',
			'campaigns' => 'Campaigns',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('sites',$this->sites,true);
		$criteria->compare('campaigns',$this->campaigns,true);
		$criteria->compare('description',$this->description,true);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return \application\models\Lists the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Lists[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Lists
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    /**
     * @return integer[]
     */
    public function getCampaignsIds()
    {
        return $this->parseDbArray($this->campaigns);
    }

    /**
     * @return integer[]
     */
    public function getSitesIds()
    {
        return $this->parseDbArray($this->sites);
    }

    public function getSitest()
    {
        return $this->sites;
    }
}
