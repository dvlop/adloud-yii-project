<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 15.04.14
 * Time: 10:51
 */

class Test extends CApplicationComponent
{
    public function show($something, $isDump = false)
    {
        echo '<pre>';
        if(!$isDump) print_r($something);
        else var_dump($something);
        \Yii::app()->end('</pre>');
    }
}