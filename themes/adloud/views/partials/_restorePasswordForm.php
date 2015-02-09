<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 08.05.14
 * Time: 11:58
 * @var SiteController $this
 * @var UserLoginForm $model
 * @var string $loginFormId
 * @var string $afterRestoreFormId
 */
?>
<?php
    if(!isset($model)) $model = new UserRestorePasswordForm();
    $img = Yii::app()->theme->baseUrl.'/assets/images';
?>

<?php echo CHtml::beginForm(Yii::app()->createUrl('index/restorePassword'), 'post', [
    'class' => 'forgot-pass',
    'id' => 'login-form',
]); ?>

    <div class="form-group text-center">
        <img src="<?php echo $img; ?>/adloud/landing/forgot-pass.png"/>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeEmailField($model, 'email', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('email'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-mail"></span>
    </div>

    <div class="form-group">
        <button data-id="#<?php echo $afterRestoreFormId; ?>" id="restore-button" type="submit" class="btn btn-block btn-inverse">Восстановить</button>
    </div>

<?php echo CHtml::endForm(); ?>

<div class="links-to-forms text-center">
    <span>Вспомнили пароль?</span>
    <span data-id="#<?php echo $loginFormId; ?>" class="log-reg-recovery go-to-login form-opener">Войти</span>
</div>