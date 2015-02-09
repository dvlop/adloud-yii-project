<?php
/**
 * Created by t0m
 * Date: 17.12.13
 * Time: 0:10
 * @property \config\Config $config
 */

namespace MLF\layers;


use config\Config;
use MLF\exceptions\FileNotFoundException;
use MLF\exceptions\MethodNotFoundException;
use MLF\LayersConfig;

abstract class Logic extends Decorable{

    protected function __construct(Decorable $nextLayer = null){
        $this->nextLayer = $nextLayer;
        $this->config = Config::getInstance();
    }

    public function __call($method, $params){
        throw new MethodNotFoundException("Can't find method {$method}");
    }

    public static function getInstance(){
        $layers = array_reverse(LayersConfig::getInstance()->getLayersName());
        $model = null;
        $className = get_called_class();

        foreach($layers as $layer){

            list($namespace, $class) = explode('\\', $className);
            $ns = $namespace . '\\'. lcfirst($layer['name']) .'\\';

            if($layer['type'] === 'logic'){
                $layerName = $className;
            } else {
                $layerName = $ns . $class . $layer['name'];
            }

            if(!@class_exists($layerName)){
                if(!@class_exists($ns .$layer['name'] . 'Layer')){
                    throw new FileNotFoundException("Class {$layerName} not found.");
                }
                $layerName = $ns . $layer['name'] . 'Layer';
            }

            $model = new $layerName($model);
        }
        return $model;
    }

    public function tableName()
    {
        return $this->nextLayer->tableName();
    }

    public function getCount()
    {
        return $this->count();
    }

    public function count($attributes = [], $tableName = '')
    {
        return $this->nextLayer->count($attributes, $tableName);
    }

    public function find($attributes = [])
    {
        return $this->nextLayer->find($attributes);
    }

    public function getAll()
    {
        return $this->nextLayer->getAll();
    }

    public function findAll($attributes = [])
    {
        return $this->nextLayer->findAll($attributes);
    }

    public function saveModel($attributes = [])
    {
        return $this->nextLayer->saveModel($attributes);
    }

    public function findById($attributes = [], $id = 0)
    {
        return $this->nextLayer->findById($attributes, $id);
    }

    public function beginTransaction()
    {
        return $this->nextLayer->beginTransaction();
    }

    public function transactionCommit()
    {
        return $this->nextLayer->transactionCommit();
    }

    public function transactionRollBack()
    {
        return $this->nextLayer->transactionCommit();
    }

    public function deleteModel($id = null)
    {
        return $this->nextLayer->deleteModel($id);
    }
}