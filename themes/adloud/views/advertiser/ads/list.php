<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 06.05.14
 * Time: 18:38
 * @var \application\modules\advertiser\controllers\AdsController $this
 * @var array $adsList
 * @var int $createdAds
 * @var Pagination $pages
 * @var int $campaignId
 */
?>

<div class="col-sm-12 teaser_list">

    <div class="toggle-campaign-status-panel">
        <button class="btn <?=$status == 'actual' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn" data-status="actual" type="button"><span class="fa fa-play"></span><?php echo Yii::t('ads_list', 'Активные'); ?></button>
        <button class="btn <?=$status == 'archived' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn" data-status="archived" type="button"><span class="fui-trash"></span><?php echo Yii::t('ads_list', 'Архивные'); ?></button>
    </div>
    <table class="table table-striped table-hover adloud_table">
        <thead>
        <tr>
            <th>
                <label class="checkbox toggle-all" for="checkbox-table-1">
                    <input type="checkbox" value="" id="checkbox-table-1" data-toggle="checkbox" class="adloud_checkbox">
                </label>
            </th>
            <th>ID</th>
            <th><?php echo Yii::t('ads_list', 'Картинка'); ?></th>
            <th><?php echo Yii::t('ads_list', 'Содержание тизера'); ?></th>
            <th><?php echo Yii::t('ads_list', 'Цена'); ?></th>
            <th><?php echo Yii::t('ads_list', 'Клики'); ?></th>
            <th><?php echo Yii::t('ads_list', 'Показы'); ?></th>
            <th><?php echo Yii::t('ads_list', 'CTR'); ?></th>
            <th><?php echo Yii::t('ads_list', 'Расход'); ?></th>
            <th><?php echo Yii::t('ads_list', 'Статус'); ?></th>
        </tr>
        </thead>
        <tbody>

            <?php foreach($adsList as $ads): ?>
                <tr>
                    <td>
                        <label class="checkbox" for="checkbox-table-2">
                            <input type="checkbox" class="adloud_checkbox" id="checkbox-table-2" value="<?php echo $ads->id; ?>" data-toggle="checkbox">
                        </label>
                    </td>
                    <td><?php echo $ads->id ?></td>
                    <td>
                        <img src="<?php echo $ads->image; ?>" width="100" height="100" />
                    </td>
                    <td class="teaser_content">
                        <p class="teaser_title"><?php echo CHtml::link($ads->caption, Yii::app()->createUrl('advertiser/ads/index', ['campaignId' => $campaignId, 'id' => $ads->id])); ?></p>
                        <p class="teaser_description"><?php echo $ads->description; ?></p>
                        <p class="teaser_link"><?php echo CHtml::link(CHtml::encode($ads->showUrl), $ads->url); ?></p>
                        <div class="iconbar iconbar-horizontal">
                            <ul>
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('advertiser/ads/index', ['campaignId' => $campaignId, 'id' => $ads->id]), [
                                        'class' => 'fui-search',
                                        'data-toggle' => 'tooltip',
                                        'data-tooltip-style' => 'light',
                                        'data-placement' => 'bottom',
                                        'data-original-title' => \Yii::t('ads_list', 'Редактировать тизер'),
                                    ]); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('advertiser/ads/delete', ['campaignId' => $campaignId, 'id' => $ads->id]), [
                                        'class' => 'fui-trash delete-button',
                                        'data-toggle' => 'tooltip',
                                        'data-tooltip-style' => 'light',
                                        'data-placement' => 'bottom',
                                        'data-original-title' => \Yii::t('ads_list', 'Удалить тизер'),
                                        'data-confirm' => \Yii::t('ads_list', 'Вы действительно хотите удалить тизер?'),
                                    ]); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link('', Yii::app()->createUrl('advertiser/ads/index', ['campaignId' => $campaignId, 'id' => $ads->id, 'action' => 'copy']), [
                                        'class' => 'fui-windows',
                                        'data-toggle' => 'tooltip',
                                        'data-tooltip-style' => 'light',
                                        'data-placement' => 'bottom',
                                        'data-original-title' => \Yii::t('ads_list', 'Копировать тизер'),
                                    ]); ?>

                                </li>
                            </ul>
                        </div>
                    </td>
                    <td class="teaser_price editable-attr" data-list="attrName=clickPrice&id=<?php echo $ads->id; ?>" data-title="<?php echo Yii::t('ads_list', 'Установите новую стоимоcть клика'); ?>" data-name="clickPrice" data-type="text" data-url="<?php echo Yii::app()->createUrl('advertiser/ads/changeAttr', ['campaignId' => $campaignId]); ?>">
                        <?php echo $ads->clickPrice; ?> $
                    </td>
                    <td><?php echo $ads->clicks; ?></td>
                    <td><?php echo $ads->shows; ?></td>
                    <td><?php echo $ads->ctr; ?>%</td>
                    <td><?php echo $ads->expenses; ?> $</td>
                    <td>
                        <div class="<?php echo $ads->statusClass; ?>" data-html="true" data-on-label="" data-off-label="" data-original-title="<?php echo \Yii::t('ads_list', $ads->statusName); ?>" data-placement="bottom" data-toggle="tooltip">
                            <input type="checkbox"
                                   class="teaser_switch"
                                   data-url="<?php echo Yii::app()->createUrl('advertiser/ads/changeStatus', ['id' => $ads->id, 'campaignId' => $campaignId]); ?>"
                                    <?php if($ads->isEnabled) echo 'checked="true"'; ?>
                                    <?php if($ads->isEnabled) echo 'data-checked="checked"'; ?>
                                    <?php echo !$ads->isAllowedSwitch ? ' disabled=true' : ''; ?>
                                />
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php $this->renderWidget('BulkOperations', ['urlOptions' => ['campaignId' => $campaignId]]); ?>

<div class="col-sm-9">
    <?php $this->widget('themes.adloud.widgets.LinkPager', ['pages'=>$pages]); ?>
</div>

<?php Yii::app()->clientScript->registerScript('adsListScript', '
    AdsList.init({
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