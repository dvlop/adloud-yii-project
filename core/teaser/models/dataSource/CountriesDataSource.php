<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class CountriesDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'geo_net_country';
    }

    public function getIpDiapasons($arrayOfCountriesId = [])
    {
        return (new CountriesIpDataSource())->getDiapasonsByCountriesId($arrayOfCountriesId);
    }
}