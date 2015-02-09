<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 08.05.14
 * Time: 11:58
 * @var IndexController $this
 * @var UserRegistrationForm $model
 * @var string $loginFormId
 * @var string $afterRegFormId
 */
?>
<?php if(!isset($model)) $model = new UserRegistrationForm(); ?>

<?php echo CHtml::beginForm(Yii::app()->createUrl('index/registration'), 'post', [
    'class' => 'landing-reg-form'
]); ?>

    <div class="form-group">
        <?php echo CHtml::activeTextField($model, 'fullName', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('fullName'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-user"></span>
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
        <?php echo CHtml::activePasswordField($model, 'password', [
            'class' => 'form-control flat',
            'placeholder' => $model->getAttributeLabel('password'),
            'required' => true,
        ]); ?>
        <span class="input-icon fui-lock"></span>
    </div>

    <div class="form-group">
        <button id="register-button" data-id="#<?php echo $afterRegFormId; ?>" type="submit" class="btn btn-block adloud_btn">Присоединиться</button>
    </div>

<?php echo CHtml::endForm(); ?>

<div class="links-to-forms text-center">
    <span>У вас уже есть аккаунт?</span>
    <span data-id="#<?php echo $loginFormId; ?>" class="log-reg-recovery go-to-login form-opener">Войти</span>
</div>