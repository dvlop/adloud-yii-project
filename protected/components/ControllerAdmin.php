<?php
/**
 * User: maksymenko.ml
 * Date: 25.03.14
 * Time: 17:46
 */

namespace application\components;

class ControllerAdmin extends ControllerBase
{
    public $layout = '//layouts/main';
    public $subLayout;

    public function init()
    {
        $this->breadcrumbs[\Yii::app()->createUrl('index/index')] = \Yii::t('main', 'Главная');
        $this->title = \Yii::app()->name;
        \Yii::app()->theme = 'admin'; // bootstrap/adloud/admin

        \Yii::app()->viewPath = \Yii::app()->theme->basePath.DIRECTORY_SEPARATOR.'views';
    }

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'expression'=> '\Yii::app()->user->isAdmin',
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $this->breadcrumbs[\Yii::app()->createUrl($this->getId() . '/')] = 'Админка';

        $this->sideMenu = array(
            array(
                'name' => 'Модерация',
                'url' => 'admin',
                'actions' => array('ads/list', 'site/list'),
                'menu' => array(
                    array(
                        'name' => 'Список обьявлений',
                        'url' => 'admin/ads/list',
                        'icon' => 'icon-rocket',
                    ),
                    array(
                        'name' => 'Список сайтов',
                        'url' => 'admin/site/list',
                        'icon' => 'icon-rocket',
                    ),
                ),
            ),
            array(
                'name' => 'Пользователи',
                'url' => 'admin',
                'actions' => array('list'),
                'menu' => array(
                    array(
                        'name' => 'Список пользователей',
                        'url' => 'admin/user/list',
                        'icon' => 'icon-user',
                    ),
                    array(
                        'name' => 'Служба поддержки',
                        'url' => 'admin/ticket/list',
                        'icon' => 'icon-user',
                    ),
                    array(
                        'name' => 'UA Таргетинг',
                        'url' => 'admin/useragent/list',
                        'icon' => 'icon-user',
                    ),
                ),
            ),
            [
                'name' => 'Финансы',
                'url' => 'admin',
                'actions' => ['money/payoutrequestlist'],
                'menu' => [
                    [
                        'name' => 'Запросы на вывод денег',
                        'url' => 'admin/money/prepaymentRequestList',
                        'icon' => 'icon-rocket',
                    ],
                    [
                        'name' => 'Запросы на реферальные выплаты',
                        'url' => 'admin/money/referalsPaymentList',
                        'icon' => 'icon-rocket',
                    ],
                    [
                        'name' => 'Статистика транзакций',
                        'url' => 'admin/stats/transactions',
                        'icon' => 'icon-rocket',
                    ]
                ],
            ],
            array(
                'name' => 'Блоки',
                'url' => 'admin',
                'actions' => ['admin/block/list'],
                'menu' => [
                    [
                        'name' => 'Список блоков',
                        'url' => 'admin/block/list',
                        'icon' => 'icon-stats',
                    ],
                ],
            ),
        );

        return true;
    }

}