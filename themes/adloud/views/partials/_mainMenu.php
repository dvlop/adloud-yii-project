<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 08.05.14
 * Time: 11:34
 * @var SiteController $this
 */
$notifications = \application\models\Notification::model()->findAllByAttributes([
    'user_id' => Yii::app()->getUser()->getId(),
    'is_new' => true
],[
    'order' => 'date desc'
]);

$tickets = \application\models\Message::model()->findAllBySql('
            SELECT message.id
            FROM message
            INNER JOIN ticket ON (ticket.id = message.ticket_id)
            WHERE
            message.is_admin = TRUE AND
            message.status = 1 AND
            ticket.user_id = :user_id',
    [':user_id' => Yii::app()->getUser()->getId()]);
?>

<ul class="nav navbar-nav navbar-right">
    <?php if(!Yii::app()->user->isGuest && Yii::app()->user->isUser): ?>
        <li>
            <?php echo CHtml::link(Yii::t('landing', 'Рекламодатель'), Yii::app()->createUrl('advertiser/campaign/list')); ?>
        </li>
        <li>
            <?php echo CHtml::link(Yii::t('landing', 'Вебмастер'), Yii::app()->createUrl('webmaster/site/list')); ?>
        </li>
        <?php if(Yii::app()->user->isAdmin): ?>
            <li>
                <?php echo CHtml::link(Yii::t('landing', 'Администратор'), Yii::app()->createUrl('admin/index')); ?>
            </li>
        <?php endif; ?>
        <li>
            <?php echo CHtml::link(Yii::t('main', 'Баланс').': '.round(Yii::app()->user->balance, 2).'$', Yii::app()->createUrl('account/balance')); ?>
        </li>

        <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._notifications', ['notifications' => $notifications]); ?>

        <li class="support">
              <span class="open-support-bar" data-url="<?php echo Yii::app()->createUrl('ticket/index/list'); ?>">
                <span class="fui-mail"></span>
                <span class="navbar-new ticket-count <?=$tickets ? '' : 'hide'?>"><?=count($tickets)?></span>
              </span>
        </li>
    <?php endif; ?>
    <?php if(!Yii::app()->user->isGuest): ?>
        <li class="dropdown user-block">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img class="img-circle" src="<?php echo Yii::app()->user->avatar; ?>"> <?php echo Yii::app()->user->fullName; ?> <b class="caret"></b>
            </a>
            <span class="dropdown-arrow"></span>
            <ul class="dropdown-menu">
                <li>
                    <?php echo CHtml::link('<span>'.Yii::t('landing', 'Профиль').'</span>', Yii::app()->createUrl('account/index'), ['class' => 'highlighted']); ?>
                </li>
                <!--<li>
                    <a href="#">Баланс</a>
                </li>
                <li>
                    <a href="#">Тикеты (<span>1</span>)</a>
                </li>-->
                <li>
                    <?php echo CHtml::link('<span>'.Yii::t('landing', 'Выход').'</span>', Yii::app()->user->logoutUrl, ['class' => 'highlighted']); ?>
                </li>
            </ul>
        </li>
    <?php else: ?>
        <!--<li>
            <?php //echo CHtml::link('FAQ', '/site/faq', ['class' => 'highlighted']); ?>
        </li>
        <li>
            <?php //echo CHtml::link('О компании', '/index/about', ['class' => 'highlighted']); ?>
        </li>
        <li>
            <?php //echo CHtml::link('Блог', '/index/blog', ['class' => 'highlighted']); ?>
        </li>-->

        <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._notifications', ['notifications' => $notifications]); ?>

        <li>
            <?php echo CHtml::link('<span>'.Yii::t('landing', 'Вход').'</span>', Yii::app()->user->loginUrl, ['class' => 'highlighted']); ?>
        </li>
    <?php endif; ?>
</ul>