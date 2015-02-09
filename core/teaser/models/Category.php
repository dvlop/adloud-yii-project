<?php

namespace models;
use models\dataSource\CategoryDataSource;

/**
 * @property \models\dataSource\CategoryDataSource $nextLayer
 */
class Category extends \MLF\layers\Logic implements \core\CRUDInterface
{

    public $description;
    public $minimumClickPrice;
    private $id;

    /**
     * @return \models\Category
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function getId(){
        return $this->getId();
    }

    public function getList()
    {
        return $this->nextLayer->getList();
    }

    public function getCategoryNames(array $ids){
        return $this->nextLayer->getCategoryNames($ids);
    }

    public function save($attributes = [])
    {
        return true;
    }

    public function initById($id)
    {
        return true;
    }

    public function update($id = null)
    {
        return true;
    }

    public function delete($id)
    {
        return true;
    }


}