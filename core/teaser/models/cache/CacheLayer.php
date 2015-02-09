<?php

namespace models\cache;
class CacheLayer extends \MLF\layers\Layer
{
    protected function beforeAnyMethodRun($method, $params)
    {
        return true;
    }

    protected function afterAnyMethodRun($result)
    {
        return $result;
    }
}