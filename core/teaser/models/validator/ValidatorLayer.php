<?php

namespace models\validator;
/**
 * @property \core\CRUDInterface $nextLayer
 */
class ValidatorLayer extends \MLF\layers\Layer
{
    public function initById($id){
        if(!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('ID is not set');
        }
        return $this->nextLayer->initById($id);
    }

    public function delete($id){
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('ID is not set');
        }
        return $this->nextLayer->delete($id);
    }
}