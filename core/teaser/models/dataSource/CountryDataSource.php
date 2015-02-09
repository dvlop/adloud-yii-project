<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class CountryDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'country';
    }

    public function getCountriesById($attributes = [], $arrayOfIds = [])
    {
        if(!$arrayOfIds){
            $arrayOfIds = $attributes;
            $attributes = array();
        }

        if(!is_array($arrayOfIds) || empty($arrayOfIds))
            return array();

        $terms = $this->getInSql($arrayOfIds);

        if(!$terms)
            return array();

        return $this->findByTerms($attributes, $terms);
    }
}