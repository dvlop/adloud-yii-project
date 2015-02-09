<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 30.07.14
 * Time: 12:51
 * To change this template use File | Settings | File Templates.
 */

return [
    'index' => [
//        'pageName' => 'Открытый тикет',
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/js/pages/ticket.js',
        ],
    ],

    'list' => [
        'pageName' => 'Служба поддержки',
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/js/pages/ticket.js',
        ],
    ],

    'admin' => [
//        'pageName' => 'Тикет от лица админа',
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/js/pages/ticket.js',
        ],
    ],
];