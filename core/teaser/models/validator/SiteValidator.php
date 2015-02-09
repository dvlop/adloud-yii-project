<?php
/**
 * Created by t0m
 * Date: 02.02.14
 * Time: 15:35
 */

namespace models\validator;
use \models\Site;
/**
 * @property Site $nextLayer
 */
class SiteValidator extends ValidatorLayer{
    public function save(){
        if ($this->nextLayer->getId() !== null) {
            throw new \exceptions\ValidationException('this object is already initialized. Create new instance or use "update" method');
        }

        if (empty($this->nextLayer->description)) {
            throw new \exceptions\ValidationException('description is not set');
        }

        if (!filter_var($this->nextLayer->url, FILTER_VALIDATE_URL)) {
            throw new \exceptions\ValidationException('siteId is not set');
        }

        if (!is_array($this->nextLayer->categories)) {
            throw new \exceptions\ValidationException('categories is not set');
        }
        return $this->nextLayer->save();
    }
}