<?php

return [
    'index' => [
        'pageName' => 'Создание кампании',
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/js/pages/campaign.js'
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
                    /*'div' => [
                        'class' => 'col-md-4 free_campaign',
                    ],
                    'p' => [
                        'class' => '',
                        'name' => 'Закажи создание своей первой рекламной кампании - бесплатно!',
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
                        'name' => 'Заказать кампанию',
                    ],
                    'span' => [
                        'class' => 'fa fa-shopping-cart pull-left fui-lg',
                    ],
                ],
            ]*/
        ],
    ],

    'list' => [
        'pageName' => 'Мои кампании',
        'breadcrumbs' => 'Мои кампании',
        'scriptFiles' => Yii::app()->theme->baseUrl.'/assets/js/pages/campaignsList.js',
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
            ]
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