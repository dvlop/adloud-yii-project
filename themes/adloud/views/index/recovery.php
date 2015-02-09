<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 07.05.14
 * Time: 10:24
 * @var UserRegistrationForm $model
 * @var SiteController $this
 * @var bool $registered
 */
?>

<?php if(!isset($model)) $model = new UserRestorePasswordForm(); ?>

<?php
    $img = Yii::app()->theme->baseUrl.'/assets/images';
?>

<div class="row">
    <div class="col-sm-2">
        <a href="/" class="logo">
            <img src="<?=$img;?>/adloud/logo.png"/>
        </a>
    </div>
    <div class="col-sm-8 text-center">
        <h1>
            Восстановление пароля
        </h1>
    </div>
    <div class="col-sm-11">

<?php echo CHtml::beginForm(Yii::app()->createUrl('index/restorePassword'), 'post', [
    'class' => 'recover-pass-form auth-form col-sm-5 col-sm-offset-4'
]); ?>
        <div class="form-group">
            <label for="">E-mail:</label>
            <?php echo CHtml::activeEmailField($model, 'email', [
                'class' => 'form-control flat',
                'required' => true,
            ]); ?>
            <span class="input-icon fui-mail"></span>
        </div>

        <div class="form-group submit-group">
            <button id="restore-button" type="submit" class="btn btn-block btn-inverse">Восстановить</button>
        </div>

        <div class="form-group">
            <a href="#" data-id="#login-form" class="login-link auth-link form-opener">Войти</a><br/>
            <a href="#" data-id="#registration-form" class="create-new-ak-link auth-link form-opener">Создать новый аккаунт</a>
        </div>

<?php echo CHtml::endForm(); ?>
    </div>
</div>

