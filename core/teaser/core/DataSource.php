<?php
namespace core;
/**
 * Created by t0m
 * Date: 04.01.14
 * Time: 18:10
 */

abstract class DataSource
{

    private $config;
    private $connections;

    public function getConnection($dependency, $instanceName)
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

    protected abstract function makeConnection(array $config);

    protected abstract function getShardAlias($dependency);
}