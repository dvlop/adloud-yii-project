<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 30.07.14
 * Time: 12:15
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\ticket;

class TicketModule extends \CWebModule
{
    public $controllerNamespace = '\application\modules\ticket\controllers';

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
            //'social.models.*',
            //'social.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }
}