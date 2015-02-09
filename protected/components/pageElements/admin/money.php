<?php

return [
    'prepaymentrequestlist' => [
        'pageName' => 'Запросы на вывод денег',
        'subLayout' => 'datatables',
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/prepayments.js',
        ],
    ],
    'referalspaymentlist' => [
        'pageName' => 'Запросы на реферальные выплаты',
        'subLayout' => 'datatables',
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/referal-payments.js',
        ],
    ],
    'transactionlist' => [
        'pageName' => 'Транзакции "рекламодатель-вебмастер"',
        'subLayout' => 'datatables',
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/transactions.js',
        ],
    ],
];