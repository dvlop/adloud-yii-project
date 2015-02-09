<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class CountriesIpDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'geo_net_country_ip';
    }

    public function getDiapasonsByCountriesId($arrayOfCountriesId = [])
    {
        if(!is_array($arrayOfCountriesId) || empty($arrayOfCountriesId))
            return array();

        $terms = $this->getInSql($arrayOfCountriesId, 'country_id');
        if(!$terms)
            return array();

        return $this->findByTerms($terms);
    }
}