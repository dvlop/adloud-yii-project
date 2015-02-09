<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.08.14
 * Time: 13:23
 * To change this template use File | Settings | File Templates.
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\UserAgent;

class UseragentController extends ControllerAdmin {

    public function actionList($type = 'device')
    {

        $model = UserAgent::model();

        $uaList = $model->getList([
            'type' => $type
        ]);

        usort($uaList, function($a,$b){
            return $b->shows - $a->shows;
        });

        $this->render('list', [
            'uaList' => $uaList,
            'type' => $type
        ]);
    }

    public function actionAllow($id = null)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['is_checked'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => ''
            ];
            $model = UserAgent::model();

            $model->setActualConnection();
            UserAgent::model()->updateByPk($id,[
                'is_checked' => TRUE
            ]);
            $model->setPersistentConnection();

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionBan($id = null)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['is_checked'])){
            $json = [
                'error' => '',
                'message' => '',
                'html' => ''
            ];
            $model = UserAgent::model();

            $model->setActualConnection();
            UserAgent::model()->updateByPk($id,[
                'is_checked' => FALSE
            ]);
            $model->setPersistentConnection();

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}