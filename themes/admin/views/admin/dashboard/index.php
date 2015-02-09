<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 18.09.14
 * Time: 19:05
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="row state-overview">
    <div class="col-lg-3 col-sm-6">
        <section class="panel clearfix">
            <div class="symbol terques pull-left">
                <i class="fa fa-eye"></i>
                <h6>Просмотры</h6>
            </div>
            <div class="value full-val">
                <p>За сегодня</p>
                <span class="h3 count2">
                <?=$numbers['today']['shows']?>
                </span>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="panel clearfix">
            <div class="symbol red pull-left">
                <i class="fa fa-hand-o-down"></i>
                <h6>Клики</h6>
            </div>
            <div class="value full-val">
                <p>За сегодня</p>
                <span class="h3 count4">
                <?=$numbers['today']['clicks']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel clearfix">
            <div class="symbol yellow pull-left">
                <i class="fa-imitation-percent">%</i>
                <h6>CTR</h6>
            </div>
            <div class="value full-val">
                <p>За сегодня</p>
                <span class="h3 count2">
                <?=$numbers['today']['ctr']?>
                </span>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel clearfix">
            <div class="symbol blue pull-left">
                <i class="fa fa-dollar fa-dollar-inverse"></i>
                <h6>Доход</h6>
            </div>
            <div class="value">
                <p>За все время</p>
                <span class="h3 count3">
                <?=$numbers['allTime']['income']?>
                </span>
            </div>
            <div class="value">
                <p>За сегодня</p>
                <span class="h3 count4">
                <?=$numbers['today']['income']?>
                </span>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel clearfix">
            <div class="symbol terques pull-left">
                <i class="fa fa-money"></i>
                <h6>Средства</h6>
            </div>
            <div class="value">
                <p>За все время</p>
                <span class="h3 count1">
                <?=$numbers['allTime']['moneyIn']?>/<?=$numbers['allTime']['moneyOut']?>
                </span>
            </div>
            <div class="value">
                <p>За сегодня</p>
                <span class="h3 count2">
                <?=$numbers['today']['moneyIn']?>/<?=$numbers['today']['moneyOut']?>
                </span>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel clearfix">
            <div class="symbol red pull-left">
                <i class="fa fa-users"></i>
                <h6>Регистрации</h6>
            </div>
            <div class="value">
                <p>За все время</p>
                <span class="h3 count3">
                <?=$numbers['allTime']['users']?>
                </span>
            </div>
            <div class="value">
                <p>За сегодня</p>
                <span class="h3 count4">
                <?=$numbers['today']['users']?>
                </span>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel clearfix">
            <div class="symbol yellow pull-left">
                <i class="fa fa-sort-amount-desc"></i>
                <h6>Площадки</h6>
            </div>
            <div class="value">
                <p>За все время</p>
                <span class="h3 count1">
                <?=$numbers['allTime']['sites']?>
                </span>
            </div>
            <div class="value">
                <p>За сегодня</p>
                <span class="h3 count2">
                <?=$numbers['today']['sites']?>
                </span>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel clearfix">
            <div class="symbol blue pull-left">
                <i class="fa fa-picture-o"></i>
                <h6>Тизеры</h6>
            </div>
            <div class="value">
                <p>За все время</p>
                <span class="h3 count1">
                <?=$numbers['allTime']['ads']?>
                </span>
            </div>
            <div class="value">
                <p>За сегодня</p>
                <span class="h3 count2">
                <?=$numbers['today']['ads']?>
                </span>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <section class="col-lg-6">
        <h3>Трафик по категориям за сегодня</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="category-today" height="400" width="450"></canvas>
                </div>
            </div>
        </div>
    </section>
    <section class="col-lg-6">
        <h3>Трафик по категориям за все время</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="category-all" height="400" width="450"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="row">
    <section class="col-lg-6">
        <h3>Трафик по площадкам за сегодня</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="site-today" height="400" width="450"></canvas>
                </div>
            </div>
        </div>
    </section>
    <section class="col-lg-6">
        <h3>Трафик по площадкам за все время</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="site-all" height="400" width="450"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

<?php Yii::app()->clientScript->registerScript('dashboard', '
    Dashboard.init({
        categoryTodayData: ['.implode(',',$chartsData["category"]["today"]).'],
        categoryAlltimeData: ['.implode(',',$chartsData["category"]["allTime"]).'],
        siteTodayData: ['.implode(',',$chartsData["site"]["today"]).'],
        siteAlltimeData: ['.implode(',',$chartsData["site"]["allTime"]).'],
    });
'); ?>