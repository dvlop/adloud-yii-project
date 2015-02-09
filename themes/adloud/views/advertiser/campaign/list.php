<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.05.14
 * Time: 15:19
 * @var array $campaigns
 * @var \application\modules\advertiser\controllers\CampaignController $this
 * @var CPagination $pages
 * @var bool $containsLabel
 */
?>

<div class="col-sm-12 campaign_list">
    <div class="toggle-campaign-status-panel">
        <button
            class="btn <?=$status == 'actual' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn auto-link"
            data-status="actual"
            type="button"
            data-url="<?php echo Yii::app()->createUrl('advertiser/campaign/list'); ?>"
        >
            <span class="fa fa-play"></span><?php echo Yii::t('campaigns', 'Активные'); ?></button>
        <button
            class="btn <?=$status == 'archived' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn auto-link"
            data-status="archived"
            type="button"
            data-url="<?php echo Yii::app()->createUrl('advertiser/campaign/list', ['status' => 'archived']); ?>"
        >
            <span class="fui-trash"></span><?php echo Yii::t('campaigns', 'Архивные'); ?></button>
    </div>
<table class="table table-striped table-hover adloud_table">
    <thead>
    <tr>
        <th>
            <label class="checkbox toggle-all" for="camp_list_check_1">
                <input type="checkbox" value="" id="camp_list_check_1" data-toggle="checkbox" class="adloud_checkbox">
            </label>
        </th>
        <th>ID</th>
        <th><?php echo Yii::t('campaigns', 'Название кампании'); ?></th>
        <?php if($containsLabel): ?>
            <th><?php echo Yii::t('campaigns', 'Метка'); ?></th>
        <?php endif; ?>
        <th><span class="fa fa-play teaser-status-icon teaser-status-icon-running" data-toggle="tooltip" data-tooltip-style="light" data-placement="bottom" data-original-title="<?php echo Yii::t('campaigns', 'Запущенные'); ?>"></span></th>
        <th><span class="fa fa-pause teaser-status-icon teaser-status-icon-paused" data-toggle="tooltip" data-tooltip-style="light" data-placement="bottom" data-original-title="<?php echo Yii::t('campaigns', 'Приостановленные'); ?>"></span></th>
        <th><span class="fa fa-clock-o teaser-status-icon teaser-status-icon-moderated" data-toggle="tooltip" data-tooltip-style="light" data-placement="bottom" data-original-title="<?php echo Yii::t('campaigns', 'На модерации'); ?>"></span></th>
        <th><span class="fa fa-ban teaser-status-icon teaser-status-icon-stopped" data-toggle="tooltip" data-tooltip-style="light" data-placement="bottom" data-original-title="<?php echo Yii::t('campaigns', 'Заблокированные'); ?>"></span></th>
        <th><?php echo Yii::t('campaigns', 'Клики'); ?></th>
        <th><?php echo Yii::t('campaigns', 'Показы'); ?></th>
        <th><?php echo Yii::t('campaigns', 'CTR'); ?></th>
        <th><?php echo Yii::t('campaigns', 'Расход'); ?></th>
        <th><?php echo Yii::t('campaigns', 'Статус'); ?></th>
    </tr>
    </thead>
    <tbody>
        <?php if($campaigns): ?>
            <?php foreach($campaigns AS $campaign): ?>
                <tr id="<?php echo 'campaignId-'.$campaign->id; ?>">
                    <td>
                        <label class="checkbox" for="camp_list_check_<?php echo $campaign->id; ?>">
                            <input type="checkbox" id="camp_list_check_<?php echo $campaign->id; ?>" value="<?php echo $campaign->id; ?>" class="adloud_checkbox" data-toggle="checkbox">
                        </label>
                    </td>
                    <td><?php echo $campaign->id; ?></td>
                    <td class="camp_name">
                        <?php echo CHtml::link($campaign->description, Yii::app()->createUrl('advertiser/ads/list', ['campaignId' => $campaign->id])); ?><br/>
                        <div class="iconbar iconbar-horizontal">
                            <ul>
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('advertiser/ads/index', ['campaignId' => $campaign->id]), [
                                        'class' => 'fui-plus',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'data-tooltip-style' => 'light',
                                        'data-original-title' => Yii::t('campaigns', 'Добавить объявление'),
                                    ]); ?>
                                </li>
                                <!--<li>
                                    <a href="#" class="glyphicon glyphicon-stats" data-toggle="tooltip" data-placement="bottom" data-tooltip-style="light" data-original-title="Статистика"></a>
                                </li>-->
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('advertiser/campaign/index', ['id' => $campaign->id]), [
                                        'class' => 'fui-gear',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'data-tooltip-style' => 'light',
                                        'data-original-title' => Yii::t('campaigns', 'Настройки рекламной кампании'),
                                    ]); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link('', '#', [
                                        'class' => 'fui-trash delete-button auto-ajax',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'data-tooltip-style' => 'light',
                                        'data-original-title' => Yii::t('campaigns', 'Удалить рекламную кампанию'),
                                        'data-url' => Yii::app()->createUrl('advertiser/campaign/delete', ['id' => $campaign->id]),
                                        'data-list' => 'delete=1',
                                        'data-update' => '#campaignId-'.$campaign->id,
                                    ]); ?>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <?php if($containsLabel): ?>
                        <td>
                            <?php if($campaign->label): ?>
                                <div data-url="<?php echo Yii::app()->createUrl('advertiser/campaign/list', ['label' => $campaign->label->id]); ?>" class="auto-link">
                                    <span class="campaighn-mark-color" style="background-color: <?php echo $campaign->label->color; ?>;"></span>
                                    <span class="campaighn-mark-name"><?php echo $campaign->label->name ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td><?php echo $campaign->runned; ?></td>
                    <td><?php echo $campaign->paused; ?></td>
                    <td><?php echo $campaign->moderated; ?></td>
                    <td><?php echo $campaign->blocked; ?></td>
                    <td><?php echo $campaign->clicks; ?></td>
                    <td><?php echo $campaign->shows; ?></td>
                    <td><?php echo round($campaign->ctr, 3); ?>%</td>
                    <td><?php echo round($campaign->expenses, 3); ?> $</td>
                    <td>
                        <div class="<?php echo $campaign->statusClass; ?>" data-on-label="" data-off-label="" data-original-title="<?php echo $campaign->statusName; ?>" data-placement="bottom" data-toggle="tooltip" data-off-label="" data-on-label="">
                            <input type="checkbox"
                                data-id="<?php echo $campaign->id; ?>"
                                data-url="<?php echo Yii::app()->createUrl('advertiser/campaign/changeStatus'); ?>"
                                class="camp_switch"
                                <?php if($campaign->isEnabled) echo 'checked="true"'; ?>
                                <?php if($campaign->isEnabled) echo 'data-checked="checked"'; ?>
                                <?php if(!$campaign->isAllowedSwitch) echo 'disabled=true'; ?>
                            />
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else:?>
            <tr>
                <td  colspan="10">
                    <div class="alert alert-danger" style="text-align: center;">
                        <?php echo Yii::t('campaigns', 'Список рекламных кампаний пуст'); ?>.
                    </div>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->renderWidget('BulkOperations'); ?>

