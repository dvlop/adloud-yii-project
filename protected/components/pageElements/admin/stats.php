<?php

return [
    'formats' => [
        'scriptFiles' => \Yii::app()->theme->baseUrl.'/assets/js/pages/formatsStat.js',
    ],
    'sites' => [
        'cssFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-datepicker/css/datepicker.css',
            Yii::app()->theme->baseUrl.'/assets/plugins/advanced-datatable/media/css/demo_table.css',
        ],
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/advanced-datatable/media/js/jquery.dataTables.js',
            Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            Yii::app()->theme->baseUrl.'/assets/js/Chart.js',
            Yii::app()->theme->baseUrl.'/assets/js/dynamic_table_init.js',
            Yii::app()->theme->baseUrl.'/assets/js/pages/stats-category.js'
        ]
    ],
];