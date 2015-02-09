<?php

namespace application\models;

use application\components\BaseModel;
use models\Ads as Ad;

/**
 * This is the model class for table "advertiser_stats".
 *
 * The followings are the available columns in table 'advertiser_stats':
 * @property string $user_id
 * @property string $date
 * @property string $ads_id
 * @property string $campaign_id
 * @property string $shows
 * @property string $clicks
 * @property double $costs
 * @property integer $id
 * @property string $campaign_description
 *
 * The followings are the available model relations:
 * @property \application\models\Users $user
 */
class AdvertiserStats extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'advertiser_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('costs', 'numerical'),
			array('user_id, date, ads_id, campaign_id, shows, clicks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, date, ads_id, campaign_id, shows, clicks, costs, id', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'date' => 'Date',
			'ads_id' => 'Ads',
			'campaign_id' => 'Campaign',
            'campaign_description' => 'CampaignDescription',
			'shows' => 'Shows',
			'clicks' => 'Clicks',
			'costs' => 'Costs',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('ads_id',$this->ads_id,true);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('shows',$this->shows,true);
		$criteria->compare('clicks',$this->clicks,true);
		$criteria->compare('costs',$this->costs);
		$criteria->compare('id',$this->id);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdvertiserStats the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getExpenses(){
        return round($this->costs, 2);
    }

    public function getPeriodStatsByDate(array $params) {
        $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", date
            FROM "advertiser_stats" "t"
            INNER JOIN "ads" "t2" ON ("t".ads_id = "t2".id)
            WHERE user_id = :userId AND date <= :endDate AND date >= :startDate';
        if($params['status'] == 'archived'){
            $sql .= ' AND "t".ads_id IN(SELECT id FROM ads WHERE campaign_id IN(SELECT id FROM campaign WHERE publish = :status))';
        } else {
            $sql .= ' AND "t".ads_id NOT IN(SELECT id FROM ads WHERE campaign_id IN(SELECT id FROM campaign WHERE publish = :status))';
        }
        $sql .= ' GROUP BY date
                ORDER BY date DESC';

        $archived = Ad::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
        [
            ':endDate' => $params['endDate'],
            ':startDate' => $params['startDate'],
            ':userId' => $this->user_id,
            ':status' => $archived
        ]);

        $result = [];

        foreach($stats as $stat) {
            $result[] = [
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
                'date' => $stat->date
            ];
        }

        if($params['today']){
            array_unshift($result,$this->getTodayStatsByDate($params));
        }

        $result = array_reverse($result);

        return $result;
    }

    public function getPeriodCampaignStatsByDate(array $params) {
        $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", date
                FROM "advertiser_stats" "t"
                INNER JOIN "ads" "t2" ON ("t".ads_id = "t2".id)
                WHERE user_id = :userId AND date <= :endDate AND date >= :startDate AND "t".campaign_id = :campaignId';

        if($params['status'] == 'archived'){
            $sql .= ' AND "t2".status = :status';
        } else {
            $sql .= ' AND "t2".status <> :status';
        }

        $sql .= ' GROUP BY date
        ORDER BY date DESC';

        $archived = Ad::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':campaignId' => $params['campaignId'],
                ':status' => $archived
            ]);

        $result = [];

        foreach($stats as $stat) {
            $result[] = [
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
                'date' => $stat->date
            ];
        }

        if($params['today']){
            array_unshift($result,$this->getTodayStatsByDate($params));
        }

        $result = array_reverse($result);

        return $result;
    }

    public function getPeriodAdsStatsByDate(array $params) {
        $sql = 'SELECT sum(shows) as "shows", sum(clicks) as "clicks", sum(costs) as "costs", date, campaign_id as "campaign_id"
                FROM "advertiser_stats" "t"
                WHERE user_id = :userId AND date <= :endDate AND date >= :startDate AND ads_id = :adsId
                GROUP BY campaign_id, date
                ORDER BY date DESC';

        $stats = $this->findAllBySql($sql,
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':adsId' => $params['adsId'],
            ]);

        $result = [];

        foreach($stats as $stat) {
            $result[] = [
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
                'date' => $stat->date,
                'campaign_id' => $stat->campaign_id
            ];
        }

        if($params['today']){
            array_unshift($result,$this->getTodayStatsByDate($params));
        }

        $result = array_reverse($result);

        return $result;
    }

    public function getPeriodCampaignStats(array $params) {
        $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".campaign_id as "campaign_id", "t".ads_id as "ads_id", json_extract_path_text("t2".content, \'caption\') as "date"
                FROM "advertiser_stats" "t"
                INNER JOIN "ads" "t2" ON ("t".ads_id = "t2".id)
                WHERE "t".user_id = :userId AND date <= :endDate AND date >= :startDate AND "t".campaign_id = :campaignId';
        if($params['status'] == 'archived'){
            $sql .= ' AND "t2".status = :status';
        } else {
            $sql .= ' AND "t2".status <> :status';
        }
        $sql .= ' GROUP BY json_extract_path_text("t2".content, \'caption\'), "t".campaign_id, "t".ads_id';

        $archived = Ad::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':campaignId' => $params['campaignId'],
                ':status' => $archived
            ]);

        $result = [];

        foreach($stats as $stat) {
            $result[$stat->ads_id] = [
                'description' => $stat->date,
                'item_id' => $stat->ads_id,
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
                'ads_id' => $stat->ads_id
            ];
        }

        if($params['today']){
            $params['groupBy'] = 'ads';
            $todayStats = $this->getTodayCampaignStats($params);

            if(!$result){
                return $todayStats;
            }

            foreach($todayStats as $tKey => $tStat){
                $found = false;
                foreach($result as $key => $res){
                    if($key == $tKey){
                        $result[$key]['shows'] += $tStat['shows'];
                        $result[$key]['clicks'] += $tStat['clicks'];
                        $result[$key]['costs'] += $tStat['costs'];
                        $found = true;
                        continue;
                    }
                }
                if (!$found) {
                    $result[$tKey] = [
                        'description' => $tStat['description'],
                        'item_id' => $tStat['item_id'],
                        'shows' => $tStat['shows'],
                        'clicks' => $tStat['clicks'],
                        'costs' => $tStat['costs'],
                    ];
                }
            }
        }

        return $result;
    }

    public function getPeriodStatsByCampaign(array $params) {
        $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t2".description as "date", "t".campaign_id as "campaign_id"
                FROM "advertiser_stats" "t"
                INNER JOIN "campaign" "t2" ON ("t".campaign_id = "t2".id)
                WHERE "t".user_id = :userId AND date <= :endDate AND date >= :startDate';
        if($params['status'] == 'archived'){
            $sql .= ' AND "t2".publish = :status AND "t".ads_id NOT IN(SELECT id FROM ads WHERE campaign_id IN(SELECT id FROM campaign WHERE publish <> :status))';
        } else {
            $sql .= ' AND "t2".publish <> :status AND "t".ads_id NOT IN(SELECT id FROM ads WHERE campaign_id IN(SELECT id FROM campaign WHERE publish = :status))';
        }
        $sql .= ' GROUP BY "t2".description, "t".campaign_id';

        $archived = Ad::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':status' => $archived
            ]);

        $result = [];

        foreach($stats as $stat) {
            $result[$stat->campaign_id] = [
                'description' => $stat->date,
                'item_id' => $stat->campaign_id,
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
            ];
        }

        if($params['today']){
            $params['groupBy'] = 'campaign';
            $todayStats = $this->getTodayCampaignStats($params);

            if(!$result){
                return $todayStats;
            }

            foreach($todayStats as $tKey => $tStat){
                $found = false;
                foreach($result as $key => $res){
                    if($key == $tKey){
                        $result[$key]['shows'] += $tStat['shows'];
                        $result[$key]['clicks'] += $tStat['clicks'];
                        $result[$key]['costs'] += $tStat['costs'];
                        $found = true;
                        continue;
                    }
                }
                if (!$found) {
                    $result[$tKey] = [
                        'description' => $tStat['description'],
                        'item_id' => $tStat['item_id'],
                        'shows' => $tStat['shows'],
                        'clicks' => $tStat['clicks'],
                        'costs' => $tStat['costs'],
                    ];
                }
            }
        }

        return $result;
    }

    public function getTodayStatsByDate(array $params){
        $user = Users::model();
        $user->id = $this->user_id;

        if(!isset($params['layer']))
            $params['layer'] = 'main';

        switch($params['layer']){
            case 'main':
                $method = 'getAds';
                break;
            case 'campaign':
                $method = 'getAdsByCampaign';
                $params['id'] = $params['campaignId'];
                break;
            case 'ads':
                $method = 'getAdsById';
                $params['id'] = $params['adsId'];
                break;
            default:
                $method = 'getAds';
                break;
        }

        $ads = $user->$method($params);

        $today = new \DateTime();
        $today = $today->format(\Yii::app()->params['dateFormat']);

        $stats = [
            'shows' => 0,
            'clicks' => 0,
            'costs' => 0,
            'date' => $today
        ];

        foreach($ads as $ad){
            $stats['shows'] += intval(\core\RedisIO::get("ads-shows:{$ad->id}")) ? intval(\core\RedisIO::get("ads-shows:{$ad->id}")) : $ad->shows;
            $stats['clicks'] += intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) ? intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) : $ad->clicks;
            $stats['costs'] += floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")) ? round(floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")),2) : 0;
        }

        if($params['layer'] == 'ads') {
            $beforeStats = $this->findAllBySql(
                'SELECT sum(shows) as "shows", sum(clicks) as "clicks", sum(costs) as "costs", ads_id as "ads_id"
                    FROM "advertiser_stats" "t"
                    WHERE user_id = :userId AND ads_id = :adsId
                    GROUP BY ads_id',
                [
                    ':userId' => $this->user_id,
                    ':adsId' => $params['id'],
                ]);
        } elseif($params['layer'] == 'campaign') {
            $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".ads_id as "ads_id"
                    FROM "advertiser_stats" "t"
                    INNER JOIN "ads" "t2" ON ("t".ads_id = "t2".id)
                    WHERE user_id = :userId AND "t".campaign_id = :campaignId';
            if($params['status'] == 'archived'){
                $sql .= ' AND "t2".status = :status';
            } else {
                $sql .= ' AND "t2".status <> :status';
            }
            $sql .= ' GROUP BY "t".ads_id';

            $archive = Ad::STATUS_ARCHIVED;

            $beforeStats = $this->findAllBySql($sql,
                [
                    ':userId' => $this->user_id,
                    ':campaignId' => $params['campaignId'],
                    ':status' => $archive
                ]);
        } else {
            $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".ads_id as "ads_id"
                    FROM "advertiser_stats" "t"
                    INNER JOIN "ads" "t2" ON ("t".ads_id = "t2".id)
                    WHERE user_id = :userId';
            if($params['status'] == 'archived'){
                $sql .= ' AND "t2".status = :status';
            } else {
                $sql .= ' AND "t2".status <> :status';
            }
            $sql .= ' GROUP BY "t".ads_id';

            $archive = Ad::STATUS_ARCHIVED;

            $beforeStats = $this->findAllBySql($sql,
            [
                ':userId' => $this->user_id,
                ':status' => $archive
            ]);
        }

        foreach($beforeStats as $bStat){
            $stats['shows'] -= $bStat->shows;
            $stats['clicks'] -= $bStat->clicks;
            $stats['costs'] -= $bStat->getExpenses();
        }

        return $stats;
    }

    public function getTodayCampaignStats(array $params){
        $user = Users::model();
        $user->id = $this->user_id;

        if(!isset($params['layer']))
            $params['layer'] = 'main';

        switch($params['layer']){
            case 'main':
                $method = 'getAds';
                break;
            case 'campaign':
                $method = 'getAdsByCampaign';
                $params['id'] = $params['campaignId'];
                break;
            case 'ads':
                $method = 'getAdsById';
                $params['id'] = $params['adsId'];
                break;
            default:
                $method = 'getAds';
                break;
        }

        $ads = $user->$method($params);

        $today = new \DateTime();
        $today = $today->format(\Yii::app()->params['dateFormat']);

        $stats = [];

        if($params['groupBy'] == 'campaign'){

                foreach($ads as $ad){
                    if(isset($stats[$ad->campaign_id])){
                        $stats[$ad->campaign_id]['shows'] += intval(\core\RedisIO::get("ads-shows:{$ad->id}")) ? intval(\core\RedisIO::get("ads-shows:{$ad->id}")) : $ad->shows;
                        $stats[$ad->campaign_id]['clicks'] += intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) ? intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) : $ad->clicks;
                        $stats[$ad->campaign_id]['costs'] += floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")) ? round(floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")),2) : 0;
                    } else {
                        $stats[$ad->campaign_id] = [
                            'shows' => intval(\core\RedisIO::get("ads-shows:{$ad->id}")) ? intval(\core\RedisIO::get("ads-shows:{$ad->id}")) : $ad->shows,
                            'clicks' => intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) ? intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) : $ad->clicks,
                            'costs' => floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")) ? round(floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")),2) : 0,
                            'description' => $ad->campaign->description,
                            'item_id' => $ad->campaign_id
                        ];
                    }
                }

                $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".campaign_id as "campaign_id", "t".ads_id as "ads_id"
                            FROM "advertiser_stats" "t"
                            INNER JOIN "ads" "t2" ON ("t".ads_id = "t2".id)
                            WHERE user_id = :userId';
                if($params['status'] == 'archived'){
                    $sql .= ' AND "t2".status = :status';
                } else {
                    $sql .= ' AND "t2".status <> :status';
                }
                $sql .= ' GROUP BY "t".campaign_id, "t".ads_id';

                $archive = Ad::STATUS_ARCHIVED;

                $beforeStats = $this->findAllBySql($sql,
                    [
                        ':userId' => $this->user_id,
                        ':status' => $archive
                    ]);

                foreach($stats as $key => $stat){
                    foreach($beforeStats as $bStat){
                        if($key == $bStat->campaign_id){
                            $stats[$key]['shows'] -= $bStat->shows;
                            $stats[$key]['clicks'] -= $bStat->clicks;
                            $stats[$key]['costs'] -= $bStat->getExpenses();
                        }
                    }
                }

        } elseif($params['groupBy'] == 'ads'){

            foreach($ads as $ad){
                if(isset($stats[$ad->id])){
                    $stats[$ad->id]['shows'] += intval(\core\RedisIO::get("ads-shows:{$ad->id}")) ? intval(\core\RedisIO::get("ads-shows:{$ad->id}")) : $ad->shows;
                    $stats[$ad->id]['clicks'] += intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) ? intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) : $ad->clicks;
                    $stats[$ad->id]['costs'] += floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")) ? round(floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")),2) : 0;
                } else {
                    $stats[$ad->id] = [
                        'shows' => intval(\core\RedisIO::get("ads-shows:{$ad->id}")) ? intval(\core\RedisIO::get("ads-shows:{$ad->id}")) : $ad->shows,
                        'clicks' => intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) ? intval(\core\RedisIO::get("ads-clicks:{$ad->id}")) : $ad->clicks,
                        'costs' => floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")) ? round(floatval(\core\RedisIO::get("ads-expenses:{$ad->id}")),2) : 0,
                        'description' => json_decode($ad->content)->caption,
                        'item_id' => $ad->id
                    ];
                }
            }

            $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".ads_id as "ads_id"
                    FROM "advertiser_stats" "t"
                    INNER JOIN "ads" "t2" ON ("t".ads_id = "t2".id)
                    WHERE user_id = :userId AND "t".campaign_id = :campaignId';
            if($params['status'] == 'archived'){
                $sql .= ' AND "t2".status = :status';
            } else {
                $sql .= ' AND "t2".status <> :status';
            }
            $sql .= ' GROUP BY "t".ads_id';

            $archive = Ad::STATUS_ARCHIVED;

            $beforeStats = $this->findAllBySql($sql,
                [
                    ':userId' => $this->user_id,
                    ':campaignId' => $params['campaignId'],
                    ':status' => $archive
                ]);

            foreach($stats as $key => $stat){
                foreach($beforeStats as $bStat){
                    if($key == $bStat->ads_id){
                        $stats[$key]['shows'] -= $bStat->shows;
                        $stats[$key]['clicks'] -= $bStat->clicks;
                        $stats[$key]['costs'] -= $bStat->getExpenses();
                    }
                }
            }

        }

        return $stats;
    }

    public function getDetailedDateStats(&$dateStats, $params){
        $sql = 'SELECT date as "date", sum(shows) as "shows", sum(clicks) as "clicks", sum(costs) as "costs", "t2".description as "campaign_id"
                FROM "advertiser_stats" "t"
                INNER JOIN "campaign" "t2" ON ("t".campaign_id = "t2".id)
                WHERE "t".user_id = :userId';

        if($params['status'] == 'archived'){
            $sql .= ' AND "t".ads_id NOT IN(SELECT id FROM ads WHERE campaign_id IN(SELECT id FROM campaign WHERE publish <> :status))';
        } else {
            $sql .= ' AND "t".ads_id NOT IN(SELECT id FROM ads WHERE campaign_id IN(SELECT id FROM campaign WHERE publish = :status))';
        }

        $sql .= ' GROUP BY description, date';

        $archive = Ad::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
        [
            ':userId' => $this->user_id,
            ':status' => $archive,
        ]);

        $today = new \DateTime();
        $today = $today->format(\Yii::app()->params['dateFormat']);

        foreach($dateStats as $key => $date){
            foreach($stats as $stat){
                if($date['date'] == $stat->date){
                    $dateStats[$key]['details'][] = [
                        'description' => $stat->campaign_id,
                        'shows' => $stat->shows,
                        'clicks' => $stat->clicks,
                        'costs' => $stat->getExpenses(),
                    ];
                }
            }
            if($date['date'] == $today && !isset($date[$today]['details'])){
                $dateStats[$key]['details'][] = [
                    'description' => \Yii::t('advertiser_stats','Для получения детальной статистики за сегодня - включите отображение по кампаниям')
                ];
            }
        }
    }
}
