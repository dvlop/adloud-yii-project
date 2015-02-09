<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "country".
 *
 * The followings are the available columns in table 'country':
 * @property string $id
 * @property string $cty_id
 * @property string $name
 * @property integer $active
 * @property string $code
 * @property \application\models\Region[] $regions
 */
class Country extends BaseModel
{
    private static $_allRegions;

    public $checked = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('active', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('code', 'length', 'max'=>4),
			array('cty_id', 'safe'),
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
            'regions' => [self::HAS_MANY, '\application\models\Region', 'country_id'],
        ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cty_id' => 'Cty',
			'name' => 'Name',
			'active' => 'Active',
			'code' => 'Code',
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
		$criteria->compare('cty_id',$this->cty_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('code',$this->code,true);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return \application\models\Country the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Country[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Country
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    /**
     * @return \application\models\Region[]
     */
    public static function getAllRegions()
    {
        if(self::$_allRegions === null){
            self::$_allRegions = [];
            foreach(Region::model()->findAll() as $region){
                self::$_allRegions[$region->getCountryId()][] = $region;
            }
        }

        return self::$_allRegions;
    }
}
