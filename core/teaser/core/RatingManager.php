<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 11.03.14
 * Time: 22:15
 */

namespace core;

use models\Category;
use models\dataSource\CategoryDataSource;

class RatingManager
{
    const RATE_STEPS_COUNT = 1100;
    const DEFAULT_CTR = 0.001;
    const MIN_RATE = 0.0001;

    public function getAdsEfficiencyRate($clickPrice, $ctr = null){
        $ctr = $ctr ? $ctr : $this->getDefaultCtr();
        //$ctr = $this->getDefaultCtr();
        return round($ctr * $clickPrice, 8) * 1000;
    }

    public function setCategoriesEfficiencyRate($efficiencyRate, $categories = array(), $adsId){
        if(!$categories) return false;

        foreach($categories as $category){
            $maxRate = RedisIO::get("max-efficiency-rate:{$category}");
            if($efficiencyRate > $maxRate){
                RedisIO::set("max-efficiency-rate:{$category}", $efficiencyRate);
                RedisIO::set("category-leader:{$category}", $adsId);
            }

            $minRate = RedisIO::get("min-efficiency-rate:{$category}");
            if(($efficiencyRate < $minRate) || $minRate == 0 ){
                RedisIO::set("min-efficiency-rate:{$category}", $efficiencyRate);
                RedisIO::set("category-looser:{$category}", $adsId);
            }
        }
        return true;
    }

    public function updateCategoryRatingsExtremes($efficiencyRate, $categories = array(), $adsId){
        foreach($categories as $category){
            $categoryLooser = RedisIO::get("category-looser:{$category}");
            if($categoryLooser == $adsId){
                RedisIO::set("min-efficiency-rate:{$category}", $efficiencyRate);
            }
            $categoryLeader = RedisIO::get("category-leader:{$category}");
            if($categoryLeader == $adsId){
                RedisIO::set("max-efficiency-rate:{$category}", $efficiencyRate);
            }
        }
    }

    public function getRandomEfficiencyRate($category){
        $maxRate = RedisIO::get("max-efficiency-rate:{$category}");
        $minRate = RedisIO::get("min-efficiency-rate:{$category}");

        if(!$minRate || !$maxRate){
            return 0;
        }

        $ratings = $this->getRatingArray($minRate, $minRate, $maxRate);
        $rating = $ratings[rand(0, count($ratings) - 1)];

        return $rating ? $rating : 0;
    }

    public function getRandomEfficiencyRate_new($category){
        $maxRate = RedisIO::get("max-efficiency-rate:{$category}");
        $minRate = RedisIO::get("min-efficiency-rate:{$category}");

        if(!$minRate || !$maxRate){
            return 0;
        }

        $minRate = abs($minRate - self::MIN_RATE);
        $stepCount = self::RATE_STEPS_COUNT;
        $step = ($maxRate - $minRate)/$stepCount;
        $randomRate = $stepCount*(1 - mt_rand()/mt_getrandmax());
        return round($minRate + $step*$randomRate, 4);
    }

    public function setAdsRating($rating, $adsId){
        $pdo = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'actual_data');
        $sql = 'UPDATE "ads" SET "rating" = :rating WHERE "id" = :id';
        $statement = $pdo->prepare($sql);
        return $statement->execute(array(':id' => $adsId, ':rating' => $rating));
    }

    public function updateCategoriesRatings(){
        $categoriesDS = Category::getInstance();
        $categories = $categoriesDS->getList();

        foreach($categories as $cat){
            $ratings = $this->getCategoryRatings($cat['id']);
            echo "max-efficiency-rate:{$cat['id']}:" . $ratings['max'] ."\n";
            echo "min-efficiency-rate:{$cat['id']}:" . $ratings['min']."\n";
            RedisIO::set("max-efficiency-rate:{$cat['id']}", $ratings['max']);
            RedisIO::set("min-efficiency-rate:{$cat['id']}", $ratings['min']);
        }
    }

    public static function getCtr($shows, $clicks){
        if(!$shows){
            return 0;
        }
        return round($clicks/$shows, 6);
    }

    private function getCategoryRatings($catId){
        $sql = 'SELECT max(rating), min(rating) FROM "public"."ads" WHERE :cat_id = any(categories);';
        $pdo = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'actual_data');
        $statement = $pdo->prepare($sql);

        $statement->execute(array(':cat_id' => $catId));
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    private function getRatingArray_new($minRate, $maxRate, $count = 100)
    {
        if($minRate == $maxRate){
            return [$maxRate];
        }
        $step = round(($maxRate - $minRate)/$count, 7);

        $ratings = [];
        while($minRate < $maxRate-$step){
            $ratings[] = $minRate += $step;
        }

        return $ratings;
    }

    private function getRatingArray($step, $minRate, $maxRate){
        $ratings = [];
        $i = 0;
        do {
            $i++;
            $rateIndex = $step + pow(($i/100 > 1 ? $i/100 : 0), ($i)/100);
            $rate = round($rateIndex*$i, 4);

            $ratings[] = $rate;

        } while($rate < $maxRate );
        if($ratings[count($ratings) - 1] > $maxRate){
            $ratings[count($ratings) - 1] = $maxRate;
        }
        return $ratings;
    }

    private function getDefaultCtr(){
        return self::DEFAULT_CTR;
    }

} 