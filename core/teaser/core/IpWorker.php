<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 22.04.14
 * Time: 0:00
 */

namespace core;


class IpWorker {

    const IP_HIT_COUNT_PREFIX = 'ip-hc-';

    /**
     * @var \DateTime
     */
    private $date;

    public function __construct(){
        $this->date = new \DateTime();
    }

    public function getIpHitCount($ip){
        return RedisIO::get($this->getKeyPrefix() . $ip);
    }

    public function incIpHitCount($ip){
        return RedisIO::incr($this->getKeyPrefix() . $ip);
    }

    private function getKeyPrefix(){
        return IpWorker::IP_HIT_COUNT_PREFIX . $this->date->format('Y-m-d') . ':';
    }

} 