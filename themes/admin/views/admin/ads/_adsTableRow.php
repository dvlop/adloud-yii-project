<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 22.09.14
 * Time: 12:45
 * @var \application\modules\admin\controllers\AdsController $this
 * @var integer $status
 * @var \application\models\Ads $ads
 */
?>

<?php use application\models\Ads; ?>

<td><?php echo $ads->id; ?></td>
<td><img src="<?php echo $ads->getImage(); ?>" width="75px" /></td>
<td><?php echo $ads->getCaption(); ?></td>
<td><a href="<?php echo $ads->getUrl(); ?>" target="_blank"><?php echo $ads->getShowUrl(); ?></a></td>
<td><?php echo round($ads->getClickPrice(), 2);?> $</td>
<td>
    <a
        href="#"
        class="auto-modal"
        data-content="#asd-cats-list-<?php echo $ads->id; ?>"
        data-title="Список категорий"
        data-notshowsubmit="1"
    >
        Показать список
    </a>
    <div id="asd-cats-list-<?php echo $ads->id; ?>" class="hide"><?php echo $ads->getCategoriesText(); ?></div>
</td>
<td>
    <input
        type="checkbox"
        class="ads-checkbox"
        data-url="<?php echo Yii::app()->createUrl('admin/ads/setShock', ['id' => $ads->id]); ?>"
        <?php if($ads->shock) echo 'checked="checked"' ?>
    />
</td>
<td>
    <input
        type="checkbox"
        class="ads-checkbox"
        data-url="<?php echo Yii::app()->createUrl('admin/ads/setAdult', ['id' => $ads->id]); ?>"
        <?php if($ads->adult) echo 'checked="checked"' ?>
    />
</td>
<td><?php echo $ads->getStatusName(); ?></td>
<td>
    <?php if($ads->getIsConfirm()): ?>
        <button
            class="btn btn-success btn-xs tooltips auto-ajax"
            data-url="<?php echo \Yii::app()->createUrl('admin/ads/setStatus', ['id' => $ads->id, 'state' => Ads::STATUS_PUBLISHED, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="setAdsStatus=1"
            data-original-title="Подтвердить"
            data-toggle="tooltip"
            data-placement="top"
            title=""
            >
            <i class="fa fa-check"></i>
        </button>
    <?php endif; ?>

    <?php if($ads->getIsReject()): ?>
        <button
            class="btn btn-danger btn-xs tooltips auto-ajax"
            data-url="<?php echo \Yii::app()->createUrl('admin/ads/setStatus', ['id' => $ads->id, 'state' => Ads::STATUS_PROHIBITED, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="setAdsStatus=1"
            data-original-title="Отклонить"
            data-toggle="tooltip"
            data-placement="top"
            title=""
            >
            <i class="fa fa-times"></i>
        </button>
    <?php endif; ?>
</td>