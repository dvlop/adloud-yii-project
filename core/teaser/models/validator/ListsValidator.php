<?php

namespace models\validator;
use models\Lists;

/**
 * @property Lists $nextLayer
 */

class ListsValidator extends ValidatorLayer
{
    public function save(){
        if ($this->nextLayer->getId() !== null) {
            throw new \exceptions\ValidationException('this object is already initialized. Create new instance or use "update" method');
        }

        if (!is_string($this->nextLayer->name) || $this->nextLayer->name) {
            throw new \exceptions\ValidationException('name is not set');
        }

        if (!is_array($this->nextLayer->sites) || $this->nextLayer->sites) {
            throw new \exceptions\ValidationException('sites is not set');
        }

        if (!is_array($this->nextLayer->campaigns) || !$this->nextLayer->campaigns) {
            throw new \exceptions\ValidationException('campaigns is not set');
        }

        $this->nextLayer->userId = intval($this->nextLayer->userId);
        if (!$this->nextLayer->userId) {
            throw new \exceptions\ValidationException('user id is not set');
        }

        $this->nextLayer->description = (string)$this->nextLayer->description;

        return $this->nextLayer->save();
    }

    public function update(){
        if ($this->nextLayer->getId() == null) {
            throw new \exceptions\ValidationException('this object is not initialized');
        }

        return $this->nextLayer->update();
    }

    public function delete()
    {
        return $this->nextLayer->delete($this->nextLayer->getId());
    }
} 