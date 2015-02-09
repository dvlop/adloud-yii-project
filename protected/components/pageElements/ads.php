<?php

return [
    'index' => [
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/js/pages/ads.js',
            Yii::app()->theme->baseUrl.'/assets/js/flatui-fileinput.js',
        ],
        'cssFiles' => Yii::app()->theme->baseUrl.'/assets/css/jquery-ui-1.10.4.min.css',
        'alterPageName' => [
            [
                'containers' => [
                    'col-md-9 col-sm-8',
                    'row',
                ],
                'headers' => [
                    'h1' => [
                        'class' => 'col-md-8 add_camp_title',
                        'name' => 'Создание объявления'
                    ],
                ],
                'elements' => [
                    /*'div' => [
                        'class' => 'col-md-4 free_campaign',
                    ],
                    'p' => [
                        'class' => '',
                        'name' => 'Закажи создание 50-ти первых тизеров абсолютно бесплатно',
                    ],*/
                ],
            ],
            /*[
                'containers' => [
                    'div' => 'col-md-3 col-sm-4 order_campaign',
                ],
                'headers' => [],
                'elements' => [
                    'a' => [
                        'url' => '',
                        'class' => 'btn btn-block btn-embossed adloud_btn',
                        'name' => 'Заказать тизер',
                    ],
                    'span' => [
                        'class' => 'fa fa-shopping-cart pull-left fui-lg',
                    ],
                ],
            ]*/
        ],
    ],

    'list' => [
        'pageName' => 'Список тизеров',
        'scriptFiles' => Yii::app()->theme->baseUrl.'/assets/js/pages/adsList.js',
        'topButtons' => [
            [
                'class' => 'col-md-3 col-sm-4',
                'elements' => [
                    'a' => [
                        'name' => 'Добавить тизер',
                        'url' => '/advertiser/ads',
                        'class' => 'btn btn-block adloud_btn',
                        'icon' => 'input-icon fui-plus pull-left',
                        //'linkParams' => ['campaignId' => $campaignId],
                    ]
                ],
            ],
            /*[
                'class' => 'col-md-3 col-sm-12 calendar',
                'elements' => [
                    'input' => [
                        'id' => 'datepicker-01',
                        'name' => '',
                        'value' => date(Yii::app()->params['dateFormat']),
                        'class' => 'form-control',
                        'type' => 'text',
                    ],
                    'span' => [
                        'name' => '',
                        'class' => 'input-icon fui-calendar',
                    ]
                ],
            ]*/
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
                'url' => '/advertiser/ads/removeAll',
                'title' => 'Вы уверены, что хотите удалить все выбранные тизеры',
            ],
        ],
    ],
];