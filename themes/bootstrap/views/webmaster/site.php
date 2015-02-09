<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/2/14
 * Time: 11:47 PM
 */
?>

<?php if(CHtml::errorSummary($model)):?>
    <div class="alert alert-block alert-danger fade in">
        <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
    </div>
<?php endif;?>

<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-edit"></i> Информация о площадке</h3>
    </div>
    <div class="panel-body">
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>
        <div class="form-group <?php if($model->hasErrors('description')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'description', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-9">
                <?php echo CHtml::activeTextArea($model,'description', array('class' => 'form-control')); ?>
            </div>
        </div>

        <div class="form-group <?php if($model->hasErrors('categories')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'categories', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-9">
                <?php echo CHtml::activeDropDownList($model, 'categories', $model->getCategories(), array('multiple'=>'multiple', 'class'=>'multiselect')); ?>
            </div>
        </div>

        <div class="form-group <?php if($model->hasErrors('url')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'url', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-9">
                <?php echo CHtml::activeTextField($model,'url', array('class' => 'form-control', 'placeholder' => 'http://')); ?>
            </div>
        </div>


        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?php echo CHtml::submitButton(empty($model->description) ? 'Добавить площадку' : 'Редактировать площадку', array('class' => 'btn-u')); ?> или <a href="<?php echo Yii::app()->createUrl('webmaster/site/list');?>">Отменить</a>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>