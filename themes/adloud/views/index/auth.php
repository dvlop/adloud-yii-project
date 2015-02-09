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

<?php if(!isset($model)) $model = new UserLoginForm(); ?>

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
            <?php echo Yii::t('auth', 'Вход'); ?>
        </h1>
    </div>
    <div class="col-sm-11">

<?php echo CHtml::beginForm(Yii::app()->createUrl('index/login'), 'post', [
    'class' => 'login-form auth-form col-sm-5 col-sm-offset-4'
]); ?>
        <div class="form-group">
            <label for="">E-mail:</label>
            <?php echo CHtml::activeEmailField($model, 'email', [
                'class' => 'form-control flat',
                'required' => true,
            ]); ?>
            <span class="input-icon fui-mail"></span>
        </div>

        <div class="form-group">
            <label for=""><?php echo Yii::t('auth', 'Пароль:'); ?></label>
            <?php echo CHtml::activePasswordField($model, 'password', [
                'class' => 'form-control flat',
                'required' => true,
            ]); ?>
            <span class="input-icon fui-lock"></span>
        </div>

        <div class="form-group submit-group">
            <button id="login-button" type="submit" class="btn btn-block btn-inverse"><?php echo Yii::t('auth', 'Войти'); ?></button>
        </div>

        <div class="form-group">
            <a href="<?php echo Yii::app()->createUrl('index/recovery'); ?>" class="forgot-pass-link auth-link"><?php echo Yii::t('auth', 'Забыли пароль?'); ?></a><br/>
            <a href="<?php echo Yii::app()->createUrl('index/register'); ?>" class="create-new-ak-link auth-link"><?php echo Yii::t('auth', 'Создать новый аккаунт'); ?></a>
        </div>

<?php echo CHtml::endForm(); ?>
    </div>
</div>

