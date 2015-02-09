<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 16.02.14
 * Time: 23:00
 */

namespace models\validator;


use exceptions\ValidationException;
use models\BlockRenderer;
/**
 * @property BlockRenderer $nextLayer
 */
class BlockRendererValidator extends ValidatorLayer {

    public function render($id, $blockParams){
        if(!filter_var($id, FILTER_VALIDATE_INT)){
            throw new \exceptions\ValidationException('invalid id');
        }

        return $this->nextLayer->render($id, $blockParams);
    }


} 