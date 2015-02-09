<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/31/14
 * Time: 12:08 AM
 */
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <?php if(CHtml::errorSummary($model)):?>
        <div class="alert alert-block alert-danger fade in">
            <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
        </div>
        <?php endif;?>

        <?php echo CHtml::beginForm(Yii::app()->createUrl('index/registration'), 'post', array('class' => 'reg-page')); ?>
            <div class="reg-header">
                <h2>Регистрация нового пользователя</h2>
                <p>Уже зарегистрированы? Нажмите <a href="<?php echo Yii::app()->createUrl('index/login');?>" class="color-green">Вход</a> что бы авторизоваться.</p>
            </div>

            <div class="form-group <?php if($model->hasErrors('fullName')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'fullName', array('class'=>'control-label')); ?> <span class="color-red">*</span>
                <?php echo CHtml::activeTextField($model,'fullName', array('class' => 'form-control margin-bottom-20')); ?>
            </div>

            <div class="form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'email', array('class'=>'control-label')); ?> <span class="color-red">*</span>
                <?php echo CHtml::activeTextField($model,'email', array('class' => 'form-control margin-bottom-20')); ?>
            </div>

            <div class="row">
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
                <div class="form-group col-lg-10 <?php if($model->hasErrors('agree')) echo 'has-error';?>">
                    <label class="checkbox" for="agree">
                        <?php echo CHtml::activeCheckBox($model,'agree', array('id' => 'agree')); ?>
                        <?php echo CHtml::activeLabel($model,'agree', array('class'=>'control-label')); ?> <span class="color-red">*</span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <?php echo CHtml::submitButton('Зарегистрироваться', array('class' => 'btn-u btn-block')); ?>
                </div>
            </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
