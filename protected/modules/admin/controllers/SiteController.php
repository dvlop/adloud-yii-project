<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.07.14
 * Time: 14:19
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\Sites;
use models\Site;

class SiteController extends ControllerAdmin
{
    public function actionIndex($id, $state, $status = null)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['setSiteStatus'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            $id = intval($id);
            $state = intval($state);

            if($model = Sites::model()->findByPk($id)){
                if($model->updateStatus($state)){
                    if($status == Sites::STATUS_ALL)
                        $json['html'] = $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.admin.site._siteTableRow', ['site' => $model, 'status' => $status], true);
                    else
                        $json['html'] = 'remove-this';

                    switch($state){
                        case 3:
                            $this->sendNotification($model->getUserId(),'site-moderated');
                            break;
                        case 200:
                            $this->sendNotification($model->getUserId(),'site-declined');
                            break;
                    }
                } else {
                    $json['error'] = $this->parseError($model->errors, 'К сожалению не удалось изменить статус сайта');
                }
            }else{
                $json['error'] = 'Не найден сайт ID '.$id;
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionList($status = null)
    {
        $model = new Sites();
        $model->setStatus($status);

        $attributes = [];
        if($model->status != Sites::STATUS_ALL){
            $status = $model->status == Site::STATUS_PUBLISHED ? [Sites::STATUS_DISABLED, Site::STATUS_PUBLISHED] : $model->status;
            $attributes = ['status' => $status];
        }

        $this->render('list', [
            'sites' => $model->findAllByAttributes($attributes, ['order' => 'id desc']),
            'model' => $model,
        ]);
    }
} 