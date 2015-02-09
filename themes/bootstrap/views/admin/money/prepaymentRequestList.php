<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 30.07.14
 * Time: 12:47
 * @var \application\modules\admin\controllers\MoneyController $this
 * @var CActiveDataProvider $dataProvider
 * @var \application\models\UserPayoutRequest $data
 * @var \application\models\UserPayoutRequest $model
 */
?>

<?php
$this->widget('zii.widgets.grid.CGridView', [
        'dataProvider' => $dataProvider,
        //'filter' => $model,
        'enablePagination'=>true,
        'ajaxUpdate'=>true,
        'columns' => [
            'id',
            'requestDate',
            'userId',
            'userName',
            'amount',
            'payoutDate',
            'statusName',
            [
                'class' => 'CButtonColumn',
                'template' => '{open}{reset}{submit}',
                'htmlOptions' => [],
                'buttons' => [
                    'open' => [
                        'label' => 'Принять',
                        'url' => function($data){ return Yii::app()->createUrl('admin/money/setPrepaymentStatus', ['id' => $data->id, 'status' => $data->statusOpened]); },
                        'update' => '',
                        'options' => [
                            'ajax'=>array(
                                'type'=>'GET',
                                'url'=>"js:$(this).attr('href')",
                                //'data' => "js:$(this).attr('data-id')",
                                //'update'=>"js:$(this).attr('href')",
                            ),
                            'class' => 'open-button',
                        ],
                    ],
                    'reset' => [
                        'label' => 'Отклонить',
                        'url' => function($data){ return Yii::app()->createUrl('admin/money/setPrepaymentStatus', ['id' => $data->id, 'status' => $data->statusRejected]); },
                        'update' => '',
                        'options' => [
                            'ajax'=>array(
                                'type'=>'GET',
                                'url'=>"js:$(this).attr('href')",
                                //'data' => "js:$(this).attr('data-id')",
                                //'update'=>"js:$(this).attr('href')",
                            ),
                        ],
                    ],
                    'submit' => [
                        'label' => 'Оплатить',
                        'url' => function($data){ return Yii::app()->createUrl('admin/money/setPrepaymentStatus', ['id' => $data->id, 'status' => $data->statusDone]); },
                        'update' => '',
                        'options' => [
                            'ajax'=>array(
                                'type'=>'GET',
                                'url'=>"js:$(this).attr('href')",
                                //'data' => "js:$(this).attr('data-id')",
                                //'update'=>"js:$(this).attr('href')",
                            ),
                        ],
                    ],
                ],
            ],
        ],
]);
?>