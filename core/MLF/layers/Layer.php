<?php
/**
 * Created by t0m
 * Date: 16.12.13
 * Time: 22:47
 */

namespace MLF\layers;

use MLF\exceptions\MethodNotFoundException;

abstract class Layer extends Decorable {

    public function __construct(Decorable $nextLayer = null){
        $this->nextLayer = $nextLayer;
    }

    public function __call($method, $params)
    {
        $before = $this->beforeAnyMethodRun($method, $params);
        if($before !== true){
            return $before;
        }
        if($this->nextLayer instanceof Logic && !method_exists($this->nextLayer, $method)){
            throw new MethodNotFoundException("Method {$method} not found");
        }

        return $this->afterAnyMethodRun(call_user_func_array(array($this->nextLayer, $method), $params));
    }

    protected function beforeAnyMethodRun($method, $params){
        return true;
    }

    protected function afterAnyMethodRun($methodResult){
        return $methodResult;
    }

}