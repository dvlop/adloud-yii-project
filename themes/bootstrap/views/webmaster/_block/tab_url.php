<?php
/**
 * User: maksymenko.ml
 * Date: 17.03.14
 * Time: 13:20
 */
?>
<div class="form-horizontal">
    <div class="form-group">
        <?php echo CHtml::activeLabel($model,'razmerShriftaSsilki', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-xs-2">
            <div class="input-group">
                <input type="text" class="span-2 form-control" value="10" readonly="true">
                <span class="input-group-addon">px.</span>
            </div>
        </div>
        <div class="col-xs-2">
            <?php echo CHtml::activeTextField($model,'razmerShriftaSsilki', array(
                    'class' => 'span-12 slider',
                    'data-slider-min' => '8',
                    'data-slider-max' => '12',
                    'data-slider-value' => '10',
                    'data-slider-tooltip' => 'hide',
                )); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo CHtml::activeLabel($model,'cvetShriftaSsilki', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-xs-2">
            <div class="input-group color-picker">
                <?php echo CHtml::activeTextField($model,'cvetShriftaSsilki', array('class' => 'form-control', 'value' => '#dd1c1c', 'readonly' => 'true')); ?>
                <span class="input-group-addon"
                      data-tooltip="tooltip" data-placement="top" title="Изменить цвет"
                    >#</span>
            </div>
        </div>
    </div>
</div>
