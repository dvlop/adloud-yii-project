<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "user_agent".
 *
 * The followings are the available columns in table 'user_agent':
 * @property string $id
 * @property string $value
 * @property string $name
 * @property string $type
 * @property string $shows
 * @property string $resolution
 * @property boolean $is_checked
 */
class UserAgent extends BaseModel
{
    public $shows;
    public $checked = false;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_agent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type', 'required'),
			array('value, name', 'length', 'max'=>255),
			array('type, resolution', 'length', 'max'=>64),
			array('is_checked', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, value, name, type, is_checked', 'safe', 'on'=>'search'),
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
			'value' => 'Value',
			'name' => 'Name',
			'type' => 'Type',
            'resolution' => 'Resolution',
			'is_checked' => 'Is Checked',
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
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('name',$this->name,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('resolution',$this->resolution,true);
		$criteria->compare('is_checked',$this->is_checked);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAgent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getList($params){
        $this->setActualConnection();
        $result = $this->findAllByAttributes([
            'type' => $params['type']
        ]);
        $this->setPersistentConnection();

        foreach($result as $key => $res){
            $result[$key]->shows = \core\RedisIO::get("user-agent:{$res->id}") ? \core\RedisIO::get("user-agent:{$res->id}") : 0;
        }

        return $result;
    }

    public function findAllBySql($sql,$params=array()){
        $this->setActualConnection();
        $result = parent::findAllBySql($sql,$params);
        $this->setPersistentConnection();

        return $result;
    }

    public function findAllByAttributes($attributes,$condition='',$params=array()){
        $this->setActualConnection();
        $result = parent::findAllByAttributes($attributes,$condition,$params);
        $this->setPersistentConnection();

        return $result;
    }
}
