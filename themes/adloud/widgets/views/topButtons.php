<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.05.14
 * Time: 16:02
 * @var array $buttons
 */
?>

<?php foreach($buttons as $button): ?>
    <?php if($button['class'] == 'date_pick'):?>
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
    <?php else:?>
<div class="<?php echo $button['class'] ?>" <?php if(isset($button['id'])) echo 'id="'.$button['id'].'"'; ?>>
    <?php if(isset($button['elements'])): ?>
        <?php foreach($button['elements'] as $name=>$element): ?>
            <?php if(!isset($element['class'])): ?>
                <?php foreach($element as $el): ?>
                    <?php $linkParams = isset($el['linkParams']) ? $el['linkParams'] : []; ?>
                    <<?php echo $name; ?>
                    class="<?php echo $el['class'] ?>"
                    <?php if(isset($el['type'])) echo 'type="'.$el['type'].'"'; ?>
                    <?php if(isset($el['id'])) echo 'id="'.$el['id'].'"'; ?>
                    <?php if(isset($el['value'])) echo 'value="'.$el['value'].'"'; ?>
                    <?php if(isset($el['readonly'])) echo 'readonly="'.$el['readonly'].'"'; ?>
                    <?php if(isset($el['data-id'])) echo 'data-id="'.$el['data-id'].'"'; ?>
                    <?php if(isset($el['data-url'])) echo 'data-url="'.$el['data-url'].'"'; ?>
                    <?php if(isset($el['url'])) echo 'href="'.Yii::app()->createUrl($el['url'], $linkParams).'"'; ?>>
                    <?php echo $el['name']; ?>
                    <?php if(isset($el['icon'])) echo '<span class="'.$el['icon'].'"></span>'; ?>
                    </<?php echo $name; ?>>
                <?php endforeach; ?>
            <?php else: ?>
                <?php $linkParams = isset($element['linkParams']) ? $element['linkParams'] : []; ?>
                <<?php echo $name; ?>
                class="<?php echo $element['class'] ?>"
                <?php if(isset($element['type'])) echo 'type="'.$element['type'].'"'; ?>
                <?php if(isset($element['id'])) echo 'id="'.$element['id'].'"'; ?>
                <?php if(isset($element['value'])) echo 'value="'.$element['value'].'"'; ?>
                <?php if(isset($element['readonly'])) echo 'readonly="'.$element['readonly'].'"'; ?>
                <?php if(isset($element['data-id'])) echo 'data-id="'.$element['data-id'].'"'; ?>
                <?php if(isset($element['data-url'])) echo 'data-url="'.$element['data-url'].'"'; ?>
                <?php if(isset($element['url'])) echo 'href="'.Yii::app()->createUrl($element['url'], $linkParams).'"'; ?>>
                <?php echo $element['name']; ?>
                <?php if(isset($element['icon'])) echo '<span class="'.$element['icon'].'"></span>'; ?>
                </<?php echo $name; ?>>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
    <?php endif; ?>
<?php endforeach; ?>