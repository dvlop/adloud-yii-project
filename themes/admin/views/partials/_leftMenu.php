<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.09.14
 * Time: 18:16 * @var \application\components\ControllerAdmin $this
 */
?>

<aside>
    <div id="sidebar"  class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
        <li>
            <a class="active" href="<?php echo Yii::app()->createUrl('admin/index/index'); ?>">
                <i class="fa fa-dashboard"></i>
                <span>Админ-панель</span>
            </a>
        </li>

        <li class="sub-menu">
            <a href="#">
                <i class="fa fa-users"></i>
                <span>Пользователи</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/user/list') ?>">Список</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/ticket/list') ?>">Тикеты</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/useragent/list') ?>">UA Таргетинг</a></li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#">
                <i class="fa fa-cogs"></i>
                <span>Модерация</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/site/list') ?>">Сайты</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/ads/list') ?>">Тизеры</a></li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#">
                <i class="fa fa-money"></i>
                <span>Финансы</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/money/prepaymentRequestList') ?>">Запросы на вывод денег</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/money/referalsPaymentList') ?>">Запросы на фереральные выплаты</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/money/transactionList') ?>">Транзакции</a></li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#">
                <i class="fa fa-bar-chart-o"></i>
                <span>Статистика</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/statstraffic/') ?>">Общий трафик</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/statscategory/') ?>">Категории трафика</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/statsuser/') ?>">Пользователи</a></li>
            </ul>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/statsfinance/') ?>">Финансы</a></li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#">
                <i class="fa fa-bars"></i>
                <span>Блоки</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo Yii::app()->createUrl('admin/block/list') ?>">Список блоков</a></li>
            </ul>
        </li>

    </ul>
    <!-- sidebar menu end-->
</div>
</aside>
