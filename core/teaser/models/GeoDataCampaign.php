<?php
namespace models;
use core\CRUDInterface;
use core\Session;
use models\dataSource\GeoDataCampaignDataSource;

/**
 * @property GeoDataCampaignDataSource $nextLayer
 */
class GeoDataCampaign extends \MLF\layers\Logic implements CRUDInterface
{
    /*
     * @ var int
     */
    public $campaign_id;

    /*
     * @ var int
     */
    public $country_id;

    /*
     * @ var int
     */
    public $city_id;

    /*
     * @ var int
     */
    public $begin_ip;

    /*
     * @ var int
     */
    public $end_ip;


    /**
     * @return \models\GeoDataCampaign
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function initById($id)
    {

    }

    public function getId()
    {
        return $this->campaign_id;
    }

    public function update()
    {
        //
    }

    public function save()
    {
        $data = [
            'campaign_id'   => $this->campaign_id,
            'country_id'    => $this->country_id,
            'city_id'       => $this->city_id,
            'begin_ip'      => $this->begin_ip,
            'end_ip'        => $this->end_ip
        ];

        return $this->nextLayer->save($data);
    }

    public function delete($id)
    {
        return true;
    }

    public function saveDiapasons($diapasons = [], $id = null)
    {
        return $this->nextLayer->saveDiapasons($diapasons, $id);
    }

    public function deleteByCampaignId($campaignId = null)
    {
        return $this->nextLayer->deleteByCampaignId($campaignId);
    }
}