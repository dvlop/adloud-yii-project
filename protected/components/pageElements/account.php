<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 06.06.14
 * Time: 16:25
 */

return [
    'index' => [
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/account.js',
            \Yii::app()->theme->baseUrl.'/assets/js/flatui-fileinput.js',
        ],
        'cssFiles' => \Yii::app()->theme->baseUrl.'/assets/css/jquery-ui-1.10.4.min.css',
    ],

    'baseinfo' => [
        'alterPageName' => [
            [
                'containers' => [],
                'headers' => [
                    'h1' => [
                        'class' => 'text-center',
                        'name' => 'Добавить сайт в систему',
                    ],
                ],
                'elements' => [],
            ],
        ],
    ],

    'thankyou' => [
        'pageName' => 'Спасибо за регистрацию!',
    ],
];