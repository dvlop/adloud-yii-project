<!--=== Content Part ===-->
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <?php if(CHtml::errorSummary($model)):?>
                <div class="alert alert-block alert-danger fade in">
                    <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
                </div>
            <?php endif;?>

            <?php echo CHtml::beginForm(Yii::app()->createUrl('index/restorePassword'), 'post', ['class' => 'reg-page']); ?>
            <div class="reg-header">
                <h2>Укажите Ваш e-mail</h2>
            </div>

            <div class="input-group margin-bottom-20 form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <?php echo CHtml::activeTextField($model,'email', array('placeholder' => 'Электронная почта', 'class' => 'form-control')); ?>
            </div>


            <div class="row">
                <div class="col-md-5">
                    <?php echo CHtml::submitButton('Отправить', array('class' => 'btn-u pull-right')); ?>
                </div>
            </div>
            <?php echo CHtml::endForm(); ?>

        </div>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->