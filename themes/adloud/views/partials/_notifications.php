<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 22.09.14
 * Time: 20:55
 */
?>


<li class="notices">
              <span class="open-notifications-bar">
                <img src="<?= Yii::app()->theme->baseUrl.'/assets/images/adloud/bell.png';?>"/>
                <span class="navbar-new notification-count <?=$notifications ? '' : 'hide'?>"><?=count($notifications)?></span>
              </span>

    <div class="notifications-bar notifications-new-item success-msg">
        <div class="notifications-item">

            <div class="notifications-content">

                <div class="notifications-icon">
                    <img src="<?= Yii::app()->theme->baseUrl.'/assets/images/adloud/notify-icon/ok.png';?>"/>
                </div>

                <p class="notifications-text"></p>

            </div>

        </div>
    </div>

    <div class="notifications-bar notifications-new-item error-msg">
        <div class="notifications-item">

            <div class="notifications-content">

                <div class="notifications-icon">
                    <img src="<?= Yii::app()->theme->baseUrl.'/assets/images/adloud/notify-icon/bad.png';?>"/>
                </div>

                <p class="notifications-text"></p>

            </div>

        </div>
    </div>

    <div class="notifications-bar notifications-history hide">

        <div class="notifications-list-bg"></div>

        <p class="notifications-bar-title"><?php echo Yii::t('landing', 'Уведомления'); ?></p>

        <ul class="notifications-list"></ul>

    </div>
</li>