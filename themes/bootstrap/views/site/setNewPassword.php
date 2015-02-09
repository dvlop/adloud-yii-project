<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <?php if(CHtml::errorSummary($model)):?>
            <div class="alert alert-block alert-danger fade in">
                <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
            </div>
        <?php endif;?>

        <?php echo CHtml::beginForm(Yii::app()->createUrl('index/setNewPassword'), 'post', ['class' => 'reg-page']); ?>
        <div class="reg-header">
            <h2>Укажите новый пароль</h2>
        </div>

        <div class="row">
            <?php echo CHtml::hiddenField(get_class($model).'[email]', $model->email) ?>

            <div class="form-group col-sm-6 <?php if($model->hasErrors('password')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'password', array('class'=>'control-label')); ?> <span class="color-red">*</span>
                <?php echo CHtml::activePasswordField($model,'password', array('class' => 'form-control margin-bottom-20')); ?>
            </div>
            <div class="form-group col-sm-6 <?php if($model->hasErrors('password2')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'password2', array('class'=>'control-label')); ?> <span class="color-red">*</span>
                <?php echo CHtml::activePasswordField($model,'password2', array('class' => 'form-control margin-bottom-20')); ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="">
                <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn-u btn-block')); ?>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
