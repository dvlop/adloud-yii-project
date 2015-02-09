<?php

namespace application\models;

use application\components\BaseModel;

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property string $id
 * @property string $user_id
 * @property string $date
 * @property boolean $is_new
 * @property string $text
 * @property string $type
 * @property boolean $is_shown
 *
 * The followings are the available model relations:
 * @property \application\models\Users $user
 */
class Notification extends BaseModel
{
    const TYPE_SYSTEM = 'system';
    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR = 'error';
    const TYPE_PAYMENT_SUCCESS = 'payment-success';
    const TYPE_PAYMENT_REJECTED = 'payment-rejected';
    const TYPE_ADS_MODERATED = 'teaser-moderated';
    const TYPE_ADS_REJECTED = 'teaser-declined';
    const TYPE_SITE_MODERATED = 'site-moderated';
    const TYPE_SITE_REJECTED = 'site-declined';
    const TYPE_LOW_BALANCE = 'low-balance';
    const TYPE_NULL_BALANCE = 'null-balance';
    const TYPE_PROFILE = 'profile';

    public static function getTypeData(){
        return [
            self::TYPE_SYSTEM => [
                'text' => '',
                'icon' => 'moder.png'
            ],
            self::TYPE_SUCCESS => [
                'text' => '',
                'icon' => 'ok.png'
            ],
            self::TYPE_ERROR => [
                'text' => '',
                'icon' => 'bad.png'
            ],
            self::TYPE_PAYMENT_SUCCESS => [
                'text' => 'Выплата успешно произведена',
                'icon' => 'payment.png'
            ],
            self::TYPE_PAYMENT_REJECTED => [
                'text' => 'Запрос на выплату был отклонен',
                'icon' => 'teaser-banned.png'
            ],
            self::TYPE_ADS_MODERATED => [
                'text' => 'Ваш тизер успешно прошел модерацию',
                'icon' => 'moder.png'
            ],
            self::TYPE_ADS_REJECTED => [
                'text' => 'Ваш тизер был отклонен модератором',
                'icon' => 'teaser-banned.png'
            ],
            self::TYPE_SITE_MODERATED => [
                'text' => 'Ваш сайт успешно прошел модерацию',
                'icon' => 'moder.png'
            ],
            self::TYPE_SITE_REJECTED => [
                'text' => 'Ваш сайт был отклонен модератором',
                'icon' => 'teaser-banned.png'
            ],
            self::TYPE_LOW_BALANCE => [
                'text' => 'Ваш баланс составляет менее 5$. Пополните ваш счёт или ваши кампании будут остановлены.',
                'icon' => 'bad.png'
            ],
            self::TYPE_NULL_BALANCE => [
                'text' => 'Ваши кампании остановлены. Пожалуйста, пополните ваш счёт.',
                'icon' => 'bad.png'
            ],
            self::TYPE_PROFILE => [
                'text' => '',
                'icon' => 'profile.png'
            ],
        ];
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, date, text, type', 'required'),
			array('is_new, is_shown', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, date, is_new, text, type, is_shown', 'safe', 'on'=>'search'),
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
			'date' => 'Date',
			'is_new' => 'Is New',
            'text' => 'Text',
            'type' => 'Type',
            'is_shown' => 'Is shown'
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('is_new',$this->is_new);
        $criteria->compare('text',$this->text, true);
        $criteria->compare('type',$this->type, true);
        $criteria->compare('is_shown',$this->is_shown);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function create($params){
        $notification = new self;
        if(!isset($params['date']))
            $params['date'] = 'now';
        $notification->setAttributes($params);
        return $notification->save();
    }
}
