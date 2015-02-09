<?php
return [
// Ticket
    '<language:(ru|en)>/ticket/<id:\d+>' => 'ticket/index/index',
    '<language:(ru|en)>/tickets' => 'ticket/index/list',
    '<language:(ru|en)>/ticket/to-user' => 'ticket/admin/touser',
    '<language:(ru|en)>/add-ticket' => 'ticket/index/create',

// Webmaster
    '<language:(ru|en)>/webmaster/block/<siteId:\d+>-<id:\d+>' => 'webmaster/block',
    '<language:(ru|en)>/webmaster/block/<siteId:\d+>' => 'webmaster/block',
    '<language:(ru|en)>/webmaster/blocks/site-<siteId:\d+>/<status:\w+>' => 'webmaster/block/list',
    '<language:(ru|en)>/webmaster/blocks/site-<siteId:\d+>' => 'webmaster/block/list',
    '<language:(ru|en)>/webmaster/stats/sites-<id:\d+>' => 'webmaster/stats/sites',
    '<language:(ru|en)>/webmaster/stats/blocks-<id:\d+>' => 'webmaster/stats/blocks',
    '<language:(ru|en)>/webmaster/stats' => 'webmaster/stats/index',
    '<language:(ru|en)>/webmaster/site-<id:\d+>' => 'webmaster/site/index',
    '<language:(ru|en)>/webmaster/sites/<status:\w+>' => 'webmaster/site/list',
    '<language:(ru|en)>/webmaster/site/delete-<id:\d+>' => 'webmaster/site/delete',
    '<language:(ru|en)>/webmaster/sites' => 'webmaster/site/list',
    '<language:(ru|en)>/webmaster' => 'webmaster/site/list',
    '<language:(ru|en)>/webmaster/money' => 'webmaster/money/index',

// Advertiser
    '<language:(ru|en)>/advertiser/ads/<campaignId:\d+>-<id:\d+>/<action:\w+>' => 'advertiser/ads',
    '<language:(ru|en)>/advertiser/ads/list/campaign-<campaignId:\d+>/<status:\w+>' => 'advertiser/ads/list',
    '<language:(ru|en)>/advertiser/ads/list/campaign-<campaignId:\d+>' => 'advertiser/ads/list',
    '<language:(ru|en)>/advertiser/ads-<id:\d+>/campaign-<campaignId:\d+>' => 'advertiser/ads/index',
    '<language:(ru|en)>/advertiser/stats/campaign-<id:\d+>' => 'advertiser/stats/campaign',
    '<language:(ru|en)>/advertiser/stats/ads-<id:\d+>' => 'advertiser/stats/ads',
    '<language:(ru|en)>/advertiser/index' => 'advertiser/stats/index',
    '<language:(ru|en)>/advertiser/ads/campaign-<campaignId:\d+>' => 'advertiser/ads/index',
    '<language:(ru|en)>/advertiser/ads-<id:\d+>' => 'advertiser/ads/index',
    '<language:(ru|en)>/advertiser/campaigns/label-<label:\w+>' => 'advertiser/campaign/list',
    '<language:(ru|en)>/advertiser/campaigns/<status:\w+>' => 'advertiser/campaign/list',
    '<language:(ru|en)>/advertiser/campaigns' => 'advertiser/campaign/list',
    '<language:(ru|en)>/advertiser' => 'advertiser/campaign/list',
    '<language:(ru|en)>/advertiser/retargeting' => 'advertiser/target/list',
    '<language:(ru|en)>/advertiser/campaign-<id:\d+>' => 'advertiser/campaign/index',

// Block
    '<language:(ru|en)>/block/select/format/<siteId:\d+>' => 'block/select/format',
    '<language:(ru|en)>/block/main/<siteId:\d+>-<id:\d+>' => 'block/index/main',
    '<language:(ru|en)>/block/main/<siteId:\d+>' => 'block/index/main',
    '<language:(ru|en)>/block/<format:\w+>/<siteId:\d+>-<id:\d+>' => 'block/index/index',
    '<language:(ru|en)>/block/<format:\w+>/<siteId:\d+>' => 'block/index/index',

// Admin
    '<language:(ru|en)>/admin/tickets' => 'admin/ticket/list',
    '<language:(ru|en)>/admin/users' => 'admin/user/list',
    '<language:(ru|en)>/admin/ua-targeting' => 'admin/useragent/list',
    '<language:(ru|en)>/admin/sites' => 'admin/site/list',
    '<language:(ru|en)>/admin/ads' => 'admin/ads/list',
    '<language:(ru|en)>/admin/prepayments' => 'admin/money/prepaymentRequestList',
    '<language:(ru|en)>/admin/referals-payments' => 'admin/money/referalsPaymentList',
    '<language:(ru|en)>/admin/transactions' => 'admin/money/transactionList',
    '<language:(ru|en)>/admin/sites-stats/<userId:\d+>' => 'admin/stats/sites',
    '<language:(ru|en)>/admin/block-ormat-test-list/<format:\w+>' => 'admin/block/formatTestList',
    '<language:(ru|en)>/admin/block-stats-<blockId:\d+>' => 'admin/stats/blocks',
    '<language:(ru|en)>/admin/formats-stats-<blockId:\d+>' => 'admin/stats/formats',
    '<language:(ru|en)>/admin/blocks' => 'admin/block/list',
    '<language:(ru|en)>/admin/statscategory-<id:\d+>' => 'admin/statscategory/category',
    '<language:(ru|en)>/admin' => 'admin/dashboard/index',


//Payment //success //fail
    '<language:(ru|en)>/payment' => 'payment/payment/index',
    '<language:(ru|en)>/payment/wmr/result' => 'payment/wmr/result',
    '<language:(ru|en)>/payment/wmz/result' => 'payment/wmz/result',
    '<language:(ru|en)>/payment/wmr/success' => 'payment/wmr/success',
    '<language:(ru|en)>/payment/wmz/success' => 'payment/wmz/success',
    '<language:(ru|en)>/payment/wmr/fail' => 'payment/wmr/fail',
    '<language:(ru|en)>/payment/wmz/fail' => 'payment/wmz/fail',
    'payment' => 'payment/payment/index',
    'payment/wmr/result/language/<language:(ru|en)>' => 'payment/wmr/result',
    'payment/wmz/result/language/<language:(ru|en)>' => 'payment/wmz/result',
    'payment/wmr/success/language/<language:(ru|en)>' => 'payment/wmr/success',
    'payment/wmz/success/language/<language:(ru|en)>' => 'payment/wmz/success',
    'payment/wmr/fail/language/<language:(ru|en)>' => 'payment/wmr/fail',
    'payment/wmz/fail/language/<language:(ru|en)>' => 'payment/wmz/fail',

// General
    '<language:(ru|en)>/' => 'index/index',
    '<language:(ru|en)>/login' => 'index/login',
    '<language:(ru|en)>/logout' => 'index/logout',
    '<language:(ru|en)>/registration' => 'index/registration',
    '<language:(ru|en)>/payment/add-money' => 'payment/payment/addMoney',
    '<language:(ru|en)>/thankyou' => 'account/thankyou',
    '<language:(ru|en)>/contacts' => 'index/contacts',
    '<language:(ru|en)>/register' => 'index/register',
    '<language:(ru|en)>/recovery' => 'index/recovery',
    '<language:(ru|en)>/auth' => 'index/auth',
    '<language:(ru|en)>/sendpass' => 'index/sendPass',
    '<language:(ru|en)>/banned' => 'account/banned',
    '<language:(ru|en)>/landing-2' => 'landing/landing2',
    '<language:(ru|en)>/landing-for-advertisers' => 'landing/forAdvertisers',
    'banned' => 'account/banned',
    'landing-2' => 'landing/forAdvertisers',
    'landing-for-advertisers' => 'landing/forAdvertisers',

// System
    '<language:(ru|en)>/<controller:\w+>/<id:\d+>'=>'<controller>/index',
    '<language:(ru|en)>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    '<language:(ru|en)>/<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

    '<controller:\w+>/<id:\d+>'=>'<controller>/index',
    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
];