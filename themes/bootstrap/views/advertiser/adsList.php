<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/10/14
 * Time: 1:26 AM
 */

?>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th></th>
        <th>Название</th>
        <th>Затраты</th>
        <th>Кликов</th>
        <th>Показов</th>
        <th>CTR (%)</th>
        <th>Стоимость клика</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
    <?php if(empty($adsList)):?>
        <tr>
            <td colspan="8">
                <div class="alert alert-danger" style="text-align: center;">
                    Нет ни одного обьявления! Но вы можете его добавить, нажав на кнопку ниже
                </div>
            </td>
        </tr>
    <?php else:?>
        <?php foreach($adsList AS $adv):?>
            <tr class="<?php echo !empty($createdAds) && $createdAds == $adv['id'] ? 'active' : '';?>"
                data-poload="<?php echo Yii::app()->createUrl("advertiser/ads/getAd", array('id'=>$adv['id']));?>"
                >
                <td>
                    <?php if($adv['moderated'] == 1):?>
                        <i class="icon-check-sign icon-color-green icon-no-border"
                           data-tooltip="tooltip" data-placement="top" title="Обьявление модерировано"
                            ></i>
                    <?php elseif($adv['moderated'] == 2):?>
                        <i class="icon-check-sign icon-color-red icon-no-border"
                           data-tooltip="tooltip" data-placement="top" title="Обьявлению отказано в модерации"
                            ></i>
                    <?php else:?>
                        <i class="icon-check-sign icon-color-grey icon-no-border"
                           data-tooltip="tooltip" data-placement="top" title="Обьявление ждет модерацию"
                            ></i>
                    <?php endif;?>
                </td>
                <td>
                    <?php echo $adv['content']['urlText'];?>
                </td>
                <td><?php echo round($adv['clicks'] * $adv['clickPrice'], 2);?> $</td>
                <td><?php echo $adv['clicks'] > 0 ? $adv['clicks'] : 0;?></td>
                <td><?php echo $adv['shows'] > 0 ? $adv['shows'] : 0;?></td>
                <td><?php echo $adv['shows'] > 0 ? round($adv['clicks'] / $adv['shows'], 3) * 100 : 0;?></td>
                <td><?php echo round($adv['clickPrice'], 2);?> $</td>
                <td>
                    <a
                        href="<?php echo Yii::app()->createUrl("advertiser/ads", ['campaignId' => $adv['campaignId'], 'id'=>$adv['id']]);?>"
                        data-tooltip="tooltip" data-placement="top" title="Редактировать обьявление"
                        class="btn btn-default btn-xs">
                        <i class="icon-edit"></i>
                    </a>
                    <?php if($adv['status']):?>
                        <button
                            data-toggle="modal"
                            data-target="#modalAdsUnPublish"
                            data-url="<?php echo Yii::app()->createUrl("advertiser/adsUnPublish", ['id'=>$adv['id'], 'campaignId' => $adv['campaignId']]);?>"
                            data-tooltip="tooltip" data-placement="top" title="Отменить публикацию обьявления"
                            class="btn btn-warning btn-xs btnAdsUnPublish">
                            <i class="icon-pause"></i>
                        </button>
                    <?php else:?>
                        <button
                            data-toggle="modal"
                            data-target="#modalAdsPublish"
                            data-url="<?php echo Yii::app()->createUrl("advertiser/ads/publish", ['id'=>$adv['id'], 'campaignId' => $adv['campaignId']]);?>"
                            data-tooltip="tooltip" data-placement="top" title="Опубликовать обьявление"
                            class="btn btn-success btn-xs btnAdsPublish">
                            <i class="icon-play"></i>
                        </button>
                    <?php endif;?>

                    <button data-toggle="modal" data-target="#modalAdsDelete" class="btn btn-danger btn-xs btnAdsDelete"
                            data-url="<?php echo Yii::app()->createUrl("advertiser/ads/delete", ['id'=>$adv['id'], 'campaignId' => $adv['campaignId']]);?>"
                            data-tooltip="tooltip" data-placement="top" title="Удалить обьявление"
                            ><i class="icon-remove"></i></button>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    </tbody>
</table>