<div class="col-sm-9">
    <div class="row">
        <?php $this->widget('themes.adloud.widgets.LinkPager', array('pages'=>$pages)); ?>
        <!-- <li class="pagination-dropdown dropup">
                <i class="dropdown-arrow"></i>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fui-triangle-up"></i>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="#">10–20</a></li>
                  <li><a href="#">20–30</a></li>
                  <li><a href="#">40–50</a></li>
                </ul>
              </li> -->
    </div>
</div>
<!-- End Adloud content -->
</div>
</div>

<?php Yii::app()->clientScript->registerScript('campaigns-list', '
    CampList.init({
        dateFormat: Main.dateFormat,
        currentDate: "'.$date.'",
        startDate: "'.$startDate.'",
        endDate: "'.$endDate.'",
        setStartAndEndText: "'.Yii::t('calendar', 'Установите начальную и конечную дату').'",
        monthNames: '.Yii::t('calendar', '["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"]').',
        monthNamesShort: '.Yii::t('calendar', '["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"]').',
        dayNames: '.Yii::t('calendar', '["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"]').',
        dayNamesShort: '.Yii::t('calendar', '["вск","пнд","втр","срд","чтв","птн","сбт"]').',
        dayNamesMin: '.Yii::t('calendar', '["Вс","Пн","Вт","Ср","Чт","Пт","Сб"]').'
    });
'); ?>