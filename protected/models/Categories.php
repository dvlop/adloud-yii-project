<?php

namespace application\models;

use application\components\BaseModel;
use application\models\Ads;
use core\RedisIO;
use models\Site;

/**
 * This is the model class for table "categories".
 *
 * The followings are the available columns in table 'categories':
 * @property string $description
 * @property integer $active
 * @property double $min_click_price
 * @property integer $id
 * @property string $name
 */
class Categories extends BaseModel
{
    public $isChecked = false;
    public $checked = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active', 'numerical', 'integerOnly'=>true),
			array('min_click_price', 'numerical'),
			array('description', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('description, active, min_click_price, id', 'safe', 'on'=>'search'),
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
			'description' => 'Description',
			'active' => 'Active',
			'min_click_price' => 'Min Click Price',
			'id' => 'ID',
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

		$criteria->compare('description',$this->description,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('min_click_price',$this->min_click_price);
		$criteria->compare('id',$this->id);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return \application\models\Categories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param string $condition
     * @param array $params
     * @return \application\models\Categories[]
     */
    public function findAll($condition='', $params=array())
    {
        return parent::findAll($condition, $params);
    }

    /**
     * @param mixed $id
     * @param string $condition
     * @param array $params
     * @return \application\models\Categories
     */
    public function findByPk($id, $condition='', $params=array())
    {
        return parent::findByPk($id, $condition, $params);
    }

    public function getName()
    {
        return $this->description;
    }

    public function getMinClickPrice()
    {
        return $this->min_click_price;
    }

    public function getSites(){
        return Sites::model()->findAllByAttributes(['category' => $this->id]);
    }

    private function getCampaigns(){
        return Campaign::model()->findAllBySql('SELECT id FROM campaign WHERE :subject_id = ANY(subject)', [':subject_id' => $this->id]);
    }

    public function getCategoryTotalTraffic(){
        $result = [
            'shows' => 0,
            'clicks' => 0
        ];

        foreach($this->getCampaigns() as $camp){
            $ads = Ads::model()->findAllByAttributes(['campaign_id' => $camp->id]);

            foreach($ads as $ad){
                $result['shows'] += RedisIO::get("ads-shows:{$ad->id}") ? RedisIO::get("ads-shows:{$ad->id}") : ($ad->shows ? $ad->shows : 0);
                $result['clicks'] += RedisIO::get("ads-clicks:{$ad->id}") ? RedisIO::get("ads-clicks:{$ad->id}") : ($ad->clicks ? $ad->clicks : 0);
            }
        }

        return $result;
    }

    public function getCategoryTotalTrafficBySite(){
        $result = [
            'shows' => 0,
            'clicks' => 0
        ];

        foreach($this->getSites() as $site){
            $blocks = Blocks::model()->findAllByAttributes(['site_id' => $site->id]);

            foreach($blocks as $block){
                $result['shows'] += RedisIO::get("block-shows:{$block->id}") ? RedisIO::get("block-shows:{$block->id}") : ($block->shows ? $block->shows : 0);
                $result['clicks'] += RedisIO::get("block-clicks:{$block->id}") ? RedisIO::get("block-clicks:{$block->id}") : ($block->clicks ? $block->clicks : 0);
            }
        }

        return $result;
    }

    public function getCategoryBeforeTraffic($startDate = null, $endDate = null){
        $campaigns_id = [];
        $result = [
            'id' => $this->id,
            'shows' => 0,
            'clicks' => 0
        ];

        foreach($this->getCampaigns() as $camp){
            $campaigns_id[] = $camp->id;
        }

        $campaigns = '{'.implode(',',$campaigns_id).'}';

        $sql = 'SELECT shows,clicks FROM advertiser_stats WHERE campaign_id = ANY(:campaigns)';
        $params = [':campaigns' => $campaigns];

        if($startDate && $endDate){
            $sql .= ' AND date >= :startDate AND date <= :endDate';
            $params = array_merge($params, [':startDate' => $startDate, ':endDate' => $endDate]);
        }
        $stats = AdvertiserStats::model()->findAllBySql($sql,$params);

        foreach($stats as $stat){
            $result['shows'] += $stat->shows;
            $result['clicks'] += $stat->clicks;
        }

        return $result;
    }

    public function getCategoryBeforeTrafficBySite($startDate = null, $endDate = null){
        $sites_id = [];
        $result = [
            'id' => $this->id,
            'shows' => 0,
            'clicks' => 0
        ];

        foreach($this->getSites() as $site){
            $sites_id[] = $site->id;
        }

        $sites = '{'.implode(',',$sites_id).'}';

        $sql = 'SELECT shows,clicks FROM webmaster_stats WHERE site_id = ANY(:sites)';
        $params = [':sites' => $sites];

        if($startDate && $endDate){
            $sql .= ' AND date >= :startDate AND date <= :endDate';
            $params = array_merge($params, [':startDate' => $startDate, ':endDate' => $endDate]);
        }
        $stats = WebmasterStats::model()->findAllBySql($sql,$params);

        foreach($stats as $stat){
            $result['shows'] += $stat->shows;
            $result['clicks'] += $stat->clicks;
        }

        return $result;
    }

    public function getCategoryTrafficByDate($startDate = null, $endDate = null){
        $today = (new \DateTime('now'))->format(\Yii::app()->params->dateFormat);
        $campaigns_id = [];
        $result = [];

        foreach($this->getCampaigns() as $camp){
            $campaigns_id[] = $camp->id;
        }

        $campaigns = '{'.implode(',',$campaigns_id).'}';

        $sql = 'SELECT sum(shows) as "shows",sum(clicks) as "clicks","date"
        FROM advertiser_stats
        WHERE campaign_id = ANY(:campaigns)
        AND date >= :startDate AND date <= :endDate
        GROUP BY "date"
        ORDER BY "date" ASC';
        $params = [':campaigns' => $campaigns,':startDate' => $startDate, ':endDate' => $endDate];

        $stats = AdvertiserStats::model()->findAllBySql($sql,$params);

        foreach($stats as $stat){
            $result[$stat->date] = [
                'id' => $this->id,
                'shows' => $stat->shows,
                'clicks' => $stat->clicks
            ];
        }

        if($endDate == $today){
            $todayData = $this->getCategoryTodayTraffic();

            $result[$today] = [
                'id' => $this->id,
                'shows' => $todayData['shows'],
                'clicks' => $todayData['clicks']
            ];
        }

        return $result;
    }

    public function getCategoryTrafficBySites($startDate = null, $endDate = null){
        $sites = $this->getSites();
        $result = [];

        foreach($sites as $site){
            $result[$site->url] = $site->getSitePeriodTraffic($startDate, $endDate);
        }

        return $result;
    }

    public function getCategoryTodayTraffic(){
        $total = $this->getCategoryTotalTraffic();
        $before = $this->getCategoryBeforeTraffic();

        return [
            'id' => $this->id,
            'shows' => ($total['shows'] - $before['shows']),
            'clicks' => ($total['clicks'] - $before['clicks'])
        ];
    }

    public function getCategoryTodayTrafficBySite(){
        $total = $this->getCategoryTotalTrafficBySite();
        $before = $this->getCategoryBeforeTrafficBySite();

        return [
            'id' => $this->id,
            'shows' => ($total['shows'] - $before['shows']),
            'clicks' => ($total['clicks'] - $before['clicks'])
        ];
    }

    public function getCategoryPeriodTraffic($startDate, $endDate, $by = null){
        $today = (new \DateTime('now'))->format(\Yii::app()->params->dateFormat);
        $periodTraffic = [
            'id' => $this->id,
            'shows' => 0,
            'clicks' => 0
        ];

        $methods = [
            'today' => $by == 'sites' ? 'getCategoryTodayTrafficBySite' : 'getCategoryTodayTraffic',
            'before' => $by == 'sites' ? 'getCategoryBeforeTrafficBySite' : 'getCategoryBeforeTraffic'
        ];

        if($endDate == $today){
            $periodTraffic = $this->$methods['today']();
        }

        $beforeTraffic = $this->$methods['before']($startDate, $endDate);

        $periodTraffic['shows'] += $beforeTraffic['shows'];
        $periodTraffic['clicks'] += $beforeTraffic['clicks'];

        return $periodTraffic;
    }

    public function getPeriodData($startDate,$endDate){
        $systemCom = 0.2;
        $webmasterGet = 1 - $systemCom;

        $traffic = $this->getCategoryPeriodTraffic($startDate,$endDate,'sites');
        $income = round($this->getPeriodIncome($startDate,$endDate),2);
        $advertiserPaid = round($income/$webmasterGet,2);

        return [
            'name' => $this->name,
            'sites' => count($this->getSites()),
            'shows' => $traffic['shows'],
            'clicks' => $traffic['clicks'],
            'ctr' => $traffic['shows'] ? round(($traffic['clicks']/$traffic['shows'])*100,2) : 0,
            'income' => $income,
            'averageCost' => $traffic['clicks'] ? round($advertiserPaid/$traffic['clicks'],2) : 0,
            'advertisersPaid' => $advertiserPaid,
            'cpm' => $traffic['shows'] ? round(($income/$traffic['shows'])*1000,2) : 0,
            'systemIncome' => round($income/($webmasterGet/$systemCom),2)
        ];
    }

    public function getTodayIncome(){
        return $this->getTotalIncome() - $this->getBeforeIncome();
    }

    public function getBeforeIncome($startDate = null, $endDate = null){
        $sites_id = [];
        $income = 0;

        foreach($this->getSites() as $site){
            $sites_id[] = $site->id;
        }

        $sites = '{'.implode(',',$sites_id).'}';

        $sql = 'SELECT costs FROM webmaster_stats WHERE site_id = ANY(:sites)';
        $params = [':sites' => $sites];

        if($startDate && $endDate){
            $sql .= ' AND date >= :startDate AND date <= :endDate';
            $params = array_merge($params, [':startDate' => $startDate, ':endDate' => $endDate]);
        }
        $stats = WebmasterStats::model()->findAllBySql($sql,$params);

        foreach($stats as $stat){
            $income += $stat->costs;
        }

        return $income;
    }

    public function getTotalIncome(){
        $income = 0;

        foreach($this->getSites() as $site){
            $blocks = Blocks::model()->findAllByAttributes([
                'site_id' => $site->id
            ]);
            foreach($blocks as $block){
                $income += RedisIO::get("block-income:{$block->id}") ? RedisIO::get("block-income:{$block->id}") : 0;
            }
        }

        return $income;
    }

    public function getPeriodIncome($startDate,$endDate){
        $today = (new \DateTime('now'))->format(\Yii::app()->params->dateFormat);
        $periodIncome = 0;

        if($endDate == $today){
            $periodIncome = $this->getTodayIncome();
        }
        $beforeIncome = $this->getBeforeIncome($startDate, $endDate);
        $periodIncome += $beforeIncome;

        return $periodIncome;
    }
}
