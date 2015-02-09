<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class CityDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'city';
    }

    public function getCitiesByCountriesId($attributes = [], $arrayOfCountriesIds = [])
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

    public function getCitiesAndCountries($arrayOfCountriesIds = [])
    {
        if(!is_array($arrayOfCountriesIds) || empty($arrayOfCountriesIds))
            return array();

        $terms = $this->getInSql($arrayOfCountriesIds, 'country_id');

        $sql = "SELECT city.id, country.id AS country_id, city.name AS city_name,  country.name AS country_name
                FROM city
                LEFT JOIN country ON (city.country_id = country.id)
                WHERE {$terms}";

        return $this->findBySQL($sql);
    }

    public function getCitiesAndRegions($arrayOfRegionsIds = [])
    {
        if(!is_array($arrayOfRegionsIds) || empty($arrayOfRegionsIds))
            return array();

        $terms = $this->getInSql($arrayOfRegionsIds, 'region_id');

        $sql = "SELECT city.id, region.id AS region_id, city.name AS city_name,  region.name AS region_name
                FROM city
                LEFT JOIN region ON (city.region_id = region.id)
                WHERE {$terms}";

        return $this->findBySQL($sql);
    }

    public function getCitiesByRegionsId($attributes = [], $arrayOfRegionsIds = [])
    {
        if(!$arrayOfRegionsIds){
            $arrayOfRegionsIds = $attributes;
            $attributes = array();
        }

        if(!is_array($arrayOfRegionsIds) || empty($arrayOfRegionsIds))
            return $this->findAll($attributes);

        $terms = $this->getInSql($arrayOfRegionsIds, 'region_id');

        if(!$terms)
            return $this->findAll($attributes);

        return $this->findByTerms($attributes, $terms);
    }

    public function getCitiesById($attributes = [], $arrayOfCitiesId = [])
    {
        if(!$arrayOfCitiesId){
            $arrayOfCitiesId = $attributes;
            $attributes = array();
        }

        if(!is_array($arrayOfCitiesId) || empty($arrayOfCitiesId))
            return array();

        $terms = $this->getInSql($arrayOfCitiesId);
        if(!$terms)
            return array();

        return $this->findByTerms($attributes, $terms);
    }

    public function getIpDiapasons($arrayOfCitiesId = [])
    {
        return (new CitiesIpDataSource())->getDiapasonsByCitiesId($arrayOfCitiesId);
    }

    public function getCityById($cityId = null)
    {
        if(!$cityId)
            return false;;

        if(is_array($cityId)){
            $terms = $this->getInSql($cityId, 'id');
            if(!$terms)
                return array();

            return $this->findByTerms(['id', 'name', 'region_id', 'country_id'], $terms);
        }else{
            return $this->find($cityId);
        }
    }
}