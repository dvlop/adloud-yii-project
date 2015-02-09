<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.08.14
 * Time: 19:04
 */

namespace application\console;


class Console extends \CApplicationComponent
{
    protected $_error;
    protected $_message = '> All done!';

    public function __construct(array $params)
    {
        $this->initialise($params);
    }

    public function initialise(array $params)
    {

    }

    protected function setError($text)
    {
        if($this->_error === null)
            $this->_error = [];

        $this->_error[] = $text;
    }

    public function getError()
    {
        if(is_array($this->_error))
            return implode('; ', $this->_error);
        else
            return '';
    }

    public function getErrors()
    {
        return $this->_error ? $this->_error : [];
    }

    public function setMessage($text)
    {
        if($this->_message === null)
            $this->_message = '';

        if($this->_message)
            $this->_message .= '\n';

        $this->_message .= $text;
    }

    public function getMessage()
    {
        return $this->_message;
    }

    public function run()
    {
        return true;
    }
} 