<?php
namespace models\validator;
use models\Ads;

/**
 * @property Ads $nextLayer
 */
class AdsValidator extends ValidatorLayer
{
    public function save(){
        if ($this->nextLayer->getId() !== null) {
            throw new \exceptions\ValidationException('this object is already initialized. Create new instance or use "update" method');
        }

        if (!is_array($this->nextLayer->additionalCategories)) {
            throw new \exceptions\ValidationException('mainCategory is not set');
        }

        if (!filter_var($this->nextLayer->campaignId, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('campaignId is not set');
        }

        if (!empty($this->nextLayer->clickPrice) && !filter_var($this->nextLayer->clickPrice, FILTER_VALIDATE_FLOAT)) {
            throw new \exceptions\ValidationException('clickPrice is not set');
        }

        if (!empty($this->nextLayer->maxClicks) && !filter_var($this->nextLayer->maxClicks, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('maxClicks is not set');
        }

        return $this->nextLayer->save();
    }

    public function update(){
        if ($this->nextLayer->getId() == null) {
            throw new \exceptions\ValidationException('this object is not initialized');
        }




        if (!empty($this->nextLayer->clickPrice) && !filter_var($this->nextLayer->clickPrice, FILTER_VALIDATE_FLOAT)) {
            throw new \exceptions\ValidationException('clickPrice is not set');
        }

        if (!empty($this->nextLayer->maxClicks) && !filter_var($this->nextLayer->maxClicks, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('clickPrice is not set');
        }

        return $this->nextLayer->update();
    }
}