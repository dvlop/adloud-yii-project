<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 01.05.14
 * Time: 14:08
 * @var ControllerBase $this
 * @var UserLoginForm $form
 */
?>
<div class="login">

    <?php echo CHtml::link('<img
            alt="Добро пожаловать на AdLoud"
            src="'.Yii::app()->theme->baseUrl.'/assets/images/adloud/logo_in.png" />'.
        '<span class="welcome">Добро пожаловать на</span> <span class="title">Ad<span>Loud</span></span>',
        Yii::app()->createAbsoluteUrl('/index'),
        ['class'=>'login-icon']); ?>

    <?php echo CHtml::beginForm(Yii::app()->createUrl("index/login"), 'post', [
        'class' => 'login-form',
        'id' => 'login-form',
    ]); ?>

    <div class="form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
        <?php echo CHtml::activeEmailField($model, 'email', [
            'placeholder' => 'Введите ваш email',
            'class' => 'form-control login-field flat',
            'id' => 'login-name',
            'required' => 'true',
        ]); ?>
        <label class="login-field-icon fui-user"></label>
    </div>

    <div class="form-group <?php if($model->hasErrors('password')) echo 'has-error';?>">
        <?php echo CHtml::activePasswordField($model, 'password', [
            'placeholder' => 'Введите ваш пароль',
            'class' => 'form-control login-field flat',
            'id' => 'login-pass',
            'required' => 'true',
        ]); ?>
        <label class="login-field-icon fui-lock"></label>
    </div>

    <?php echo CHtml::submitButton('Авторизация в системе', ['class' => 'btn btn-primary btn-lg btn-block']); ?>

    <?php echo CHtml::link('Забыли пароль?', Yii::app()->createUrl("index/restorePassword"), ['class' => 'login-link']); ?>

    <?php echo CHtml::endForm(); ?>

</div>