<?php

namespace application\models;

use application\components\BaseModel;
use models\Block;

/**
 * This is the model class for table "webmaster_stats".
 *
 * The followings are the available columns in table 'webmaster_stats':
 * @property string $user_id
 * @property string $date
 * @property string $block_id
 * @property string $site_id
 * @property string $shows
 * @property string $clicks
 * @property double $costs
 * @property integer $id
 *
 * The followings are the available model relations:
 * @property \application\models\Users $user
 * @property \application\models\Sites $site
 * @property \application\models\Blocks $block
 */
class WebmasterStats extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'webmaster_stats';
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
			array('user_id, date, block_id, site_id, shows, clicks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, date, block_id, site_id, shows, clicks, costs, id', 'safe', 'on'=>'search'),
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
			'site' => array(self::BELONGS_TO, '\application\models\Sites', 'site_id'),
			'block' => array(self::BELONGS_TO, '\application\models\Blocks', 'block_id'),
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
			'block_id' => 'Block',
			'site_id' => 'Site',
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
		$criteria->compare('block_id',$this->block_id,true);
		$criteria->compare('site_id',$this->site_id,true);
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
	 * @return WebmasterStats the static model class
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
                FROM "webmaster_stats" "t"
                INNER JOIN "blocks" "t2" ON ("t".block_id = "t2".id)
                WHERE user_id = :userId AND date <= :endDate AND date >= :startDate';
        if($params['status'] == 'archived'){
            $sql .= ' AND "t".block_id IN(SELECT id FROM blocks WHERE site_id IN(SELECT id FROM sites WHERE status = :status))';
        } else {
            $sql .= ' AND "t".block_id NOT IN(SELECT id FROM blocks WHERE site_id IN(SELECT id FROM sites WHERE status = :status))';
        }
        $sql .= ' GROUP BY date
                ORDER BY date DESC';

        $archived = Block::STATUS_ARCHIVED;

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

    public function getPeriodSiteStatsByDate(array $params) {
        $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", date
                FROM "webmaster_stats" "t"
                INNER JOIN "blocks" "t2" ON ("t".block_id = "t2".id)
                WHERE user_id = :userId AND date <= :endDate AND date >= :startDate AND "t".site_id = :siteId';
        if($params['status'] == 'archived'){
            $sql .= ' AND "t2".status = :status';
        } else {
            $sql .= ' AND "t2".status <> :status';
        }
        $sql .= ' GROUP BY date
                ORDER BY date DESC';

        $archived = Block::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':siteId' => $params['siteId'],
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

    public function getPeriodBlocksStatsByDate(array $params) {
        $stats = $this->findAllBySql(
            'SELECT sum(shows) as "shows", sum(clicks) as "clicks", sum(costs) as "costs", date, site_id as "site_id"
                FROM "webmaster_stats" "t"
                WHERE user_id = :userId AND date <= :endDate AND date >= :startDate AND block_id = :blockId
                GROUP BY site_id, date
                ORDER BY date DESC',
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':blockId' => $params['blockId'],
            ]);

        $result = [];

        foreach($stats as $stat) {
            $result[] = [
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
                'date' => $stat->date,
                'site_id' => $stat->site_id
            ];
        }

        if($params['today']){
            array_unshift($result,$this->getTodayStatsByDate($params));
        }

        $result = array_reverse($result);

        return $result;
    }

    public function getPeriodSiteStats(array $params) {
        $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".site_id as "site_id", "t".block_id as "block_id", "t2".description as "date"
                FROM "webmaster_stats" "t"
                INNER JOIN "blocks" "t2" ON ("t".block_id = "t2".id)
                WHERE "t".user_id = :userId AND date <= :endDate AND date >= :startDate AND "t".site_id = :siteId';
        if($params['status'] == 'archived'){
            $sql .= ' AND "t2".status = :status';
        } else {
            $sql .= ' AND "t2".status <> :status';
        }
        $sql .= ' GROUP BY "t2".description, "t".site_id, "t".block_id';

        $archive = Block::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':siteId' => $params['siteId'],
                ':status' => $archive
            ]);

        $result = [];

        foreach($stats as $stat) {
            $result[$stat->block_id] = [
                'description' => $stat->date,
                'item_id' => $stat->block_id,
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
                'block_id' => $stat->block_id
            ];
        }

        if($params['today']){
            $params['groupBy'] = 'blocks';
            $todayStats = $this->getTodaySiteStats($params);

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

    public function getPeriodStatsBySite(array $params) {
        $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t2".url as "date", "t".site_id as "site_id"
                FROM "webmaster_stats" "t"
                INNER JOIN "sites" "t2" ON ("t".site_id = "t2".id)
                WHERE "t".user_id = :userId AND date <= :endDate AND date >= :startDate';

        if($params['status'] == 'archived'){
            $sql .= ' AND "t2".status = :status AND "t".block_id NOT IN(SELECT id FROM blocks WHERE site_id IN(SELECT id FROM sites WHERE status <> :status))';
        } else {
            $sql .= ' AND "t2".status <> :status AND "t".block_id NOT IN(SELECT id FROM blocks WHERE site_id IN(SELECT id FROM sites WHERE status = :status))';
        }
        $sql .= ' GROUP BY "t2".url, "t".site_id';

        $archived = Block::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
            [
                ':endDate' => $params['endDate'],
                ':startDate' => $params['startDate'],
                ':userId' => $this->user_id,
                ':status' => $archived
            ]);

        $result = [];

        foreach($stats as $stat) {
            $result[$stat->site_id] = [
                'description' => $stat->date,
                'item_id' => $stat->site_id,
                'shows' => $stat->shows,
                'clicks' => $stat->clicks,
                'costs' => $stat->getExpenses(),
            ];
        }

        if($params['today']){
            $params['groupBy'] = 'sites';
            $todayStats = $this->getTodaySiteStats($params);

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
                $method = 'getBlocks';
                break;
            case 'sites':
                $method = 'getBlocksBySite';
                $params['id'] = $params['siteId'];
                break;
            case 'blocks':
                $method = 'getBlocksById';
                $params['id'] = $params['blockId'];
                break;
            default:
                $method = 'getBlocks';
                break;
        }

        $blocks = $user->$method($params);

        $today = new \DateTime();
        $today = $today->format(\Yii::app()->params['dateFormat']);

        $stats = [
            'shows' => 0,
            'clicks' => 0,
            'costs' => 0,
            'date' => $today
        ];

        foreach($blocks as $block){
            $stats['shows'] += intval(\core\RedisIO::get("block-shows:{$block->id}")) ? intval(\core\RedisIO::get("block-shows:{$block->id}")) : $block->shows;
            $stats['clicks'] += intval(\core\RedisIO::get("block-clicks:{$block->id}")) ? intval(\core\RedisIO::get("block-clicks:{$block->id}")) : $block->clicks;
            $stats['costs'] += floatval(\core\RedisIO::get("block-income:{$block->id}")) ? round(floatval(\core\RedisIO::get("block-income:{$block->id}")),2) : 0;
        }

        if($params['layer'] == 'blocks') {
            $beforeStats = $this->findAllBySql(
                'SELECT sum(shows) as "shows", sum(clicks) as "clicks", sum(costs) as "costs", block_id as "block_id"
                    FROM "webmaster_stats" "t"
                    WHERE user_id = :userId AND block_id = :blockId
                    GROUP BY block_id',
                [
                    ':userId' => $this->user_id,
                    ':blockId' => $params['id'],
                ]);
        } elseif($params['layer'] == 'sites') {
            $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".block_id as "block_id"
                    FROM "webmaster_stats" "t"
                    INNER JOIN "blocks" "t2" ON ("t".block_id = "t2".id)
                    WHERE user_id = :userId AND "t".site_id = :siteId';
            if($params['status'] == 'archived'){
                $sql .= ' AND "t2".status = :status';
            } else {
                $sql .= ' AND "t2".status <> :status';
            }
            $sql .= ' GROUP BY "t".block_id';

            $archive = Block::STATUS_ARCHIVED;

            $beforeStats = $this->findAllBySql($sql,
                [
                    ':userId' => $this->user_id,
                    ':siteId' => $params['siteId'],
                    ':status' => $archive
                ]);
        } else {
            $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".block_id as "block_id"
                    FROM "webmaster_stats" "t"
                    INNER JOIN "blocks" "t2" ON ("t".block_id = "t2".id)
                    WHERE user_id = :userId';
            if($params['status'] == 'archived'){
                $sql .= ' AND "t2".status = :status';
            } else {
                $sql .= ' AND "t2".status <> :status';
            }
            $sql .= ' GROUP BY "t".block_id';

            $archive = Block::STATUS_ARCHIVED;

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

    public function getTodaySiteStats(array $params){
        $user = Users::model();
        $user->id = $this->user_id;

        if(!isset($params['layer']))
            $params['layer'] = 'main';

        switch($params['layer']){
            case 'main':
                $method = 'getBlocks';
                break;
            case 'sites':
                $method = 'getBlocksBySite';
                $params['id'] = $params['siteId'];
                break;
            case 'blocks':
                $method = 'getBlocksById';
                $params['id'] = $params['blockId'];
                break;
            default:
                $method = 'getBlocks';
                break;
        }

        $blocks = $user->$method($params);

        $today = new \DateTime();
        $today = $today->format(\Yii::app()->params['dateFormat']);

        $stats = [];

        if($params['groupBy'] == 'sites'){

            foreach($blocks as $block){
                if(isset($stats[$block->site_id])){
                    $stats[$block->site_id]['shows'] += intval(\core\RedisIO::get("block-shows:{$block->id}")) ? intval(\core\RedisIO::get("block-shows:{$block->id}")) : $block->shows;
                    $stats[$block->site_id]['clicks'] += intval(\core\RedisIO::get("block-clicks:{$block->id}")) ? intval(\core\RedisIO::get("block-clicks:{$block->id}")) : $block->clicks;
                    $stats[$block->site_id]['costs'] += floatval(\core\RedisIO::get("block-income:{$block->id}")) ? round(floatval(\core\RedisIO::get("block-income:{$block->id}")),2) : 0;
                } else {
                    $stats[$block->site_id] = [
                        'shows' => intval(\core\RedisIO::get("block-shows:{$block->id}")) ? intval(\core\RedisIO::get("block-shows:{$block->id}")) : $block->shows,
                        'clicks' => intval(\core\RedisIO::get("block-clicks:{$block->id}")) ? intval(\core\RedisIO::get("block-clicks:{$block->id}")) : $block->clicks,
                        'costs' => floatval(\core\RedisIO::get("block-income:{$block->id}")) ? round(floatval(\core\RedisIO::get("block-income:{$block->id}")),2) : 0,
                        'description' => $block->site->url,
                        'item_id' => $block->site_id
                    ];
                }
            }

            $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".site_id as "site_id", "t".block_id as "block_id"
                    FROM "webmaster_stats" "t"
                    INNER JOIN "blocks" "t2" ON ("t".block_id = "t2".id)
                    WHERE user_id = :userId';
            if($params['status'] == 'archived'){
                $sql .= ' AND "t2".status = :status';
            } else {
                $sql .= ' AND "t2".status <> :status';
            }
            $sql .= ' GROUP BY "t".site_id, "t".block_id';

            $archive = Block::STATUS_ARCHIVED;

            $beforeStats = $this->findAllBySql($sql,
                [
                    ':userId' => $this->user_id,
                    ':status' => $archive
                ]);

            foreach($stats as $key => $stat){
                foreach($beforeStats as $bStat){
                    if($key == $bStat->site_id){
                        $stats[$key]['shows'] -= $bStat->shows;
                        $stats[$key]['clicks'] -= $bStat->clicks;
                        $stats[$key]['costs'] -= $bStat->getExpenses();
                    }
                }
            }

        } elseif($params['groupBy'] == 'blocks'){

            foreach($blocks as $block){
                if(isset($stats[$block->id])){
                    $stats[$block->site_id]['shows'] += intval(\core\RedisIO::get("block-shows:{$block->id}")) ? intval(\core\RedisIO::get("block-shows:{$block->id}")) : $block->shows;
                    $stats[$block->site_id]['clicks'] += intval(\core\RedisIO::get("block-clicks:{$block->id}")) ? intval(\core\RedisIO::get("block-clicks:{$block->id}")) : $block->clicks;
                    $stats[$block->site_id]['costs'] += floatval(\core\RedisIO::get("block-income:{$block->id}")) ? round(floatval(\core\RedisIO::get("block-income:{$block->id}")),2) : 0;
                } else {
                    $stats[$block->id] = [
                        'shows' => intval(\core\RedisIO::get("block-shows:{$block->id}")) ? intval(\core\RedisIO::get("block-shows:{$block->id}")) : $block->shows,
                        'clicks' => intval(\core\RedisIO::get("block-clicks:{$block->id}")) ? intval(\core\RedisIO::get("block-clicks:{$block->id}")) : $block->clicks,
                        'costs' => floatval(\core\RedisIO::get("block-income:{$block->id}")) ? round(floatval(\core\RedisIO::get("block-income:{$block->id}")),2) : 0,
                        'description' => $block->description,
                        'item_id' => $block->id
                    ];
                }
            }

            $sql = 'SELECT sum("t".shows) as "shows", sum("t".clicks) as "clicks", sum("t".costs) as "costs", "t".block_id as "block_id"
                    FROM "webmaster_stats" "t"
                    INNER JOIN "blocks" "t2" ON ("t".block_id = "t2".id)
                    WHERE user_id = :userId AND "t".site_id = :siteId';
            if($params['status'] == 'archived'){
                $sql .= ' AND "t2".status = :status';
            } else {
                $sql .= ' AND "t2".status <> :status';
            }
            $sql .= ' GROUP BY "t".block_id';

            $archive = Block::STATUS_ARCHIVED;

            $beforeStats = $this->findAllBySql($sql,
                [
                    ':userId' => $this->user_id,
                    ':siteId' => $params['siteId'],
                    ':status' => $archive
                ]);

            foreach($stats as $key => $stat){
                foreach($beforeStats as $bStat){
                    if($key == $bStat->block_id){
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
        $sql = 'SELECT date as "date", sum(shows) as "shows", sum(clicks) as "clicks", sum(costs) as "costs", "t2".url as "site_id"
                FROM "webmaster_stats" "t"
                INNER JOIN "sites" "t2" ON ("t".site_id = "t2".id)
                WHERE "t".user_id = :userId';
        if($params['status'] == 'archived'){
            $sql .= ' AND "t".block_id NOT IN(SELECT id FROM blocks WHERE site_id IN(SELECT id FROM sites WHERE status <> :status))';
        } else {
            $sql .= ' AND "t".block_id NOT IN(SELECT id FROM blocks WHERE site_id IN(SELECT id FROM sites WHERE status = :status))';
        }
        $sql .= ' GROUP BY url, date';

        $archive = Block::STATUS_ARCHIVED;

        $stats = $this->findAllBySql($sql,
            [
                ':userId' => $this->user_id,
                ':status' => $archive
            ]);

        $today = new \DateTime();
        $today = $today->format(\Yii::app()->params['dateFormat']);

        foreach($dateStats as $key => $date){
            foreach($stats as $stat){
                if($date['date'] == $stat->date){
                    $dateStats[$key]['details'][] = [
                        'description' => $stat->site_id,
                        'shows' => $stat->shows,
                        'clicks' => $stat->clicks,
                        'costs' => $stat->getExpenses(),
                    ];
                }
            }
            if($date['date'] == $today && !isset($date[$today]['details'])){
                $dateStats[$key]['details'][] = [
                    'description' => \Yii::t('webmaster_stats','Для получения детальной статистики за сегодня - включите отображение по площадкам')
                ];
            }
        }
    }
}
