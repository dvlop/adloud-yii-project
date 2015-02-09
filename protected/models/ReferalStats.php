<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "referal_stats".
 *
 * The followings are the available columns in table 'referal_stats':
 * @property string $id
 * @property string $referer_id
 * @property string $referal_id
 * @property string $date
 * @property double $sum
 * @property boolean $status
 * @property string $moderation
 * @property string $start_date
 *
 * @property \application\models\Users $referal
 */
class ReferalStats extends BaseModel
{
    const STATUS_NEW = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_DENIED = 200;
    const STATUS_ALL = 1000;

    private static $_statuses = [
        self::STATUS_NEW => 'Новая',
        self::STATUS_ACCEPTED => 'Принята',
        self::STATUS_DENIED => 'Отклонена',
    ];

    private static $_webStatuses = [
        self::STATUS_NEW => 'Новые',
        self::STATUS_ACCEPTED => 'Принятые',
        self::STATUS_DENIED => 'Отклонённые',
        self::STATUS_ALL => 'Все',
    ];

    private $_statusesSelector;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'referal_stats';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            ['referer_id, referal_id, date, sum, start_date', 'required'],
            ['sum', 'numerical'],
            ['moderation', 'length', 'max'=>64],
            ['status', 'safe'],
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'referal' => [self::BELONGS_TO, '\application\models\Users', 'referal_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'referer_id' => 'Referer',
            'referal_id' => 'Referal',
            'date' => 'Date',
            'sum' => 'Sum',
            'status' => 'Status',
            'moderation' => 'Moderation',
            'start_date' => 'Start Date',
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ReferalStats the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\ReferalStats[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\ReferalStats
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function setStatus($status)
    {
        $status = intval($status);
        if(!isset(self::$_webStatuses[$status]))
            $status = self::STATUS_NEW;

        $this->status = $status;
    }

    /**
     * @return \stdClass[]
     */
    public function getSelectorStatuses()
    {
        if($this->_statusesSelector === null){
            $this->_statusesSelector = [];

            foreach(self::$_webStatuses as $value => $name){
                $this->_statusesSelector[] = (object)[
                    'value' => $value,
                    'name' => $name,
                    'checked' => $this->status == $value,
                ];
            }
        }

        return $this->_statusesSelector;
    }

    public function setRefererId($id)
    {
        $this->referer_id = (int)$id;
    }

    public function getRefererId()
    {
        return $this->referer_id;
    }

    public function setReferalId($id)
    {
        $this->referal_id = (int)$id;
    }

    public function getReferalId()
    {
        return $this->referal_id;
    }

    public function getStatusName()
    {
        return self::$_statuses[$this->status];
    }

    public function getReferalLink($userId = null)
    {
        if($userId === null)
            $userId = \Yii::app()->user->id;
        return \Yii::app()->getBaseUrl(true).'/?ref='.$userId;
    }

    public function getReferalName()
    {
        return $this->referal ? $this->referal->getFullName() : '';
    }
} 