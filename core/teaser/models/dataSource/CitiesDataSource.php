<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class CitiesDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'geo_net_city';
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

        $whereIdIn = "WHERE city.country_id IN (";

        foreach($arrayOfCountriesIds as $id){
            $whereIdIn .= (int)$id.", ";
        }

        $whereIdIn = substr($whereIdIn, 0, strlen($whereIdIn)-2).")";

        $sql = "SELECT city.id, country.id AS country_id, city.name_en AS city_name_en, city.name_ru AS city_name_ru, country.name_en AS country_name_en,  country.name_ru AS country_name_ru
                FROM geo_net_city as city
                LEFT JOIN geo_net_country  as country ON (city.country_id = country.id)
                {$whereIdIn}";

        return $this->findBySQL($sql);
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

            return $this->findByTerms(['id', 'name_ru', 'name_en', 'country_id'], $terms);
        }else{
            return $this->find($cityId);
        }
    }
}