<?php


namespace models\dataSource;
use core\RedisIO;
use core\MoneyTransaction;
use exceptions\DataLayerException;
use PDO;
use core\TransactionManager;

class GeoDataCampaignDataSource extends DataSourceLayer
{
    public $id = 0;

    public function tableName()
    {
        return 'geo_data_campaign';
    }

    public function indexName()
    {
        return ['campaign_id' => $this->id];
    }

    public function save($data = [])
    {
        echo '<pre>'; print_r($data); exit();
    }

    public function getByCampaignId($attributes = [], $campaignId = null)
    {
        $geoData = array();
        if(!is_array($attributes)){
            $campaignId = $attributes;
            $attributes = artray();
        }

        if(!$campaignId)
            return $geoData;

        if(is_array($campaignId)){
            foreach($campaignId as $id){
                $geoData = array_merge($geoData, $this->getByCampaignId($id));
            }
        }else{
            return $this->findAll(array_merge($attributes, ['where' => ['campaign_id' => $campaignId]]));
        }

        return $geoData;
    }

    public function saveDiapasons($diapasons = [], $campaign_id = null)
    {
        if(!$diapasons || !$campaign_id)
            return true;

        try{
            $isNew = !$this->find(['where' => ['campaign_id' => $campaign_id]]);
        }catch(\Exception $e){
            $isNew = true;
        }

        if(!$isNew){
            if(!$this->deleteByCampaignId($campaign_id)){
                throw new \exceptions\DataLayerException('can not update Geo data');
            }
        }

        $indexes = "(campaign_id, country_id, city_id, begin_ip, end_ip)";
        $values = "";

        if(is_array($diapasons)){
            foreach($diapasons as $diapasone)
            {
                $country_id = isset($diapasone->country_id) ? $diapasone->country_id : null;
                $city_id = isset($diapasone->city_id) ? $diapasone->city_id : null;

                $values .= "(".(int)$campaign_id.", ".(int)$country_id.", ".(int)$city_id.", ".(int)$diapasone->begin_ip.", ".(int)$diapasone->end_ip."), ";
            }

            $values = substr($values, 0, strlen($values)-2);

            $sql = 'INSERT INTO '.$this->tableName()." $indexes VALUES $values";

            return $this->pdoPersistent->query($sql);
        }else{
            return true;
        }
    }

    public function deleteByCampaignId($campaignId = null)
    {
        if(!$campaignId)
            return false;

        $sql = "DELETE FROM ".$this->tableName()." WHERE campaign_id ";

        if(is_array($campaignId)){
            $sql .= "IN (";
            foreach($campaignId as $id){
                $sql .= (int)$id.", ";
            }
            $sql = substr($sql, 0, strlen($sql)-2).")";
        }else{
            $sql .= "= ".(int)$campaignId;
        }

        return $this->pdoPersistent->query($sql);
    }
}