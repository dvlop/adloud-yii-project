<?php

namespace models\dataSource;
use core\PostgreSQL;
use core\Session;
use exceptions\DataLayerException;

/**
 * Class DataSourceLayer
 * @package models\dataSource
 * @property \PDO pdoActual
 * @property \PDO pdoPersistent
 */
class DataSourceLayer extends \MLF\layers\Layer
{

    private $_pdoActual;
    private $_pdoPersistent;

    /**
     * @param $name
     * @return bool|\PDO
     */
    public function __get($name){
        switch ($name){
            case 'pdoActual':
                if($this->_pdoActual == null){
                    $this->_pdoActual = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'actual_data');
                }
                return $this->_pdoActual;
            case 'pdoPersistent':
                if($this->_pdoPersistent == null){
                    $this->_pdoPersistent = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');
                }
                return $this->_pdoPersistent;
        }
        return false;
    }

    public function __construct(\MLF\layers\Layer $nextLayer = null)
    {
        parent::__construct($nextLayer);
    }

    protected function prepareArrayForInsert(array $data){
        return '{'. implode(',', $data) . '}';
    }

    protected function parseArrayFromDatabaseString($string){
        $string = str_replace('{', '', $string);
        $string = str_replace('}', '', $string);
        return array_filter(explode(',', $string));
    }

    public function tableName()
    {
        $name = explode('\\', get_class($this));
        return lcfirst(end($name));
    }

    public function indexName()
    {
        return ['id' => 0];
    }

    public function getAll($tableName = '')
    {
        return $this->findAll($tableName);
    }

    public function find($attributes = [], $tableName = '')
    {
        if(!$attributes){
            return $this->findAll($tableName);
        }elseif(is_array($attributes)){
            return $this->findAll($attributes, $tableName);
        }else{
            $result = $this->findAll([
                'where' => ['id' => (int)$attributes],
            ], $tableName);

            if($result) return $result[0];
            else return null;
        }
    }

    public function findAll($attributes = [], $tableName = '')
    {
        if(is_string($attributes) && $attributes){
            $tableName = $attributes;
            $attributes = array();
        }

        if(!$tableName)
            $tableName = $this->tableName();

        $sql = "SELECT ";
        $where = "";

        if(isset($attributes['where'])){
            $where = $this->getWhereSql($attributes['where']);
            unset($attributes['where']);
        }

        $sql .= $this->getAttributesSql($attributes)." FROM ".$tableName.$where;
        $sql .= " ORDER BY id";

        return $this->findBySQL($sql);
    }

    public function saveModel($attributes = [], $tableName = '')
    {
        if(method_exists($this, 'beforeSave')){
            $this->beforeSave();
        }

        if(is_string($attributes) && $attributes){
            $tableName = $attributes;
            $attributes = array();
        }

        if(!$tableName)
            $tableName = $this->tableName();

        $indexes = "(";
        $values = "(";

        $isNewRecord = true;

        if(isset($attributes['where'])){
            $where = $this->getWhereSql($attributes['where']);

            unset($attributes['where']);

            $isNewRecord = false;
        }else{
            if($idWhere = $this->getIndexWhere()){
                $where = " WHERE ".$idWhere;
                $isNewRecord = false;
            }else{
                $where = "";
            }
        }

        foreach($attributes as $name => $value){
            $indexes .="$name, ";
            $values .="'$value', ";
        }

        $indexes = substr($indexes, 0, strlen($indexes)-2).")";
        $values = substr($values, 0, strlen($values)-2).")";

        if($isNewRecord)
            $sql = 'INSERT INTO '.$tableName." $indexes VALUES $values";
        else
            $sql = 'UPDATE '.$tableName." SET $indexes = $values $where";

        return $this->pdoPersistent->query($sql);
    }

    public function getCount($attributes = [], $tableName = '')
    {
        return $this->count($attributes, $tableName);
    }

    public function count($attributes = [], $tableName = '')
    {
        $where = "";

        if(is_string($attributes) && $attributes){
            $tableName = $attributes;
            $attributes = array();
        }

        if(!$tableName)
            $tableName = $this->tableName();

        if($attributes){
            $where = " WHERE ";

            foreach($attributes as $name => $attribute){
                if(is_string($name) && strlen($name) > 0)
                    $where .= "$name = '$attribute' AND ";
                else
                    $where .= $attribute.' AND ';
            }

            if($where == " WHERE ")
                $where = "";
            else
                $where = substr($where, 0, strlen($where)-4);
        }

        $sql = "SELECT COUNT(*) FROM ".$tableName.$where;

        $statement = $this->pdoPersistent->query($sql);

        if(!$statement){
            return false;
        }

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $statement->fetch();

        if (!$result || !isset($result['count'])) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        return $result['count'];
    }

    public function findById($attributes = [], $id = 0)
    {
        if(is_integer($attributes) || is_string($attributes)){
            $id = (int)$attributes;
            $attributes = array();
        }

        $result = $this->findAll(array_merge($attributes, ['where' => ['id' => $id]]));

        if($result)
            return $result[0];
        else
            return false;
    }

    public function findByTerms($attributes = [], $terms = '', $tableName = '')
    {
        if(is_string($attributes)){
            $terms = $attributes;
            $attributes = array();
        }

        if(!is_string($terms) || $terms == '')
            return $this->findAll($tableName);

        if(!$tableName)
            $tableName = $this->tableName();

        $sql = "SELECT ".$this->getAttributesSql($attributes)." FROM ".$tableName." WHERE ".$terms;

        return $this->findBySQL($sql);
    }

    public function findBySQL($sql = '')
    {
        if(!is_string($sql) || $sql === '')
            return $this->findAll();

        $statement = $this->pdoPersistent->query($sql);

        $statement->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
        $result = array();

        while($row = $statement->fetch()){
            $result[]=$row;
        }

        if (!$result) {
            throw new \exceptions\DataLayerException($this->parseError($statement->errorInfo()));
        }

        return $result;
    }

    public function deleteModel($id = null)
    {
        if(!$id)
            throw new \exceptions\DataLayerException('no index to delete');


        if(is_array($id)){
            if(isset($id['where'])){
                $where = $this->getWhereSql($id['where']);
                unset($id['where']);
            }else{
                $where = " WHERE ".$this->getInSql($id, 'id');
            }
        }else{
            $where = " WHERE id = ".(int)$id;
        }

        $sql = "DELETE FROM ".$this->tableName().$where;

        return $this->pdoPersistent->query($sql);
    }

    public function beginTransaction()
    {
        return $this->pdoPersistent->beginTransaction();
    }

    public function transactionCommit()
    {
        return $this->pdoPersistent->commit();
    }

    public function transactionRollBack()
    {
        return $this->pdoPersistent->rollBack();
    }

    protected function getIndexWhere()
    {
        $indexName = $this->indexName();

        $result = '';

        if(is_array($indexName) && $indexName){
            foreach($this->indexName() as $name=>$value){
                $result = "$name = '$value'";
                break;
            }
        }

        return $result;
    }

    protected function getAttributesSql($attributes = [])
    {
        $sql = '';

        if(!$attributes){
            $sql .= "*";
        }else{
            foreach($attributes  as $attribute){
                $sql .= "$attribute, ";
            }

            $sql = substr($sql, 0, strlen($sql)-2);
        }

        return $sql;
    }

    protected function getWhereSql($whereAttributes = [])
    {
        if(!is_array($whereAttributes) || empty($whereAttributes))
            return "";

        $where = " WHERE ";

        foreach($whereAttributes as $name => $attribute){
            $where .= "$name = '$attribute' AND ";
        }

        $where = substr($where, 0, strlen($where)-4);

        return $where;
    }

    // Only for integer!
    protected function getInSql($attributes = [], $key = '')
    {
        if(!is_array($attributes) || empty($attributes))
            return '';

        if(!$key)
            $key = 'id';

        $terms = $key." in (";

        foreach($attributes as $id){
            if(strpos($id, '"') !== false)
                $id = trim(str_replace('"', '', $id));

            $terms .= intval($id).", ";
        }

        if($terms == $key." in (")
            $terms = "";
        else{
            $terms = substr($terms, 0, strlen($terms)-2);
            $terms .= ")";
        }

        return $terms;
    }

    protected function runQuery($sql, \PDO $pdo, array $data){
        $statement = $pdo->prepare($sql);
        $result = $statement->execute($data);
        if(!$result){
            throw new DataLayerException('cannot execute query' . $statement->errorInfo()[2]);
        }
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function parseError($error)
    {
        $result = '';

        if(is_string($error)){
            $result = $error;
        }elseif(is_array($error)){
            if(isset($error[2]))
                $result = $error[2];
            else
                $result = implode(', ', $error);
        }

        $result = trim(htmlspecialchars($result));

        $result = str_replace('DETAIL:  ', ', ', $result);

        return strpos($result, 'ERROR:') === false ? $result : str_replace('ERROR:', '', $result);
    }
}