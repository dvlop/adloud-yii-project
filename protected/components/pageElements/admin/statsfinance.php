<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:49
 * To change this template use File | Settings | File Templates.
 */

return [
    'index' => [
        'cssFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-datepicker/css/datepicker.css',
        ],
        'scriptFiles' => [
            Yii::app()->theme->baseUrl.'/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            Yii::app()->theme->baseUrl.'/assets/js/Chart.js',
            Yii::app()->theme->baseUrl.'/assets/js/pages/stats-finance.js'
        ]
    ],
];