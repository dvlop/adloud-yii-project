<?php
namespace models\validator;
use models\Campaign,
    core\Session;

/**
 * @property Campaign $nextLayer
 */
class CampaignValidator extends ValidatorLayer
{
    public function getById($id) {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('userId is not set or incorrect');
        }

        if (!filter_var(Session::getInstance()->getUserId(), FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('userId is not set or incorrect');
        }

        return $this->nextLayer->getById($id);
    }

    public function save()
    {
        if ($this->nextLayer->getId() !== null) {
            throw new \exceptions\ValidationException('this object is already initialized. Create new instance or use "update" method');
        }

        if (empty($this->nextLayer->description)) {
            throw new \exceptions\ValidationException('description is not set');
        }

        if (!filter_var(Session::getInstance()->getUserId(), FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('userId is not set or incorrect');
        }

        if (!filter_var($this->nextLayer->clickPrice, FILTER_VALIDATE_FLOAT)) {
            throw new \exceptions\ValidationException('clickPrice is not set');
        }

        return $this->nextLayer->save();
    }

    public function delete($id = null){
        if ($this->nextLayer->getId() === null && !filter_var($id, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('invalid id');
        }

        return $this->nextLayer->delete($id);
    }
}