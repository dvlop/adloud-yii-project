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

        <div class="col-sm-8 text-center recover-send-msg">
            <p class="h1">
                На указанный адрес
            </p>
            <p>
                выслана инструкция по восстановлению пароля
            </p>
        </div>
        <div class="col-sm-12 text-center recover-send-img">
            <img src="<?=$img;?>/adloud/recover-pass-send.png"/>
        </div>
        <div class="col-sm-12 text-center recover-pass-verify">
            <a href="/auth" class="btn btn-inverse">
                Далее
            </a>
        </div>
</div>

