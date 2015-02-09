<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class RegionDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'region';
    }

    public function getRegionsByCountriesId($attributes = [], $arrayOfCountriesIds = [])
    {
        if(!$arrayOfCountriesIds){
            $arrayOfCountriesIds = $attributes;
            $attributes = array();
        }

        if(!is_array($arrayOfCountriesIds) || empty($arrayOfCountriesIds))
            return $this->findAll($attributes);

        $terms = $this->getInSql($arrayOfCountriesIds, 'country_id');

        if(!$terms)
            return $this->findAll($attributes);

        return $this->findByTerms($attributes, $terms);
    }

    public function getRegionsAndCountries($arrayOfCountriesIds = [])
    {
        if(!is_array($arrayOfCountriesIds) || empty($arrayOfCountriesIds))
            return array();

        $terms = $this->getInSql($arrayOfCountriesIds, 'country_id');

        $sql = "SELECT region.id, country.id AS country_id, region.name AS region_name, country.name AS country_name
                FROM region
                LEFT JOIN country ON (region.country_id = country.id)
                WHERE {$terms}";

        return $this->findBySQL($sql);
    }

    public function getRegionsById($attributes = [], $arrayOfRegionsId = [])
    {
        if(isset($attributes['data-test'])){

        }
        if(!$arrayOfRegionsId){
            $arrayOfRegionsId = $attributes;
            $attributes = array();
        }

        if(!is_array($arrayOfRegionsId) || empty($arrayOfRegionsId))
            return array();

        $terms = $this->getInSql($arrayOfRegionsId);
        if(!$terms)
            return array();

        return $this->findByTerms($attributes, $terms);
    }
}