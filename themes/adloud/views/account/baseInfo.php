<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 27.05.14
 * Time: 13:48
 * @var AccountController $this
 * @var UserBaseInfoModel $model
 */
?>

<?php echo CHtml::beginForm('', 'post', ['id' => 'change_base_info', 'class' => 'after-reg-form']); ?>

    <div class="form-group">
        <?php echo CHtml::activeUrlField($model, 'siteUrl', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('siteUrl'),
            'required' => true,
        ]); ?>
        <span class="input-icon fa fa-globe"></span>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeUrlField($model, 'statLink', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('statLink'),
            'required' => true,
        ]); ?>
        <span class="input-icon fa fa-link"></span>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeTextField($model, 'statLogin', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('statLogin'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-user"></span>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeTextField($model, 'statPassword', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('statPassword'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-lock"></span>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeTextArea($model, 'description', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('description'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-chat"></span>
    </div>

    <div class="form-group row">
        <div class="col-sm-8 col-sm-offset-2">
            <button type="submit" class="btn adloud_btn btn-embossed btn-block" href="#">Добавить сайт<span class="fui-check pull-right"></span></button>
        </div>
    </div>

<?php echo CHtml::endForm(); ?>