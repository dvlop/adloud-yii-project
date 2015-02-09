<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:24 PM
 * @property ControllerAdvertiser $controller
 */
class SideMenu extends CWidget
{
    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()
    }

    public function run()
    {
        $menu = array();

        foreach($this->controller->sideMenu as $item){
            if(isset($item['menu'])){
                $menu = array_merge($menu, $item['menu']);
            }
        }

        $url = Yii::app()->request->requestUri;

        foreach($menu as $num=>$item){
            if($item['url'] == $url || $item['url'] == $url.'/'){
                $menu[$num]['class'] = 'active';
            }else{
                $menu[$num]['class'] = '';
            }
        }

        $this->render('sideMenu', array('menu' => $menu));
    }
}