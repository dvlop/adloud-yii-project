<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i>Финанасы</h3>
    </div>
    <div class="panel-body">
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>
        <div class="form-group <?php if($model->hasErrors('email')) echo 'has-error';?>" style="margin-top: 10px">
            <?php echo CHtml::activeLabel($model, 'email', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-2">
                <?php echo CHtml::activeTelField($model,'email', array('class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group <?php if($model->hasErrors('amount')) echo 'has-error';?>" style="margin-top: 10px">
            <?php echo CHtml::activeLabel($model, 'amount', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-2">
                <?php echo CHtml::activeTelField($model,'amount', array('class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group <?php if($model->hasErrors('description')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model, 'description', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-2">
                <?php echo CHtml::activeTelField($model,'description', array('class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?php echo CHtml::submitButton( 'Вывести деньги', array('class' => 'btn-u')); ?>
                <?php echo CHtml::submitButton( 'Отменить', array('class' => 'btn-u', 'name' => 'cancel')); ?>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>