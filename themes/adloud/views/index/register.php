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

<?php if(!isset($model)) $model = new UserRegistrationForm(); ?>

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
            <?php echo Yii::t('register', 'Регистрация'); ?>
        </h1>
    </div>
    <div class="col-sm-11">

<?php echo CHtml::beginForm(Yii::app()->createUrl('index/registration'), 'post', [
    'class' => 'reg-form auth-form col-sm-5 col-sm-offset-4'
]); ?>
            <div class="form-group">
                <label for=""><?php echo Yii::t('register', 'Ваше имя:'); ?></label>
                <?php echo CHtml::activeTextField($model, 'fullName', [
                    'class' => 'form-control flat',
                    'required' => true,
                ]); ?>
                <span class="input-icon fui-user"></span>
            </div>

            <div class="form-group">
                <label for=""><?php echo Yii::t('register', 'mail:'); ?></label>
                <?php echo CHtml::activeEmailField($model, 'email', [
                    'class' => 'form-control flat',
                    'required' => true,
                ]); ?>
                <span class="input-icon fui-mail"></span>
            </div>

            <div class="form-group">
                <label for=""><?php echo Yii::t('register', 'Пароль:'); ?></label>
                <?php echo CHtml::activePasswordField($model, 'password', [
                    'class' => 'form-control flat',
                    'required' => true,
                ]); ?>
                <span class="input-icon fui-lock"></span>
            </div>

            <div class="form-group submit-group">
                <button id="register-button" type="submit" class="btn btn-block"><?php echo Yii::t('register', 'Присоединиться!'); ?></button>
            </div>

            <div class="form-group">
                <a href="#" data-id="#login-form" class="login-link auth-link form-opener"><?php echo Yii::t('register', 'Уже есть аккаунт'); ?></a>
            </div>

<?php echo CHtml::endForm(); ?>
    </div>
</div>

