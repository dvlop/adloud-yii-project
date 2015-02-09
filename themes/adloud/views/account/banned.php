<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 29.07.14
 * Time: 18:53
 * To change this template use File | Settings | File Templates.
 */
?>

<?php $img = Yii::app()->theme->baseUrl.'/assets/images';
            Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css'.'/additional-styles.css');?>

<div class="row">
    <section class="thank-you">
        <div class="col-sm-2">
            <a href="<?php Yii::app()->createUrl('index/index'); ?>" class="logo">
                <img src="<?php echo $img ?>/adloud/logo.png"/>
            </a>
        </div>
        <div class="col-sm-8 text-center">
            <h1>
                Здравствуйте, <?=Yii::app()->user->fullName?>!
            </h1>
        </div>
</div>

<div class="row">
    <div class="col-sm-6 col-sm-offset-3 text-center">
        <p class="ban-info">
            Нам очень жаль, но Ваш аккаунт был заблокирован в связи с подозрительными действиями. <br/><span class="ban-reason">Причина: <span class="ban-reason-value">накрутка</span>
        </p>
        <div class="ban-img">
            <img src="<?php echo $img ?>/adloud/ban-img.png"/>
        </div>
        <p class="if-you-do-not-agree">
            Если Вы считаете, что бан не обоснован, <br/>
            пожалуйста свяжитесь с нами через систему тикетов.
        </p>
        <div class="send-appeal" id="help-desk">
            <a href="/ticket" class="btn" type="button">
                Подать апелляцию
            </a>
        </div>
    </div>
</div>