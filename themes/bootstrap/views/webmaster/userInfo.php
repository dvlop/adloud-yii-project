<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i>Личная информация</h3>
    </div>
    <div class="panel-body">
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>
        <div class="form-group <?php if($model->hasErrors('fullName')) echo 'has-error';?>" style="margin-top: 10px">
            <?php echo CHtml::activeLabel($model, 'fullName', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-4">
                <?php echo CHtml::activeTelField($model,'fullName', array('class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model, 'email', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-4">
                <?php echo CHtml::activeTelField($model,'email', array('class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group <?php if($model->hasErrors('webmoneyId')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model, 'webmoneyId', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-4">
                <?php echo CHtml::activeTelField($model,'webmoneyId', array('class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?php echo CHtml::submitButton( 'Сохранить', array('class' => 'btn-u')); ?>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>