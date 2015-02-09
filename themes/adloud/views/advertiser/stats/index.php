<?php
/**
 * @var string $dateFormat
 * @var string $date
 * @var string $startDate
 * @var string $endDate
 */
?>
<h1 class="col-sm-12">
    <?=$pageName;?>
</h1>
<div class="col-sm-12">
    <div class="col-sm-12 statistic_menu">

        <div class="col-sm-3">
            <div class="row">
                <select id="type" name="graph_type" class="graph_settings graph_type">
                    <option value="campaign" <?= $type == 'campaign' ? 'selected=true' : ''?>><?php echo Yii::t('advertiser_stats', 'По кампаниям'); ?></option>
                    <option value="date"  <?= $type == 'date' ? 'selected=true' : ''?>><?php echo Yii::t('advertiser_stats', 'По датам'); ?></option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <?php if(!isset($isAd)):?>
            <div class="row">
                <select id="day-period" name="graph_time" class="graph_settings graph_time">
                    <option value="actual" <?= $status == 'actual' ? 'selected=true' : ''?>><?php echo Yii::t('advertiser_stats', 'Актуальные'); ?></option>
                    <option value="archived"  <?= $status == 'archived' ? 'selected=true' : ''?>><?php echo Yii::t('advertiser_stats', 'Архивные'); ?></option>
                </select>
            </div>
            <?php endif;?>
        </div>

        <div class="col-sm-3 set_period">
            <p><?php echo Yii::t('advertiser_stats', 'Установить период'); ?> <span class="caret right"></span></p>
        </div>

        <div class="col-sm-3 datepicker-container">
            <div class="row">

                <input type="text" class="form-control datepicker" readonly="readonly"/>
                <span class="input-icon fui-calendar"></span>
                <span class="input-icon caret right"></span>

                <div id="datepicker">

                    <div class="datepicker-period-panel">
                        <p class="datepicker-period-panel-title"><?php echo Yii::t('calendar', 'Выберите период:'); ?></p>
                        <ul>
                            <li class="today-period" data-handler="today" data-event="click"><?php echo Yii::t('calendar', 'За сегодня'); ?></li>
                            <li class="yesterday-period"><?php echo Yii::t('calendar', 'За вчера'); ?></li>
                            <li class="week-period"><?php echo Yii::t('calendar', 'За текущую неделю'); ?></li>
                            <li class="last-week-period"><?php echo Yii::t('calendar', 'За прошлую неделю'); ?></li>
                            <li class="month-period"><?php echo Yii::t('calendar', 'За текущий месяц'); ?></li>
                            <li class="last-month-period"><?php echo Yii::t('calendar', 'За прошлый месяц'); ?></li>
                            <li class="year-period"><?php echo Yii::t('calendar', 'За текущий год'); ?></li>
                        </ul>
                    </div>
                    <input type="hidden" name="startDate"/>
                    <input type="hidden" name="endDate"/>

                    <div class="ui-datepicker-buttonpane">
                        <button class="btn btn-default close-calendar" type="button"><?php echo Yii::t('calendar', 'Отменить'); ?></button>
                        <button class="btn btn-inverse check_date" type="submit"><?php echo Yii::t('calendar', 'Применить'); ?></button>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<div class="col-sm-12">
    <?php if(count($chartData['label']) > 1):?>
    <span class="chart-title"><?=$pageName;?></span>
    <button class="toggle-chart show" type="button"><?php echo Yii::t('advertiser_stats', 'Скрыть график'); ?></button>
    <button class="toggle-chart hide" type="button"><?php echo Yii::t('advertiser_stats', 'Показать график'); ?></button>
    <div class="chart-container">
        <canvas id="chart" class="chart" width="940" height="300" style="width:940px;heigh:300px;"></canvas>
    </div>
    <?php endif;?>
</div>

