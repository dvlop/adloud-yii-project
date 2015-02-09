<?php

return [
    'index' => [
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/colorpicker/colpick.js',
            Yii::app()->theme->baseUrl.'/assets/js/pages/new-creative-block.js',
        ],
        'cssFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/plugins/colorpicker/css/colpick.css',
        ],
    ],

    'main' => [
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/colorpicker/colpick-main.js',
            Yii::app()->theme->baseUrl.'/assets/js/pages/main-block.js',
        ],
        'cssFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/plugins/colorpicker/css/colpick.css',
            \Yii::app()->theme->baseUrl.'/assets/css/new-creative-blocks.css',
        ],
    ],
];