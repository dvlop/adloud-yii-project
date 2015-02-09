<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 26.07.14
 * Time: 18:01
 */

namespace core\clickFilter;


class SpentTimeFilter extends FilterAbstract {

    public function filter(){
        if(empty($_SESSION['lastPageRequest'])){
            return ClickErrors::SPENT_TIME_ERROR;
        }
        $date = @\DateTime::createFromFormat('Y-m-d H:i:s', $_SESSION['lastPageRequest']);
        if(!$date){
            return ClickErrors::SPENT_TIME_ERROR;
        }

        $twoSecondsEgo = (new \DateTime())->modify('-2 seconds');

        if($date > $twoSecondsEgo){
            return ClickErrors::SPENT_TIME_ERROR;
        }

        return ClickErrors::OK;
    }

} 