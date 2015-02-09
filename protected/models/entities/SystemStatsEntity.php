<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 18.09.14
 * Time: 17:07
 * To change this template use File | Settings | File Templates.
 */

namespace application\models\entities;

use application\models\Blocks;
use application\models\Categories;
use application\models\Sites;
use application\models\Users;
use core\PostgreSQL;
use core\Session;

class SystemStatsEntity extends AbstractEntity {

    public static function getIncome($period = null, $startDate = null, $endDate = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'actual_data');
        $sql = 'SELECT SUM(amount) FROM transactions WHERE "from" = \'advertiser\' AND "to" = \'system\'';
        $params = [];

        if($period == 'today'){
            $sql .= ' AND timestamp >= \'today\'';
        } elseif($startDate && $endDate){
            $sql .= ' AND timestamp >= :startDate AND timestamp <= :endDate';
            $params = [':startDate' => $startDate, ':endDate' => $endDate];
        }

        $statement = $db->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return round($result['sum'],2);
    }

    public static function getIncomeByDate($startDate = null, $endDate = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'actual_data');
        $sql = 'SELECT SUM(amount),date("timestamp")
        FROM transactions
        WHERE "from" = \'advertiser\'
        AND "to" = \'system\'
        AND timestamp >= :startDate
        AND timestamp <= :endDate
        GROUP BY date("timestamp")
        ORDER BY date("timestamp") ASC';
        $params = [':startDate' => $startDate, ':endDate' => $endDate];

        $statement = $db->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getMoneyIn($period = null, $startDate = null, $endDate = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'actual_data');
        $sql = 'SELECT SUM(amount) FROM transactions WHERE "from" = \'system\' AND "to" = \'advertiser\'';
        $params = [];

        if($period == 'today'){
            $sql .= ' AND timestamp >= \'today\'';
        } elseif($startDate && $endDate){
            $sql .= ' AND timestamp >= :startDate AND timestamp <= :endDate';
            $params = [':startDate' => $startDate, ':endDate' => $endDate];
        }

        $statement = $db->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return round($result['sum'],2);
    }

    public static function getMoneyInByDate($startDate = null, $endDate = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'actual_data');
        $sql = 'SELECT SUM(amount),date("timestamp")
        FROM transactions
        WHERE "from" = \'system\'
        AND "to" = \'advertiser\'
        AND timestamp >= :startDate
        AND timestamp <= :endDate
        GROUP BY date("timestamp")
        ORDER BY date("timestamp") ASC';
        $params = [':startDate' => $startDate, ':endDate' => $endDate];

        $statement = $db->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getMoneyOut($period = null, $startDate = null, $endDate = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'actual_data');
        $sql = 'SELECT SUM(amount) FROM transactions WHERE "from" = \'webmaster\' AND "to" IS NULL';
        $params = [];

        if($period == 'today'){
            $sql .= ' AND timestamp >= \'today\'';
        } elseif($startDate && $endDate){
            $sql .= ' AND timestamp >= :startDate AND timestamp <= :endDate';
            $params = [':startDate' => $startDate, ':endDate' => $endDate];
        }

        $statement = $db->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return round($result['sum'],2);
    }

    public static function getMoneyOutByDate($startDate = null, $endDate = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'actual_data');
        $sql = 'SELECT SUM(amount),date("timestamp")
        FROM transactions
        WHERE "from" = \'webmaster\'
        AND "to" IS NULL
        AND timestamp >= :startDate
        AND timestamp <= :endDate
        GROUP BY date("timestamp")
        ORDER BY date("timestamp") ASC';
        $params = [':startDate' => $startDate, ':endDate' => $endDate];

        $statement = $db->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getUsersCount($period = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'persistent_data');
        $sql = 'SELECT COUNT(id) FROM users';

        if($period == 'today')
            $sql .= ' WHERE register_date >= \'today\'';

        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return intval($result['count']);
    }

    public static function getSitesCount($period = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'persistent_data');
        $sql = 'SELECT COUNT(id) FROM sites';

        if($period == 'today')
            $sql .= ' WHERE create_date >= \'today\'';

        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return intval($result['count']);
    }

    public static function getAdsCount($period = null){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(),'persistent_data');
        $sql = 'SELECT COUNT(id) FROM ads';

        if($period == 'today')
            $sql .= ' WHERE create_date >= \'today\'';

        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return intval($result['count']);
    }

    public static function getTraffic($period = null){
        $categories = Categories::model()->findAll([]);
        $trafficByCategory = [];
        $totalTraffic = [
            'shows' => 0,
            'clicks' => 0
        ];

        foreach($categories as $cat){
            $categoryTraffic = $period == 'today' ? $cat->getCategoryTodayTraffic() : $cat->getCategoryTotalTraffic();
            $trafficByCategory[$cat->name] = $categoryTraffic;

            $totalTraffic['shows'] += $categoryTraffic['shows'];
            $totalTraffic['clicks'] += $categoryTraffic['clicks'];
        }

        return [
            'byCategory' => $trafficByCategory,
            'total' => $totalTraffic
        ];
    }

    public static function getTrafficBySite($period = null) {
        $sites = Sites::model()->findAllByAttributes(['status' => 3]);
        $trafficBySite = [];

        foreach($sites as $site){
            $siteTraffic = $period == 'today' ? $site->getSiteTodayTraffic() : $site->getSiteTotalTraffic();
            $trafficBySite[$site->url] = $siteTraffic;
        }
        arsort($trafficBySite);
        $trafficBySite = array_slice($trafficBySite, 0, 10);

        return $trafficBySite;
    }

    public static function getPeriodCategoryTrafficByDate($startDate, $endDate){
        $categories = Categories::model()->findAll([]);
        $categoryTable = [];

        foreach($categories as $cat){
            $categoryTable[$cat->name] = $cat->getCategoryTrafficByDate($startDate, $endDate);
        }

        return $categoryTable;
    }

    public static function getPeriodCategoryTraffic($startDate, $endDate){
        $categories = Categories::model()->findAll([]);
        $categoryTable = [];

        foreach($categories as $cat){
            if($cat->name)
                $categoryTable[$cat->name] = $cat->getCategoryPeriodTraffic($startDate, $endDate);
        }

        return $categoryTable;
    }

    public static function getPeriodCategoryData($startDate, $endDate){
        $categories = Categories::model()->findAll([]);
        $categoryTable = [];

        foreach($categories as $cat){
            if($cat->name)
                $categoryTable[$cat->id] = $cat->getPeriodData($startDate, $endDate);
        }

        return $categoryTable;
    }

    public static function getPeriodSiteData($categoryId,$startDate, $endDate){
        $sites = Sites::model()->findAllByAttributes(['category' => $categoryId]);
        $siteTable = [];

        foreach($sites as $site){
            if($site->url)
                $siteTable[$site->id] = $site->getPeriodData($startDate, $endDate);
        }

        return $siteTable;
    }

    public static function getPeriodSiteDataByUser($userId,$startDate, $endDate){
        $sites = Sites::model()->findAllByAttributes(['user_id' => $userId]);
        $siteTable = [];

        foreach($sites as $site){
            if($site->url)
                $siteTable[$site->id] = $site->getPeriodData($startDate, $endDate);
        }

        return $siteTable;
    }

    public static function getPeriodBlockData($siteId,$startDate, $endDate){
        $blocks = Blocks::model()->findAllByAttributes(['site_id' => $siteId]);
        $blockTable = [];

        foreach($blocks as $block){
            if($block->description)
                $blockTable[$block->id] = $block->getPeriodData($startDate, $endDate);
        }

        return $blockTable;
    }

    public static function getPeriodTotalTraffic($startDate, $endDate){
        $categories = Categories::model()->findAll([]);
        $totalTraffic = [
            'shows' => 0,
            'clicks' => 0
        ];

        foreach($categories as $cat){
            $categoryTraffic = $cat->getCategoryPeriodTraffic($startDate, $endDate);

            $totalTraffic['shows'] += $categoryTraffic['shows'];
            $totalTraffic['clicks'] += $categoryTraffic['clicks'];
        }

        return $totalTraffic;
    }

    public static function getPeriodTotalTrafficByDate($startDate, $endDate){
        $categories = Categories::model()->findAll([]);
        $trafficTable = [];

        foreach($categories as $cat){
            $traffic = $cat->getCategoryTrafficByDate($startDate, $endDate);

            foreach($traffic as $date => $traf){
                if(!isset($trafficTable[$date])){
                    $trafficTable[$date] = [
                        'shows' => $traf['shows'],
                        'clicks' => $traf['clicks'],
                    ];
                } else {
                    $trafficTable[$date]['shows'] += $traf['shows'];
                    $trafficTable[$date]['clicks'] += $traf['clicks'];
                }
                $trafficTable[$date]['ctr'] = $trafficTable[$date]['shows'] ? round(($trafficTable[$date]['clicks']/$trafficTable[$date]['shows'])*100,4) : 0;
            }
        }

        return $trafficTable;
    }

    public static function getPeriodUsers($startDate, $endDate){
        $result = [];

        $allUsers = Users::model()->findAllBySql('SELECT id,email,full_name,register_date FROM users WHERE register_date >= :startDate AND register_date <= :endDate',[
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        $advertisers = Users::model()->findAllBySql('
        SELECT campaign.user_id as id FROM users LEFT JOIN campaign ON (users.id = campaign.user_id)
        WHERE register_date >= :startDate AND register_date <= :endDate AND campaign.user_id IS NOT NULL GROUP BY users.id,campaign.user_id ORDER BY users.id',[
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);
        $webmasters = Users::model()->findAllBySql('
        SELECT sites.user_id as id FROM users LEFT JOIN sites ON (users.id = sites.user_id)
        WHERE register_date >= :startDate AND register_date <= :endDate AND sites.user_id IS NOT NULL GROUP BY users.id,sites.user_id ORDER BY users.id',[
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        $advertisers = self::objectsId2array($advertisers);
        $webmasters = self::objectsId2array($webmasters);

        foreach($allUsers as $user){
            $role = [];
            if(in_array($user->id,$advertisers))
                $role[] = 'Рекламодатель';
            if(in_array($user->id,$webmasters))
                $role[] = 'Вебмастер';

            $result[$user->id] = [
                'id' => $user->id,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'role' => implode(', ',$role),
                'register_date' => $user->register_date
            ];
        }

        return [
            'numbers' => [
                'totalUsers' => count($allUsers),
                'advertisers' => count($advertisers),
                'webmasters' => count($webmasters)
            ],
            'table' => $result
        ];
    }

    public static function getPeriodUsersByDate($startDate, $endDate){
        $result = [];
        $users = Users::model()->findAllBySql('
        SELECT count(id) as id, register_date FROM users
        WHERE register_date >= :startDate AND register_date <= :endDate GROUP BY register_date ORDER BY register_date',[
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        foreach($users as $user){
            $result[] = [
                'date' => $user->register_date,
                'sum' => $user->id
            ];
        }

        return $result;
    }

    public static function objectsId2array($objects){
        $result = [];
        foreach($objects as $obj){
            $result[] = $obj->id;
        }
        return $result;
    }

    public static function arrayDateFill(array $array, $from, $to){
        if(!$array){
            $array[0] = [
                'sum' => 0,
                'date' => null
            ];
        }
        $result = [];
        $i = 0;
        $from = new \DateTime($from);
        $to = (new \DateTime($to))->modify('-1 day');

        while($from <= $to){
            if(isset($array[$i]) && $array[$i]['date'] == $from->format(\Yii::app()->params->dateFormat)){
                $result[] = $array[$i];
                $i++;
            } else {
                $keys = [];
                foreach($array[0] as $key => $val){
                    if($key != 'date')
                        $keys[] = $key;
                }
                $emptyDate = array_fill_keys($keys,0);
                $emptyDate['date'] = $from->format(\Yii::app()->params->dateFormat);

                $result[] = $emptyDate;
            }
            $from->modify('+1 day');
        }

        return $result;
    }
}