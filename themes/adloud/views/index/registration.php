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

<?php if(!$registered): ?>

    <section class="registration">
        <h1><?php echo $this->pageName; ?></h1>
        <?php $this->partial('_registerForm', ['model' => $model]); ?>
    </section>

<?php else: ?>

    <section class="reg-success">
        <h1>Поздравляем!</h1>
        <div class="reg-success-icon">
            <span>
                <span class="fui-check"></span>
            </span>
        </div>
        <p class="reg-success-msg">Вы успешно зарегистрировались в системе</p>
        <a href="<?php echo Yii::app()->createAbsoluteUrl(Yii::app()->user->baseInfoUrl); ?>" class="btn adloud_btn">Далее</a>
    </section>

<?php endif; ?>