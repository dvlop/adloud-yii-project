<?php
namespace core;
use config\Config;
use core\DataSource;
use PDO;
use core\Session;

/**
 * Created by t0m
 * Date: 29.12.13
 * Time: 20:38
 */

class PostgreSQL extends DataSource
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
     * @param Session $dependency
     * @return PDO;
     */
    public function getConnection($dependency, $instanceName)
    {
        $shard = $this->getShardAlias($dependency);
        $config = Config::getInstance()->getPostgresSettings($shard)[$instanceName];

        $connectionHash = md5($shard . $instanceName);

        if (!isset($this->connections[$connectionHash])) {
            $this->connections[$connectionHash] = $this->makeConnection($config);

        }
        return $this->connections[$connectionHash];
    }

    private function __construct(){}

    protected function makeConnection(array $config)
    {
        return new PDO("pgsql:host={$config['address']};port={$config['port']};dbname={$config['database']};user={$config['user']};password={$config['password']}");
    }

    protected function getShardAlias($dependency)
    {
        return 'pgsql1';
    }
}