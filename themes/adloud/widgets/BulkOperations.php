<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.05.14
 * Time: 16:02
 * @property ControllerAdvertiser $controller
 */

class BulkOperations extends CWidget
{
    public $urlOptions = [];

    public function init()
    {

    }

    public function run()
    {
        $this->render('bulkOperations', [
            'operations' => $this->controller->bulkOperations,
            'urlOptions' => $this->urlOptions,
        ]);
    }
} 