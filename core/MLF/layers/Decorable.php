<?php
/**
 * Created by t0m
 * Date: 17.12.13
 * Time: 0:18
 */

namespace MLF\layers;


class Decorable {
    protected $nextLayer;

    /**
     * @property \config\Config $config
     */
    protected $config;

    public function __set($key, $value){
        $this->nextLayer->$key = $value;
    }

    public function __get($key){
        return $this->nextLayer->$key;
    }

}