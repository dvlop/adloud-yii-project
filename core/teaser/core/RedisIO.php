<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 04.03.14
 * Time: 23:36
 */

namespace core;


class RedisIO {

    public static function get($key){
        $redis = RedisConnection::getInstance()->getConnection($key);
        return $redis->get($key);
    }

    public static function delete($key){
        $redis = RedisConnection::getInstance()->getConnection($key);
        return $redis->delete($key);
    }

    public static function set($key, $value){
        $redis = RedisConnection::getInstance()->getConnection($key);
        return $redis->set($key, $value);
    }

    public static function incrByFloat($key, $value){
        $redis = RedisConnection::getInstance()->getConnection($key);
        return $redis->incrByFloat($key, $value);
    }

    public static function incr($key){
        $redis = RedisConnection::getInstance()->getConnection($key);
        return $redis->incr($key);
    }

    public static function hIncrBy($key,$element,$val=1){
        $redis = RedisConnection::getInstance()->getConnection($key);
        return $redis->hIncrBy($key,$element,$val);
    }

    public static function hGetAll($key){
        $redis = RedisConnection::getInstance()->getConnection($key);
        return $redis->hGetAll($key);
    }
} 