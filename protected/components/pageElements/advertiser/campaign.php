<?php

return [
    'index' => [
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/campaign.js'
        ],
        'cssFiles' => 'http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css',
        'alterPageName' => [
            [
                'containers' => [
                    'col-md-9 col-sm-8',
                    'row',
                ],
                'headers' => [
                    'h1' => [
                        'class' => 'col-md-8 add_camp_title',
                        'name' => 'Создание Кампании'
                    ],
                ],
                'elements' => [

                ],
            ],
        ],
    ],

    'list' => [
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/datepicker-dark.js',
            \Yii::app()->theme->baseUrl.'/assets/js/pages/campaignsList.js',
        ],
        'topButtons' => [
            [
                'class' => 'col-md-3 col-sm-4',
                'elements' => [
                    'a' => [
                        'name' => 'Добавить кампанию',
                        'url' => '/advertiser/campaign',
                        'class' => 'btn btn-block adloud_btn',
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
//            ]
//            [
//                'class' => 'col-sm-3 datepicker-container',
//                'elements' => [
//                    'input' => [
//                        'name' => '',
//                        'class' => 'form-control datepicker',
//                        'type' => 'text',
//                        'readonly' => 'readonly',
//                    ],
//                    'span' => [
//                        [
//                            'name' => '',
//                            'class' => 'input-icon fui-calendar',
//                        ],
//                        [
//                            'name' => '',
//                            'class' => 'input-icon caret right',
//                        ],
//                    ],
//                ]
//            ]
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
                'url' => '/advertiser/campaign/removeAll',
                'title' => 'Вы уверены, что хотите удалить все выбранные кампании',
            ],
        ],
    ],
];