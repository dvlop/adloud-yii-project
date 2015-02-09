<?php

return [
    'index' => [
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/block.js',
        ],
    ],

    'list' => [
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/datepicker-dark.js',
            \Yii::app()->theme->baseUrl.'/assets/plugins/zeroclipboard/dist/ZeroClipboard.js',
            \Yii::app()->theme->baseUrl.'/assets/js/pages/blocksList.js',
            //\Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-radio/bootstro.min.js',
        ],
        'cssFiles' => [
            //\Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-radio/checkbox-radio-switch.css',
        ],
        'topButtons' => [
            [
                'class' => 'col-md-3 col-sm-4',
                'elements' => [
                    'a' => [
                        'name' => 'Добавить блок',
                        'url' => '/block/select/format',
                        'class' => 'btn btn-block adloud_btn btn-add-block',
                        'icon' => 'input-icon fui-plus pull-left',
                    ]
                ],
            ],
            [
                'class' => 'date_pick'
            ]
//            [
//                'class' => 'col-md-3 col-sm-12 calendar',
//                'elements' => [
//                    'input' => [
//                        'id' => 'datepicker-01',
//                        'name' => '',
//                        'value' => '',
//                        'class' => 'form-control',
//                        'type' => 'text',
//                        'readonly' => true,
//                    ],
//                    'span' => [
//                        [
//                            'name' => '',
//                            'class' => 'input-icon fui-calendar',
//                        ],
//                        [
//                            'name' => '',
//                            'class' => '',
//                            'id' => 'period'
//                        ],
//                    ],
//                ],
//            ],
        ],
        'bulkOperations' => [
            /*[
           'name' => 'Опибликовать',
           'url' => '/advertiser/publishAllAds',
           'title' => 'Опубликовать все эти тизеры',
           ],
           [
               'name' => 'Убрать из публикации',
               'url' => '/advertiser/unPublishAllAds',
               'title' => 'Убрать из публикации все эти тизеры',
           ],*/
            [
                'name' => 'Удалить',
                'url' => '/webmaster/block/removeAll',
                'title' => 'Вы уверены, что хотите удалить все выбранные блоки',
            ],
        ],
    ],
];