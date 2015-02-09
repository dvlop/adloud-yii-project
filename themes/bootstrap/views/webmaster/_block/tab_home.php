<?php
/**
 * User: maksymenko.ml
 * Date: 17.03.14
 * Time: 13:04
 */
?>
<div class="form-horizontal">
    <div class="form-group <?php if($model->hasErrors('description')) echo 'has-error';?>">
        <?php echo CHtml::activeLabel($model,'description', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-lg-5">
            <?php echo CHtml::activeTextField($model,'description', array('class' => 'form-control')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabel($model,'razmerRamki', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-xs-2">
            <div class="input-group">
                <input type="text" class="span-2 form-control" value="2" readonly="true">
                <span class="input-group-addon">px.</span>
            </div>
        </div>
        <div class="col-xs-2">
            <?php echo CHtml::activeTextField($model,'razmerRamki', array(
                    'class' => 'span-12 slider',
                    'data-slider-min' => '1',
                    'data-slider-max' => '3',
                    'data-slider-value' => '2',
                    'data-slider-tooltip' => 'hide',
                )); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo CHtml::activeLabel($model,'cvetRamki', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-xs-2">
            <div class="input-group">
                <?php echo CHtml::activeTextField($model,'cvetRamki', array(
                        'class' => 'form-control color-picker',
                        'value' => '#eeeeee',
                        'readonly' => 'true',
                        'data-tooltip' => "tooltip",
                        'data-placement' => "top",
                        'title' => "Изменить цвет",
                    )); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabel($model,'cvetFona', array('class'=>'col-lg-3 control-label')); ?>
        <div class="col-xs-2">
            <div class="input-group">
                <?php echo CHtml::activeTextField($model,'cvetFona', array(
                        'class' => 'form-control color-picker',
                        'value' => '#ffffff',
                        'readonly' => 'true',
                        'data-tooltip' => "tooltip",
                        'data-placement' => "top",
                        'title' => "Изменить цвет",
                    )); ?>
            </div>
        </div>
    </div>
</div>