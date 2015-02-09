<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 28.07.14
 * Time: 14:50
 * To change this template use File | Settings | File Templates.
 */

namespace models;

use MLF\layers\Logic;
use config\Config;

/**
 * @property dataSource\ReferalsDataSource $nextLayer
 * @property \config\Config $config
 */
class Referals extends Logic {

    /**
     * @return \models\Referals
     */
    public static function getInstance() {
        return parent::getInstance();
    }

    public function getRequestList(array $params){
        return $this->nextLayer->getRequestList($params);
    }

    public function acceptRequest($id){
        return $this->nextLayer->acceptRequest($id);
    }

    public function denyRequest($id){
        return $this->nextLayer->denyRequest($id);
    }
}