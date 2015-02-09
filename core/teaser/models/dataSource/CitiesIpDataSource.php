<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class CitiesIpDataSource extends DataSourceLayer
{
    public function tableName()
    {
        return 'geo_net_city_ip';
    }

    public function getDiapasonsByCitiesId($arrayOfCitiesId = [])
    {
        if(!is_array($arrayOfCitiesId) || empty($arrayOfCitiesId))
            return array();

        $terms = $this->getInSql($arrayOfCitiesId, 'city_id');
        if(!$terms)
            return array();

        return $this->findByTerms($terms);
    }

    public function delete($id = null)
    {
        return $this->deleteModel($id);
    }
}