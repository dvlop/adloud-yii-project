<?php
namespace core;
use config\Config;
use core\DataSource;
use DataLayerException;
use Redis;

/**
 * Created by t0m
 * Date: 04.01.14
 * Time: 18:25
 */

class RedisConnection extends DataSource
{
    private static $_instance;
    private $config;
    private $connections;

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param String $instanceName
     * @param string $dependency
     * @return Redis;
     */
    public function getConnection($dependency, $instanceName = null)
    {

        $shard = $this->getShardAlias($dependency);
        $config = $this->config[$shard];
        $config['database'] = $instanceName ? $instanceName : (isset($this->config[$shard]['database']) ? $this->config[$shard]['database'] : null);
        $connectionHash = md5($shard . $instanceName);

        if (!isset($this->connections[$connectionHash])) {
            $this->connections[$connectionHash] = $this->makeConnection($config);
        }

        return $this->connections[$connectionHash];
    }

    private function __construct()
    {
        $this->config = Config::getInstance()->getRedisSettings();
    }

    protected function makeConnection(array $config)
    {
        $redis = new Redis();
        $connection = $redis->connect($config['address'], $config['port']);
        if (!$connection) {
            throw new \exceptions\DataLayerException('redis server not found');
        }
        return $redis;
    }

    protected function getShardAlias($dependency)
    {
        return 'redis1';
    }
}