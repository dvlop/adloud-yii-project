<?php

return [
    'index' => [
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/selectpicker-smart.js',
            \Yii::app()->theme->baseUrl.'/assets/js/pages/site.js',
        ],
        'alterPageName' => [
            [
                'containers' => [
                    'col-sm-12',
                ],
                'headers' => [
                    'h1' => [
                        'name' => 'Добавить площадку',
                    ],
                ],
                'elements' => [],
            ],
        ],
    ],

    'list' => [
        'pageName' => 'Мои площадки',
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/datepicker-dark.js',
            \Yii::app()->theme->baseUrl.'/assets/js/pages/sitesList.js',
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
                        'name' => 'Добавить площадку',
                        'url' => '/webmaster/site',
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
                'url' => '/webmaster/site/removeAll',
                'title' => 'Вы уверены, что хотите удалить все выбранные сайты',
            ],
        ],
    ],
];