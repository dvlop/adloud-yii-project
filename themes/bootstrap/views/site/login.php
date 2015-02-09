<!--=== Content Part ===-->
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <?php if(CHtml::errorSummary($model)):?>
                <div class="alert alert-block alert-danger fade in">
                    <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
                </div>
            <?php endif;?>

            <?php echo CHtml::beginForm(array('index/login'), 'post', array('class' => 'reg-page')); ?>
                <div class="reg-header">
                    <h2>Вход</h2>
                </div>

                <div class="input-group margin-bottom-20 form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <?php echo CHtml::activeTextField($model,'email', array('placeholder' => 'Электронная почта', 'class' => 'form-control')); ?>
                </div>
                <div class="input-group margin-bottom-20 form-group <?php if($model->hasErrors('password')) echo 'has-error';?>">
                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                    <?php echo CHtml::activePasswordField($model,'password', array('placeholder' => 'Пароль', 'class' => 'form-control')); ?>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <label class="checkbox" for="remember">
                            <?php echo CHtml::activeCheckBox($model,'remember', array('id' => 'remember')); ?>
                            <?php echo CHtml::activeLabel($model,'remember', array('class'=>'control-label')); ?>
                        </label>
                    </div>
                    <div class="col-md-5">
                        <?php echo CHtml::submitButton('Войти', array('class' => 'btn-u pull-right')); ?>
                    </div>
                </div>
            <?php echo CHtml::endForm(); ?>

            <hr>

            <h4>Забыли пароль?</h4>
            <p>Не переживайте. <a class="color-green" href="<?php echo Yii::app()->createUrl('index/restorePassword');?>">Нажмите тут</a> что бы его  сбросить.</p>
        </div>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->