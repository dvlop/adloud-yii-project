<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:30
 * To change this template use File | Settings | File Templates.
 */
?>
<h1>Пользователи системы</h1>

<div class="row">
    <div class="col-sm-8">
        <section class="panel">
            <header class="panel-heading">
                Зарегестрированы в данный период
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
                            <th>ID</th>
                            <th>E-mail</th>
                            <th>Имя</th>
                            <th>Роль</th>
                            <th>Регистрация</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($tableData as $stat):?>
                            <tr class="gradeA">
                                <td><?=$stat['id']?></td>
                                <td><?=$stat['email']?></td>
                                <td><?=$stat['full_name']?></td>
                                <td><?=$stat['role']?></td>
                                <td><?=$stat['register_date']?></td>
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
                    <i class="fa fa-users"></i>
                </div>
                <div class="value">
                    <span class="h3 count1"><?=$numbers['totalUsers']?></span>
                    <h6>Колличество регистраций</h6>
                </div>
            </section>
        </div>
        <div class="col-sm-12">
            <section class="panel clearfix">
                <div class="symbol red pull-left">
                    <i class="fa fa-picture-o"></i>
                </div>
                <div class="value">
                    <span class="h3 count1"><?=$numbers['advertisers']?></span>
                    <h6>Рекламодатели</h6>
                </div>
            </section>
        </div>
        <div class="col-sm-12">
            <section class="panel clearfix">
                <div class="symbol yellow pull-left">
                    <i class="fa fa-sort-amount-asc"></i>
                </div>
                <div class="value">
                    <span class="h3 count1"><?=$numbers['webmasters']?></span>
                    <h6>Вебмастера</h6>
                </div>
            </section>
        </div>
    </div>
    <section class="col-lg-12">
        <h3>Пользователи</h3>
        <div class="panel">
            <div class="panel-body">
                <div class="chart">
                    <canvas id="stat-users" height="400" width="940"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>

<?php Yii::app()->clientScript->registerScript('statsUser', '
    StatsUser.init({
        usersLabels: ['.implode(',',$chartsData['labels']).'],
        usersData: ['.implode(',',$chartsData['data']).']
    });
'); ?>