<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 13.08.14
 * Time: 16:29
 * @var \application\modules\admin\controllers\AdsController $this
 * @var \application\models\Ads $model
 * @var \application\models\Ads $data
 * @var integer $adsStatus
 */
?>

<?php
    use application\models\Ads;
?>

    <div class="col-lg-12 margin-bottom-40">

        <div class="col-lg-1">Статус:</div>

        <div class="col-lg-2">
            <?php if($model->getStatuses()): ?>
                <select name="adsStatus" id="ads-status-selector-id" class="sites-status-selector">
                    <?php foreach($model->getStatuses() as $status): ?>
                        <option
                            value="<?php echo $status->value; ?>"
                            <?php if($status->selected) echo 'selected="selected"'; ?>
                        >
                            <?php echo $status->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>

    </div>

<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Объявления, ожидающие модерацию</h3>
    </div>

    <div class="panel-body">

        <?php $this->widget('zii.widgets.grid.CGridView', [
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array_merge($model->listColumnsValues, [
                [
                    'class' => '\CButtonColumn',
                    'template' => '{confirm}{reject}',
                    'buttons' => [
                        'confirm' => [
                            'url' => function($data){ return \Yii::app()->createUrl('admin/ads/changeStatus', [
                                    'id' => $data->id,
                                    'status' => Ads::STATUS_PUBLISHED,
                                    'adsStatus' => $data->getQueryStatus(),
                                ]); },
                            'label' => '<i class="icon-ok"></i>',
                            'visible' => '$data->getIsConfirm()',
                            'options' => [
                                'class' => 'btn btn-success btn-xs btnModerateAccept ads-status-button',
                                'data-placement' => 'top',
                                'data-tooltip' => 'tooltip',
                                'data-toggle' => 'modal',
                                'data-original-title' => 'Подтвердить',
                                'title' => 'Подтвердить',
                                'onclick' => 'return AdsList.changeAdsStatus($(this))',
                            ],
                        ],
                        'reject' => [
                            'id' => '$data->id',
                            'url' => function($data){ return \Yii::app()->createUrl('admin/ads/changeStatus', [
                                    'id' => $data->id,
                                    'status' => Ads::STATUS_PROHIBITED,
                                    'adsStatus' => $data->getQueryStatus(),
                                ]); },
                            'label' => '<i class="icon-remove"></i>',
                            'visible' => '$data->getIsReject()',
                            'options' => [
                                'class' => 'btn btn-danger btn-xs btnModerateDecline ads-status-button',
                                'data-placement' => 'top',
                                'data-tooltip' => 'tooltip',
                                'data-toggle' => 'modal',
                                'data-original-title' => 'Отклонить',
                                'title' => 'Отклонить',
                                'onclick' => 'return AdsList.openModalWindow($(this))',
                            ],
                        ],
                    ],
                ]
            ]),
        ]); ?>

    </div>

</div>


<?php Yii::app()->clientScript->registerScript('adsListManage', '
    AdsList.init({
        getModalUrl: "'.Yii::app()->createUrl('admin/ads/getModal').'"
    });
'); ?>