<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 08.05.14
 * Time: 11:58
 * @var SiteController $this
 * @var UserLoginForm $model
 * @var string $registerFormId
 * @var string $restoreFormId
 */
?>
<?php
    if(!isset($model)) $model = new UserLoginForm();
    $img = Yii::app()->theme->baseUrl.'/assets/images';
?>

<?php echo CHtml::beginForm(Yii::app()->createUrl('index/login'), 'post', [
    'class' => 'landing-login-form'
]); ?>

    <div class="form-group">
        <img src="<?php echo $img; ?>/adloud/landing/why-we-logo.png"/>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeEmailField($model, 'email', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('email'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-user"></span>
    </div>

    <div class="form-group">
        <?php echo CHtml::activePasswordField($model, 'password', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('password'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-lock"></span>
    </div>

    <div class="form-group">
        <button id="login-button" type="submit" class="btn btn-block btn-inverse">Войти</button>
    </div>

<?php echo CHtml::endForm(); ?>

<div class="links-to-forms text-center">
    <span data-id="#<?php echo $restoreFormId; ?>" class="log-reg-recovery go-to-recovery form-opener">Забыли пароль?</span><br/>
    <span data-id="#<?php echo $registerFormId; ?>" class="log-reg-recovery go-to-reg form-opener">Зарегистрироваться</span>
</div>