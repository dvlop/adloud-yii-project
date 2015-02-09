<?php
/**
 * Created by PhpStorm.
 * User: M-A-X
 * Date: 10.07.14
 * Time: 13:50
 * @property array $sites
 * @property array $blocks
 */

class StatsForm extends CFormModel
{
    public $arParams;

    private $_sites;
    private $_blocks;

    public function getSites()
    {
        if($this->_sites === null){
            $this->_sites = [];

            try{
                $this->_sites = \models\Stats::getInstance()->getSites($this->arParams);
            }catch(Exception $e){
                $this->addError(null, $e->getMessage());
            }
        }

        return $this->_sites;
    }

    public function getBlocks()
    {
        if($this->_blocks === null){
            $this->_blocks = [];

            try{
                $this->_blocks = \models\Stats::getInstance()->getBlocks($this->arParams);
            }catch(Exception $e){
                $this->addError(null, $e->getMessage());
            }
        }

        return $this->_blocks;
    }

    public function getSortOrder($sortBy)
    {
        $sortOrder = 'asc';

        if($this->arParams['sortBy'] == $sortBy && $this->arParams['sortOrder'] == 'asc')
        {
            $sortOrder = 'desc';
        }
        return $sortOrder;
    }

    public function getSortLink($sortBy)
    {
        return '?sortBy='.$sortBy.'&sortOrder='.$this->getSortOrder($sortBy);
    }

}