<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "label".
 *
 * The followings are the available columns in table 'label':
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property integer $user_id
 *
 * @property \application\models\Users $user
 * @property \application\models\Campaign[] $campaigns
 *
 * @property integer $userId
 */
class Label extends BaseModel
{
    private $_campaigns;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'label';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			['name, color', 'required'],
            ['name', 'length', 'max'=>240],
			['color', 'length', 'max'=>520],
            ['name, color', 'filter', 'filter' => 'trim'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [
            'user' => array(self::BELONGS_TO, '\application\models\Users', 'user_id'),
        ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Имя метки',
			'color' => 'Цвет',
		];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Label the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Label[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Label
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function setUserId($id)
    {
        $this->user_id = (int)$id;
    }

    public function getUserId()
    {
        if($this->user_id === null)
            $this->user_id = \Yii::app()->user->id;

        return $this->user_id;
    }

    /**
     * @return \application\models\Campaign[]
     */
    public function getCampaigns()
    {
        if($this->_campaigns === null){
            $this->_campaigns = [];

            foreach(Campaign::model()->findByAttributes(['user_id' => $this->getUserId()]) as $campaign){
                $ids = $campaign->getLabelsId();
                if(is_array($ids) && in_array($this->id, $ids)){
                    $this->_campaigns[] = $campaign;
                }
            }
        }

        return $this->_campaigns;
    }
}
