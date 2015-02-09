<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.06.14
 * Time: 13:55
 */

use application\components\ControllerBase;

class GeoController extends ControllerBase
{
    public function actionGetCountries($campaignId = null)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['getRegions'])){
            $json = [
                'html' => '',
                'error' => '',
                'message' => '',
            ];

            $model = new CampaignForm();

            $model->setCampaign($campaignId);

            $json['html'] = $this->renderPartial('getCountries', ['model' => $model, 'campaignId' => $campaignId], true);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionGetRegions($campaignId = null, $countryId = null)
    {
        if($countryId !== null && \Yii::app()->request->isAjaxRequest && isset($_POST['getRegions'])){
            $json = [
                'html' => '',
                'error' => '',
                'message' => '',
            ];

            $model = new CampaignForm();
            $model->setCampaign($campaignId);

            $json['html'] = $this->renderPartial('getRegions', ['model' => $model, 'countriesId' => [$countryId], 'campaignId' => $campaignId], true);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionGetCities($campaignId = null, $regionId = null)
    {
        if($regionId !== null && \Yii::app()->request->isAjaxRequest && isset($_POST['getRegions'])){
            $json = [
                'html' => '',
                'error' => '',
                'message' => '',
            ];

            $model = new CampaignForm();
            $model->setCampaign($campaignId);

            $json['html'] = $this->renderPartial('getCities', ['model' => $model, 'regionsId' => [$regionId], 'campaignId' => $campaignId], true);

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }
}