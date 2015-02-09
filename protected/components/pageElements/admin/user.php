<?php

return [
    'list' => [
        'pageName' => 'Пользователи системы',
        'subLayout' => 'datatables',
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/users.js',
        ],
    ],
    'index' => [
        'pageName' => 'Редактирование профиля пользователя',
        'subLayout' => 'forms',
        'scriptFiles' => [
            \Yii::app()->theme->baseUrl.'/assets/js/pages/user.js',
        ],
    ]
];