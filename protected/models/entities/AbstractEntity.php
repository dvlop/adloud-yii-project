<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 22.08.14
 * Time: 13:35
 */

namespace application\models\entities;


class AbstractEntity extends \CComponent
{
    protected $_error;

    public function __construct($content)
    {
        if(is_string($content)){
            $content = \CJSON::decode($content);
        }

        if(is_array($content)){
            foreach($content as $name=>$value){
                if(property_exists($this, $name)){
                    if(is_string($value))
                        $value = trim($value);
                    $this->$name = $value;
                    unset($content[$name]);
                }
            }
        }

        $this->initialise($content);
    }

    public function getError()
    {
        return $this->_error;
    }

    public function initialise($content)
    {

    }
} 