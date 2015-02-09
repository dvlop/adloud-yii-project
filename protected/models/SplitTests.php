<?php
namespace application\models;
use application\components\BaseModel;
/**
 * This is the model class for table "split_tests".
 *
 * The followings are the available columns in table 'split_tests':
 * @property integer $id
 * @property string $name
 * @property string $start_date
 * @property string $stop_date
 * @property string $format
 * @property string $block_id
 * @property string $results
 * @property integer $state
 *
 * The followings are the available model relations:
 * @property Blocks $block
 */
class SplitTests extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'split_tests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('format', 'length', 'max'=>32),
			array('start_date, stop_date, block_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name,start_date, stop_date, format, block_id, results', 'safe', 'on'=>'search'),
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
			'block' => array(self::BELONGS_TO, 'Blocks', 'block_id'),
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
			'start_date' => 'Start Date',
			'stop_date' => 'Stop Date',
			'format' => 'Format',
			'block_id' => 'Block',
			'state' => 'state',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('results',$this->results);
		$criteria->compare('state',$this->state);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('stop_date',$this->stop_date,true);
		$criteria->compare('format',$this->format,true);
		$criteria->compare('block_id',$this->block_id,true);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SplitTests the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getCtr(){
        if(!$this->shows){
            return 0;
        }
        return round($this->clicks/$this->shows, 6);
    }

    public function save($runValidation = true, $attributes = NULL){
        if($runValidation && self::model()->findAllByAttributes(['format' => $this->format, 'state' => 1])){
            throw new \InvalidArgumentException('There are active test for this format');
        }
        return parent::save(true, $attributes);
    }

}
