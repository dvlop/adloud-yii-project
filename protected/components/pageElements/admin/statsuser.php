<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:50
 * To change this template use File | Settings | File Templates.
 */

return [
    'index' => [
        'cssFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-datepicker/css/datepicker.css',
            Yii::app()->theme->baseUrl.'/assets/plugins/advanced-datatable/media/css/demo_table.css',
        ],
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/advanced-datatable/media/js/jquery.dataTables.js',
            Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            Yii::app()->theme->baseUrl.'/assets/js/Chart.js',
            Yii::app()->theme->baseUrl.'/assets/js/dynamic_table_init.js',
            Yii::app()->theme->baseUrl.'/assets/js/pages/stats-user.js'
        ]
    ],
];