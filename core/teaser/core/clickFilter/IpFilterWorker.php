<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 21.04.14
 * Time: 23:58
 */

namespace core\clickFilter;


use core\IpWorker;

class IpFilterWorker extends FilterAbstract{

    private $ip;
    private $maxHit;
    private $ipWorker;

    public function __construct(IpWorker $ipWorker, $ip, $maxHit){
        $this->ip = $ip;
        $this->maxHit = $maxHit;
        $this->ipWorker = $ipWorker;
    }

    public function filter(){
        if($this->ipWorker->getIpHitCount($this->ip) > $this->maxHit){
            return false;
        }
        return true;
    }

} 