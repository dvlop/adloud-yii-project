<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:28
 * To change this template use File | Settings | File Templates.
 */
?>
<h1>Финансовая статистика</h1>

<div class="row">
    <div class="col-sm-4 pull-right">
        <div class="col-sm-10">
            <div class="row">
                <div class="input-group input-large datepicker-container" data-date="<?=$period['startDate']?>" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control dpd1 text-center" name="from" value="<?=$period['startDate']?>">
                    <span class="input-group-addon"> - </span>
                    <input type="text" class="form-control dpd2 text-center" name="to" value="<?=$period['endDate']?>">
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="row">
                <button type="button" class="btn btn-success btn-shadow btn-block datepicker-submit"><i class="fa fa-refresh"></i></button>
            </div>
        </div>
    </div>
</div>
<div class="row state-overview statistic">
    <div class="col-sm-4">
        <section class="panel clearfix">
            <div class="symbol terques pull-left">
                <img src="<?=Yii::app()->theme->baseUrl?>/assets/img/finance.png">
            </div>
            <div class="value">
                <span class="h3 count1"><?=$numbers['income']?></span>
                <h6>Общий доход</h6>
            </div>
        </section>
    </div>
    <div class="col-sm-4">
        <section class="panel clearfix">
            <div class="symbol red pull-left">
                <img src="<?=Yii::app()->theme->baseUrl?>/assets/img/finance-in.png">
            </div>
            <div class="value">
                <span class="h3 count1"><?=$numbers['moneyIn']?></span>
                <h6>Всего введено</h6>
            </div>
        </section>
    </div>
    <div class="col-sm-4">
        <section class="panel clearfix">
            <div class="symbol blue pull-left">
                <img src="<?=Yii::app()->theme->baseUrl?>/assets/img/finance-out.png">
            </div>
            <div class="value">
                <span class="h3 count1"><?=$numbers['moneyOut']?></span>
                <h6>Всего выведено</h6>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <section class="col-lg-12">
        <h3>Доход системы</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="stat-income" height="400" width="1080"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="row">
    <section class="col-lg-12">
        <h3>Движение средств в системе</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="stat-money" height="400" width="1080"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

<?php Yii::app()->clientScript->registerScript('statsFinance', '
    StatsFinance.init({
        incomeLabels: ['.implode(',',$chartsData["income"]["labels"]).'],
        incomeData: ['.implode(',',$chartsData["income"]["data"]).'],
        moneyInLabels: ['.implode(',',$chartsData["moneyIn"]["labels"]).'],
        moneyInData: ['.implode(',',$chartsData["moneyIn"]["data"]).'],
        moneyOutLabels: ['.implode(',',$chartsData["moneyOut"]["labels"]).'],
        moneyOutData: ['.implode(',',$chartsData["moneyOut"]["data"]).']
    });
'); ?>