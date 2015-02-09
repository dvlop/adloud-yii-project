<?php
/**
 * User: maksymenko.ml
 * Date: 17.03.14
 * Time: 13:21
 * @var $model BlockForm
 */
?>
<div class="form-horizontal">
    <div class="form-group <?php if($model->hasErrors('adsNumberRows') || $model->hasErrors('adsNumberColumns')) echo 'has-error';?>">

        <div class="form-group <?php if($model->hasErrors('description')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'description', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-5">
                <?php echo CHtml::activeTextField($model,'description', array('class' => 'form-control')); ?>
            </div>
        </div>

        <div class="form-group <?php if($model->hasErrors('additionalCategories')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'type', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-9">
                <div class="input-group">
                    <?php echo CHtml::activeDropDownList($model, 'type', $model->getTypes(),array('class' => 'form-control', 'id' => 'BlockForm_type')); ?>
                </div>
            </div>
        </div>

        <div class="form-group <?php if($model->hasErrors('additionalCategories')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'css', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-9">
                <div class="input-group">
                    <?php echo CHtml::activeDropDownList($model, 'css', $model->getStyles(),array('class' => 'form-control', 'id' => 'BlockForm_css')); ?>
                </div>
            </div>
        </div>

        <div class="form-group <?php if($model->hasErrors('additionalCategories')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'adsNumberRows', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-4">
                <div class="input-group">
                    <?php echo CHtml::activeTextField($model,'adsNumberRows', array(
                            'class' => 'span-2 form-control',
                            'readonly' => 'true',
                        )); ?>
                    <span class="input-group-addon">шт.</span>
                    <div class="col-lg-3">
                        <input
                            id="ads-number-rows"
                            class="span-12 slider"
                            data-slider-min="1"
                            data-slider-max="5"
                            data-slider-value="<?php echo $model->adsNumberRows;?>"
                            data-slider-tooltip="hide"
                            data-url = "<?=Yii::app()->createUrl('webmaster/block/getPreview')?>"
                            >
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group <?php if($model->hasErrors('additionalCategories')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'adsNumberColumns', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-4">
                <div class="input-group">
                    <?php echo CHtml::activeTextField($model,'adsNumberColumns', array(
                        'class' => 'span-2 form-control',
                        'readonly' => 'true',
                    )); ?>
                    <span class="input-group-addon">шт.</span>
                    <div class="col-lg-3">
                        <input
                            id="ads-number-columns"
                            class="span-12 slider"
                            data-slider-min="1"
                            data-slider-max="5"
                            data-slider-value="<?php echo $model->adsNumberColumns;?>"
                            data-slider-tooltip="hide"
                            data-url = "<?=Yii::app()->createUrl('webmaster/block/getPreview')?>"
                            >
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
