<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 09.06.14
 * Time: 16:06
 */

namespace application\modules\webmaster;

class WebmasterModule extends \CWebModule
{
    public $controllerNamespace = '\application\modules\webmaster\controllers';

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