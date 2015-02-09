<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 14.08.14
 * Time: 12:11
 * @var \application\modules\admin\controllers\AdsController $this
 * @var \application\models\Ads $model
 */
?>


<?php
    use application\models\Ads;
?>

<?php if($model->getIsConfirm()): ?>
    <a
        href="<?php echo \Yii::app()->createUrl('admin/ads/changeStatus', [
            'id' => $model->id,
            'status' => Ads::STATUS_PUBLISHED,
            'adsStatus' => $model->getQueryStatus(),
        ]); ?>"
        title=""
        data-original-title="Подтвердить"
        data-toggle="modal"
        data-tooltip="tooltip"
        data-placement="top"
        class="btn btn-success btn-xs btnModerateAccept ads-status-button"
    >
        <i class="icon-ok"></i>
    </a>
<?php endif; ?>
<?php if($model->getIsReject()): ?>
    <a
        href="<?php echo \Yii::app()->createUrl('admin/ads/changeStatus', [
            'id' => $model->id,
            'status' => Ads::STATUS_PROHIBITED,
            'adsStatus' => $model->getQueryStatus(),
        ]); ?>"
        title=""
        data-original-title="Отклонить"
        data-toggle="modal"
        data-tooltip="tooltip"
        data-placement="top"
        class="btn btn-danger btn-xs btnModerateDecline ads-status-button"
    >
        <i class="icon-remove"></i>
    </a>
<?php endif; ?>