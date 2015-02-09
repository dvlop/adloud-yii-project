<?php

return [
    'advertiser' => [
        'class' => 'application\modules\advertiser\AdvertiserModule',
        'defaultController' => 'index',
    ],
    'webmaster' => [
        'class' => 'application\modules\webmaster\WebmasterModule',
        'defaultController' => 'index',
    ],
    'ticket' => [
        'class' => 'application\modules\ticket\TicketModule',
        'defaultController' => 'index',
    ],
    'admin' => [
        'class' => 'application\modules\admin\AdminModule',
        'defaultController' => 'index',
    ],
    'block' => [
        'class' => 'application\modules\block\BlockModule',
        'defaultController' => 'index',
    ],

    'payment' => [
        'class' => 'application\modules\payment\PaymentModule',
        'payments' => [
            'WMR' => [
                'accountNumber' => 'R287780747273',
                //'serviceId' => '474791632507',
                'clientSecret' => '3DA98BD06F47U8I9YT',
                'systemCurrency' => 'WMR',
            ],
            'WMZ' => [
                'accountNumber' => 'Z230839962985',
                //'serviceId' => '474791632507',
                'clientSecret' => '3DAEIU8YF47U87',
                'systemCurrency' => 'WMZ',
            ],
            /*'yandexMoney' => array(
                'accountNumber' => '410012285143085',
                'serviceId' => '08A61CD828C48764A48BF140FC78610B236FEAC18DCA5CB763EC7DA9EA337211',
                'clientSecret' => '8553E573936B8CF715B7872D4867E380C5A197EC2A8F32CFB779AB5A2DF857755D7035B2F0E32700FCC5BF44852975B14DDCE051A1318D2902923DB6CDFB7F0F',
                'systemCurrency' => 'YAR',
            ),
            'qiwi' => [
                'accountNumber' => '263532',
                'systemCurrency' => 'QIWI',
            ],
            'creditCard' => [
                'accountNumber' => 'Z145179295679',
                'systemCurrency' => 'CARD',
            ],*/
        ],
        'theme' => 'adloud',
    ],
    'social' => [

    ],
    // uncomment the following to enable the Gii tool
    /*'gii' => [
        'class' => 'system.gii.GiiModule',
        'password' => 'admin',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        'ipFilters' => ['127.0.0.1','::1'],
    ],*/
];