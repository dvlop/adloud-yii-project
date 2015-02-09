<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:29
 * To change this template use File | Settings | File Templates.
 */
?>
<h1>Трафик системы</h1>

<div class="row">
    <div class="col-sm-8">
    <section class="panel">
    <header class="panel-heading">
        Трафик системы по датам
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
    </header>
    <div class="panel-body">
    <div class="adv-table">
    <table class="display table table-bordered" id="hidden-table-info">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Показы</th>
        <th>Клики</th>
        <th>CTR</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($statData as $date => $stat):?>
        <tr class="gradeA">
            <td><?=$date?></td>
            <td><?=$stat['shows']?></td>
            <td><?=$stat['clicks']?></td>
            <td><?=$stat['ctr']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
    </table>

    </div>
    </div>
    </section>
    </div>


    <div class="col-sm-4 state-overview statistic">
        <div class="col-sm-12 pull-right">
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
        <div class="col-sm-12">
            <section class="panel clearfix">
                <div class="symbol terques pull-left">
                    <i class="fa fa-eye"></i>
                </div>
                <div class="value">
                    <span class="h3 count1"><?=$numbers['shows']?></span>
                    <h6>Колличество показов</h6>
                </div>
            </section>
        </div>
        <div class="col-sm-12">
            <section class="panel clearfix">
                <div class="symbol red pull-left">
                    <i class="fa fa fa-hand-o-down"></i>
                </div>
                <div class="value">
                    <span class="h3 count1"><?=$numbers['clicks']?></span>
                    <h6>Колличество кликов</h6>
                </div>
            </section>
        </div>
        <div class="col-sm-12">
            <section class="panel clearfix">
                <div class="symbol yellow pull-left">
                    <i class="fa fa fa-imitation-percent">%</i>
                </div>
                <div class="value">
                    <span class="h3 count1"><?=$numbers['ctr']?></span>
                    <h6>CTR</h6>
                </div>
            </section>
        </div>
    </div>
    <section class="col-lg-12">
        <h3>Показы</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="stat-shows" height="400" width="940"></canvas>
                </div>
            </div>
        </div>
    </section>
    <section class="col-lg-12">
        <h3>Клики</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="stat-clicks" height="400" width="940"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

<?php Yii::app()->clientScript->registerScript('statsTraffic', '
    StatsTraffic.init({
        showsLabels: ['.implode(',',$chartsData['labels']).'],
        showsData: ['.implode(',',$chartsData['shows']['data']).'],
        clicksLabels: ['.implode(',',$chartsData['labels']).'],
        clicksData: ['.implode(',',$chartsData['clicks']['data']).']
    });
'); ?>