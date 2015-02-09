<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.09.14
 * Time: 16:40
 * @var \application\modules\admin\controllers\SiteController $this
 * @var \application\models\Sites $site
 * @var integer $status
 */
?>

<?php use application\models\Sites; ?>

<td><?php echo $site->id; ?></td>
<td><a target="_blank" href="<?php echo $site->url; ?>"><?php echo $site->url; ?></a></td>
<td><?php echo $site->getUserId(); ?></td>
<td><a target="_blank" href="<?php echo $site->getStatsUrl(); ?>"><?php echo $site->getStatsUrl(); ?></a></td>
<td><?php echo $site->getStatsLogin(); ?></td>
<td><?php echo $site->getStatsPassword(); ?></td>
<td><?php echo $site->getStatusName(); ?></td>
<td>
    <?php if($site->getIsConfirm()): ?>
        <button
            class="btn btn-success btn-xs tooltips auto-ajax"
            data-url="<?php echo \Yii::app()->createUrl('admin/site/index', ['id' => $site->id, 'state' => Sites::STATUS_PUBLISHED, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="setSiteStatus=1"
            data-original-title="Подтвердить"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-check"></i>
        </button>
    <?php endif; ?>

    <?php if($site->getIsReject()): ?>
        <button
            class="btn btn-danger btn-xs tooltips auto-ajax"
            data-url="<?php echo \Yii::app()->createUrl('admin/site/index', ['id' => $site->id, 'state' => Sites::STATUS_PROHIBITED, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="setSiteStatus=1"
            data-original-title="Отклонить"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-times"></i>
        </button>
    <?php endif; ?>
</td>