<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 07.05.14
 * Time: 16:33
 * @var Pagination $pages
 * @var \application\modules\webmaster\controllers\BlockController $this
 * @var array $blocksList
 * @var string $createdBlock
 * @var int $id
 * @var BlockForm $model
 * @var string $date
 * @var string $startDate
 * @var string $endDate
 * @var string $siteId
 * @var bool $showModal
 * @var string $blockTypesContent
 */
?>

<?php use application\models\Blocks; ?>

    <div class="col-sm-12 blocks_table">
        <div class="toggle-campaign-status-panel">
            <button
                class="btn <?=$status == 'actual' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn auto-link"
                data-status="actual"
                data-url="<?php echo Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId]); ?>"
                type="button"
                >
                <span class="fa fa-play"></span><?php echo Yii::t('webmaster_block', 'Активные'); ?>
            </button>
            <button
                class="btn <?=$status == 'archived' ? 'btn-inverse' : 'btn-default'?> campaign-status-btn auto-link"
                data-status="archived"
                data-url="<?php echo Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId, 'status' => 'archived']); ?>"
                type="button"
                >
                <span class="fui-trash"></span><?php echo Yii::t('webmaster_block', 'Архивные'); ?>
            </button>
        </div>
        <table class="table table-striped table-hover adloud_table">
            <thead>
            <tr>
                <th>
                    <label class="checkbox toggle-all" for="decks_checkbox_1">
                        <input type="checkbox" value="" id="decks_checkbox_1" data-toggle="checkbox" class="adloud_checkbox">
                    </label>
                </th>
                <th>ID</th>
                <th><?php echo Yii::t('webmaster_block', 'Название блока'); ?></th>
                <th><?php echo Yii::t('webmaster_block', 'Формат блока'); ?></th>
                <th><?php echo Yii::t('webmaster_block', 'Размер'); ?></th>
                <th><?php echo Yii::t('webmaster_block', 'Клики'); ?></th>
                <th><?php echo Yii::t('webmaster_block', 'Показы'); ?></th>
                <th><?php echo Yii::t('webmaster_block', 'CTR'); ?></th>
                <th><?php echo Yii::t('webmaster_block', 'Заработок'); ?></th>
                <th><?php echo Yii::t('webmaster_block', 'Статус'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($blocksList as $block): ?>
                <tr>
                    <td>
                        <label class="checkbox" for="decks_checkbox_2">
                            <input type="checkbox" value="<?php echo $block->id; ?>" id="decks_checkbox_<?php echo $block->id; ?>" class="adloud_checkbox" data-toggle="checkbox">
                        </label>
                    </td>
                    <td><?php echo $block->id; ?></td>
                    <td class="block_name">
                        <?php echo $block->description; ?><br/>
                        <div class="iconbar iconbar-horizontal">
                            <ul>
                                <li>
                                    <a
                                        href="<?php echo Yii::app()->createUrl('webmaster/stats/index'); ?>"
                                        class="glyphicon glyphicon-stats"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                        data-tooltip-style="light"
                                        data-original-title="<?php echo Yii::t('webmaster_block', 'Статистика'); ?>"
                                        ></a>
                                </li>
                                <li>
                                    <?php
                                    if($block->type == Blocks::FORMAT_MARKET || $block->type == Blocks::FORMAT_SIMPLE){
                                        $url = Yii::app()->createUrl('block/index/index', [
                                            'format' => $block->type,
                                            'siteId' => $siteId,
                                            'id' => $block->id,
                                        ]);
                                    }elseif($block->type == Blocks::FORMAT_MAIN){
                                        $url = Yii::app()->createUrl('block/index/main', [
                                            'siteId' => $siteId,
                                            'id' => $block->id,
                                        ]);
                                    }else{
                                        $url = Yii::app()->createUrl('webmaster/block/index', [
                                            'siteId' => $siteId,
                                            'id' => $block->id,
                                        ]);
                                    }
                                    ?>
                                    <a
                                        href="<?php echo $url; ?>"
                                        class="fui-gear add-block"
                                        data-toggle="tooltip"
                                        data-tooltip-style="light"
                                        data-placement="bottom"
                                        data-original-title="<?php echo Yii::t('webmaster_block', 'Настройки рекламного блока'); ?>"
                                        ></a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo Yii::app()->createUrl('webmaster/block/delete', ['siteId' => $siteId, 'id' => $block->id]); ?>"
                                        class="fui-trash delete-button"
                                        data-toggle="tooltip"
                                        data-tooltip-style="light"
                                        data-placement="bottom"
                                        data-confirm="<?php echo Yii::t('webmaster_block', 'Вы действительно хотите удалить блок?'); ?>"
                                        data-original-title="<?php echo Yii::t('webmaster_block', 'Удалить рекламный блок'); ?>"
                                        ></a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo Yii::app()->createUrl('block/index/codeModal', ['id' => $block->id]); ?>"
                                        class="fa fa-code show-block-code"
                                        data-toggle="tooltip"
                                        data-tooltip-style="light"
                                        data-placement="bottom"
                                        data-original-title="<?php echo Yii::t('webmaster_block', 'Показать код'); ?>"
                                        ></a>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td><?php echo $block->format; ?></td>
                    <td><?php echo $block->size; ?></td>
                    <td><?php echo $block->clicks ? $block->clicks : 0 ?></td>
                    <td><?php echo $block->shows ? $block->shows : 0 ?></td>
                    <td><?php echo $block->ctr ? $block->ctr : 0 ?>%</td>
                    <td><?php echo $block->expense ? $block->expense : 0 ?> $</td>
                    <td>
                        <div
                            class="<?php echo $block->statusClass; ?>"
                            data-on-label=""
                            data-off-label=""
                            data-tooltip-style="light"
                            data-placement="bottom"
                            data-toggle="tooltip"
                            data-original-title="<?php echo \Yii::t('webmaster_block', $block->status); ?>"
                        >
                            <input
                                type="checkbox"
                                class="blocks_switch"
                                data-id="<?php echo $block->id; ?>"
                                data-url="<?php echo Yii::app()->createUrl('webmaster/block/changeStatus', ['id' => $block->id]); ?>"
                                <?php echo $block->isEnabled ? 'checked=true' : ''; ?>
                                <?php echo $block->isEnabled ? 'data-checked="checked"' : ''; ?>
                                <?php echo !$block->isAllowedSwitch ? 'disabled="true"' : ''; ?>
                                />
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

        <?php $this->renderWidget('BulkOperations', ['urlOptions' => ['id' => $siteId]]); ?>

        <div class="col-sm-12">
            <div class="row">
                <?php $this->widget('themes.adloud.widgets.LinkPager', array('pages'=>$pages)); ?>
            </div>
        </div>
        <!-- End Adloud content -->

    </div>

<?php Yii::app()->clientScript->registerScript('blockList', '
    BlocksList.init({
        getModalUrl: "'.Yii::app()->createUrl('block/index/formatsModal', ['id' => $siteId]).'",
        lightFormatUrl: "'.Yii::app()->createUrl('webmaster/block/index', ['siteId' => $siteId]).'",
        proFormatUrl: "'.Yii::app()->createUrl('block/index/index', ['siteId' => $siteId]).'",
        lightFormatName: "'.\models\Block::BLOCK_LIGHT_FORMAT.'",
        proFormatName: "'.\models\Block::BLOCK_PRO_FORMAT.'",
        dateFormat: Main.dateFormat,
        clipboardPath: "'.Yii::app()->theme->baseUrl.'/assets/plugins/zeroclipboard/dist",
        currentDate: "'.$date.'",
        startDate: "'.$startDate.'",
        endDate: "'.$endDate.'",
        showModal: '.(int)$showModal.',
        setStartAndEndText: "'.Yii::t('calendar', 'Установите начальную и конечную дату').'",
        monthNames: '.Yii::t('calendar', '["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"]').',
        monthNamesShort: '.Yii::t('calendar', '["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"]').',
        dayNames: '.Yii::t('calendar', '["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"]').',
        dayNamesShort: '.Yii::t('calendar', '["вск","пнд","втр","срд","чтв","птн","сбт"]').',
        dayNamesMin: '.Yii::t('calendar', '["Вс","Пн","Вт","Ср","Чт","Пт","Сб"]').'
    });
'); ?>