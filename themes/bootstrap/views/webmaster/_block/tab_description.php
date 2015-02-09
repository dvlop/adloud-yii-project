<?php
/**
 * User: maksymenko.ml
 * Date: 17.03.14
 * Time: 13:20
 */
?>
<div class="form-horizontal">
    <div class="form-group">
        <?php echo CHtml::activeLabel($model,'razmerShriftaOpisania', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-xs-2">
            <div class="input-group">
                <?php echo CHtml::activeTextField($model,'razmerShriftaOpisania', array(
                        'class' => 'span-2 form-control',
                        'readonly' => 'true',
                        'value' => 13
                    )); ?>
                <span class="input-group-addon">px.</span>
            </div>
        </div>
        <div class="col-xs-2">
            <input
            class="span-12 slider"
            data-slider-min="8"
            data-slider-max="18"
            data-slider-value="13"
            data-slider-tooltip="hide"
                >
        </div>
    </div>
    <div class="form-group">
        <?php echo CHtml::activeLabel($model,'cvetShriftaOpisania', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-xs-2">
            <div class="input-group color-picker">
                <?php echo CHtml::activeTextField($model,'cvetShriftaOpisania', array(
                        'class' => 'form-control',
                        'value' => '#333333',
                        'readonly' => 'true',
                        'data-tooltip' => "tooltip",
                        'data-placement' => "top",
                        'title' => "Изменить цвет",
                    )); ?>
            </div>
        </div>
    </div>
</div>