<div class="col-sm-12">
    <?php if(isset($stats)):?>
    <table class="table table-striped table-hover adloud_table statistic_table">
        <thead>
        <tr>
            <th class="col-sm-3"><?php echo Yii::t('advertiser_stats', 'Описание'); ?></th>
            <th class="col-sm-3"><?php echo Yii::t('advertiser_stats', 'Показы'); ?></th>
            <th class="col-sm-2"><?php echo Yii::t('advertiser_stats', 'Клики'); ?></th>
            <th class="col-sm-2"><?php echo Yii::t('advertiser_stats', 'CTR'); ?></th>
            <th class="col-sm-2"><?php echo Yii::t('advertiser_stats', 'Расход'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if($type == 'date'):?>
            <?php foreach($stats as $stat):?>
            <tr class="stats-date-row">
                <td><span data-date="<?=$stat['date']?>" class="selected-statistic-period-day"><span class="caret"></span><span><?=$stat['date']?></span></span></td>
                <td><?=$stat['shows']?></td>
                <td><?=$stat['clicks']?></td>
                <td><?= $stat['shows'] != 0 ?round($stat['clicks'] / $stat['shows'], 5)*100 : 0?>%</td>
                <td><?=round($stat['costs'],2)?></td>
            </tr>
                <?php foreach($stat['details'] as $detail):?>
                    <?php if(isset($detail['shows'])):?>
                        <tr data-day="<?=$stat['date']?>" class="stats-hide-row hide">
                            <td><?=$detail['description']?></td>
                            <td><?=$detail['shows']?></td>
                            <td><?=$detail['clicks']?></td>
                            <td><?=$detail['shows'] != 0 ?round($detail['clicks'] / $detail['shows'], 5)*100 : 0?>%</td>
                            <td><?=round($detail['costs'],2)?></td>
                        </tr>
                    <?php else: ?>
                        <tr data-day="<?=$stat['date']?>" class="stats-hide-row hide">
                            <td colspan="5"><?=$detail['description']?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach?>
            <?php
                $total['shows'] += $stat['shows'];
                $total['clicks'] += $stat['clicks'];
                $total['costs'] += $stat['costs'];
                ?>
            <?php endforeach?>
        <?php else: ?>
            <?php foreach($stats as $stat):?>
                <tr>
                    <td><?=CHtml::link($stat['description'], Yii::app()->createUrl($actionLink, ['id' => $stat['item_id']]).$linkParams);?></td>
                    <td><?=$stat['shows']?></td>
                    <td><?=$stat['clicks']?></td>
                    <td><?= $stat['shows'] != 0 ?round($stat['clicks'] / $stat['shows'], 5)*100 : 0?>%</td>
                    <td><?=round($stat['costs'],2)?></td>
                </tr>
                <?php
                    $total['shows'] += $stat['shows'];
                    $total['clicks'] += $stat['clicks'];
                    $total['costs'] += $stat['costs'];
                ?>
            <?php endforeach?>
        <?php endif; ?>
        <tr>
            <td><?php echo Yii::t('advertiser_stats', 'Всего'); ?></td>
            <td><?=$total['shows']?></td>
            <td><?=$total['clicks']?></td>
            <td><?= $total['shows'] != 0 ?round($total['clicks'] / $total['shows'], 5)*100 : 0?>%</td>
            <td><?=round($total['costs'],2)?></td>
        </tr>
        </tbody>
    </table>
    <?php endif;?>
</div>

<?php Yii::app()->clientScript->registerScript('advertiser-stats', '
    AdvStats.init({
        dateFormat: Main.dateFormat,
        currentDate: "'.$date.'",
        startDate: "'.$startDate.'",
        endDate: "'.$endDate.'",
        type: "'.$type.'",
        chartLabels: '.json_encode($chartData['label']).',
        showsData: '.json_encode($chartData['shows']).',
        clicksData: '.json_encode($chartData['clicks']).',
        setStartAndEndText: "'.Yii::t('calendar', 'Установите начальную и конечную дату').'",
        monthNames: '.Yii::t('calendar', '["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"]').',
        monthNamesShort: '.Yii::t('calendar', '["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"]').',
        dayNames: '.Yii::t('calendar', '["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"]').',
        dayNamesShort: '.Yii::t('calendar', '["вск","пнд","втр","срд","чтв","птн","сбт"]').',
        dayNamesMin: '.Yii::t('calendar', '["Вс","Пн","Вт","Ср","Чт","Пт","Сб"]').'
    });
'); ?>