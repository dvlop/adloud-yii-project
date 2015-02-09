<?php
namespace models\validator;
use models\Block;

/**
 * @property Block $nextLayer
 */
class BlockValidator extends ValidatorLayer {

    public function save(){

        if ($this->nextLayer->getId() !== null) {
            throw new \exceptions\ValidationException('this object is already initialized. Create new instance or use "update" method');
        }

        if (empty($this->nextLayer->description)) {
            throw new \exceptions\ValidationException('description is not set');
        }

        if (!filter_var($this->nextLayer->siteId, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('siteId is not set');
        }

        if (!filter_var($this->nextLayer->adsNumberRows, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('adsNumberRows is not set');
        }

        if (!filter_var($this->nextLayer->adsNumberColumns, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('adsNumberColumns is not set');
        }

        if (!is_array($this->nextLayer->categories)) {
            throw new \exceptions\ValidationException('categories is not set');
        }
        return $this->nextLayer->save();
    }

    public function getList($siteId = false, $limit = 100, $offset = 0){
        return $this->nextLayer->getList($siteId, $limit, $offset);
    }

    public function getInsertCode(){
        if ($this->nextLayer->getId() == null) {
            throw new \exceptions\ValidationException('this object is not initialized');
        }
        return $this->nextLayer->getInsertCode();
    }

    public function getBlockIncome(){
        if ($this->nextLayer->getId() == null) {
            throw new \exceptions\ValidationException('this object is not initialized');
        }
        return $this->nextLayer->getBlockIncome();
    }

}