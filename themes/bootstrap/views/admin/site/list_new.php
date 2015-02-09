<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 05.08.14
 * Time: 12:02
 * @var \application\modules\admin\controllers\SiteController $this
 * @var \application\models\Sites $model
 * @var \application\models\Sites $data
 */
?>

<?php
    use application\models\Sites;
?>

<div class="col-lg-12 margin-bottom-40">

    <div class="col-lg-1">Статус:</div>

    <div class="col-lg-2">
        <?php if($model->selectorStatuses): ?>
            <select name="siteStatus" id="sites-status-selector-id" class="sites-status-selector">
                <?php foreach($model->selectorStatuses as $status): ?>
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

<div class="col-lg-12 margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-user"></i>Модерация сайтов</h3>
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
                            'url' => function($data){ return \Yii::app()->createUrl('admin/site/index', ['id' => $data->id, 'state' => Sites::STATUS_PUBLISHED]); },
                            'label' => '<i class="icon-ok"></i>',
                            'visible' => '$data->getIsConfirm()',
                            'options' => [
                                'class' => 'btn btn-success btn-xs btnModerateAccept site-status-button',
                                'data-placement' => 'top',
                                'data-tooltip' => 'tooltip',
                                'data-toggle' => 'modal',
                                'data-original-title' => 'Подтвердить',
                                'title' => 'Подтвердить',
                            ],
                        ],
                        'reject' => [
                            'url' => function($data){ return \Yii::app()->createUrl('admin/site/index', ['id' => $data->id, 'state' => Sites::STATUS_PROHIBITED]); },
                            'label' => '<i class="icon-remove"></i>',
                            'visible' => '$data->getIsReject()',
                            'options' => [
                                'class' => 'btn btn-danger btn-xs btnModerateDecline, site-status-button',
                                'data-placement' => 'top',
                                'data-tooltip' => 'tooltip',
                                'data-toggle' => 'modal',
                                'data-original-title' => 'Отклонить',
                                'title' => 'Отклонить',
                            ],
                        ],
                    ],
                ]
            ]),
        ]); ?>
    </div>

</div>

<?php Yii::app()->clientScript->registerScript('usersList', '
    Sites.init();
'); ?>