<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 07.05.14
 * Time: 11:40
 * @var array $sitesList
 * @var string $createdSite
 * @var Pagination $pages
 * @var string $date
 * @var string $startDate
 * @var string $endDate
 */
?>

<div class="col-sm-12 decks">
    <div class="toggle-campaign-status-panel">
        <button
            class="btn <?=$status == 'actual' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn auto-link"
            data-status="actual"
            data-url="<?php echo Yii::app()->createUrl('webmaster/site/list'); ?>"
            type="button"
        >
            <span class="fa fa-play"></span><?=\Yii::t('sites','Активные');?>
        </button>
        <button
            class="btn <?=$status == 'archived' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn auto-link"
            data-status="archived"
            data-url="<?php echo Yii::app()->createUrl('webmaster/site/list', ['status' => 'archived']); ?>"
            type="button"
        >
            <span class="fui-trash"></span><?=\Yii::t('sites','Архивные');?>
        </button>
    </div>
    <table class="table table-striped table-hover adloud_table">
        <thead>
        <tr>
            <th>
                <label class="checkbox toggle-all" for="site_list_check_1">
                    <input type="checkbox" value="" id="site_list_check_1" data-toggle="checkbox" class="adloud_checkbox">
                </label>
            </th>
            <th>ID</th>
            <th><?=\Yii::t('sites','Адрес площадки');?></th>
            <th><?=\Yii::t('sites','Категория');?></th>
            <th<?=\Yii::t('sites','Клики');?>></th>
            <th><?=\Yii::t('sites','Показы');?></th>
            <th>CTR</th>
            <th><?=\Yii::t('sites','Заработок');?></th>
            <th><?=\Yii::t('sites','Статус');?></th>
        </tr>
        </thead>
        <tbody>
        <?php if($sitesList): ?>
            <?php foreach($sitesList AS $site): ?>
                <tr id="<?php echo 'siteId'.$site->id; ?>">
                    <td>
                        <label class="checkbox" for="site_list_check_<?php echo $site->id; ?>">
                            <input type="checkbox" value="<?php echo $site->id; ?>" id="site_list_check_<?php echo $site->id; ?>" class="adloud_checkbox" data-toggle="checkbox">
                        </label>
                    </td>
                    <td><?php echo $site->id; ?></td>
                    <td class="decks_addres">
                        <span class="auto-link" data-url="<?php echo Yii::app()->createUrl('webmaster/block/list', ['siteId' => $site->id]); ?>"><?php echo $site->url; ?></span>
                        <br/>
                        <div class="iconbar iconbar-horizontal">
                            <ul>
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('block/select/format', ['siteId' => $site->id]), [
                                        'class' => 'fui-plus add-block',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'data-tooltip-style' => 'light',
                                        'data-original-title' => \Yii::t('sites', 'Добавить рекламный блок'),
                                        'data-id' => $site->id,
                                    ]); ?>
                                </li>
                                <!--<li>
                                    <?php /*echo CHtml::link('', Yii::app()->createUrl('webmaster/block/index', ['siteId' => $site->id]), [
                                        'class' => 'glyphicon glyphicon-stats',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'data-tooltip-style' => 'light',
                                        'data-original-title' => 'Статистика',
                                    ]);*/ ?>
                                </li>-->
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('webmaster/site/index', ['id' => $site->id]), [
                                        'class' => 'fui-gear',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'data-tooltip-style' => 'light',
                                        'data-original-title' => \Yii::t('sites', 'Настройка сайта'),
                                    ]); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('webmaster/site/delete', ['id' => $site->id]), [
                                        'class' => 'fui-trash delete-button',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'data-tooltip-style' => 'light',
                                        'data-confirm' => \Yii::t('sites', 'Вы уверены, что хотите удалить этот сайт'),
                                        'data-original-title' => \Yii::t('sites', 'Удалить сайт'),
                                    ]); ?>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td><?php echo $site->additionalCategory ? \Yii::t('campaign', $site->categoryName).', '.\Yii::t('campaign', $site->additionalCategoryName) : \Yii::t('campaign', $site->categoryName); ?></td>
                    <td><?php echo $site->clicks; ?></td>
                    <td><?php echo $site->shows; ?></td>
                    <td><?php echo $site->ctr; ?>%</td>
                    <td><?php echo $site->income; ?></td>
                    <td>
                        <div class="<?php echo $site->statusClass; ?>" data-on-label="" data-off-label="" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo Yii::t('sites',$site->statusName); ?>" data-url="<?php echo $site->dataUrl; ?>">
                            <input type="checkbox"
                                <?php echo $site->isEnabled ? ' checked=true' : ''; ?>
                                <?php echo $site->isEnabled ? 'data-checked="checked"' : ''; ?>
                                class="decks_switch"
                                <?php echo !$site->isAllowedSwitch ? ' disabled=true' : ''; ?> />
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else:?>
            <tr>
                <td  colspan="9">
                    <div class="alert alert-danger" style="text-align: center;">
                       <?=\Yii::t('campaign', 'Список сайтов пуст');?>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php $this->renderWidget('BulkOperations'); ?>

    <div class="col-sm-12">
        <div class="row">
            <?php $this->renderWidget('LinkPager', ['pages'=>$pages]); ?>
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

<?php Yii::app()->clientScript->registerScript('sitesList', '
    SitesList.init({
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