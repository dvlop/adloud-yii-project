<?php

namespace application\models;

use application\components\BaseModel;
use core\RedisIO;
use templates\TemplateManager;

/**
 * This is the model class for table "target_list".
 *
 * The followings are the available columns in table 'target_list':
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $category_id
 * @property integer $status
 * @property integer $users
 *
 * The followings are the available model relations:
 * @property \application\models\TargetCategory $category
 * @property Users $user
 */
class TargetList extends BaseModel
{
    public $shows = 0;
    public $checked = false;

    const LIST_STATUS_DELETED = 0;
    const LIST_STATUS_ACTIVE = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'target_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name, category_id', 'required'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, name, category_id, status, users', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, '\application\models\TargetCategory', 'category_id'),
			'user' => array(self::BELONGS_TO, '\application\models\Users', 'user_id'),
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
			'name' => 'Name',
			'category_id' => 'Category',
            'status' => 'Status',
            'users' => 'Users'
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('category_id',$this->category_id,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('users',$this->users,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TargetList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function create($params, $runValid = false){
        $model = new self;
        $model->setAttributes($params);
        $model->save($runValid);
    }

    public function getInsertRetargetingCode(){
        return TemplateManager::renderInsertRetargetingCode($this);
    }

    public function getCategoriesList(){
        return TargetCategory::model()->findAll();
    }

    public function remove(){
        try {
            $this->status = self::LIST_STATUS_DELETED;
            $this->users = RedisIO::get("target-users:{$this->id}") ? RedisIO::get("target-users:{$this->id}") : 0;
            RedisIO::delete("target-users:{$this->id}");

            $this->update(['status','users']);
        } catch(\Exception $e){
            return false;
        }

        return true;
    }
}
