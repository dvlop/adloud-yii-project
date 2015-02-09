<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.08.14
 * Time: 11:45
 * @var \application\modules\webmaster\controllers\MoneyController $this
 * @var \application\models\UserPayoutRequest $model
 * @var \application\models\UserPayoutRequest $activePayment
 * @var \CActiveDataProvider $dataProvider
 * @var \CPagination $page
 */
?>

<div class="col-sm-12 withdrawal">

    <div class="order_withdrawal col-sm-12">
        <p class="auto_withdrawal_date col-sm-6">
            <?php if(!$activePayment): ?>
                <?=Yii::t('webmaster_money', 'У Вас нет активных выплат')?>
            <?php else: ?>
                <?=Yii::t('webmaster_money', 'Дата выплаты:')?>
                <time datetime="<?php echo $activePayment->paymentDate; ?>"><?php echo Yii::t('webmaster_money', $activePayment->paymentDate); ?></time>
            <?php endif; ?>
        </p>

        <div class="col-md-4 col-sm-6 col-md-push-1">
            <a id="prepayment-link" class="btn btn-lg btn-embossed btn-block adloud_btn" data-url="<?php echo Yii::app()->createUrl('webmaster/money/prepayment'); ?>" href="#">
                <?=Yii::t('webmaster_money', 'Заказать выплату')?>
            </a>
        </div>

    </div>

    <legend><?=Yii::t('webmaster_money', 'Статистика выплат')?></legend>

    <table class="table table-striped table-hover adloud_table payments_stat">

        <thead>

        <tr>
            <th>ID</th>
            <th><?=Yii::t('webmaster_money', 'Дата')?></th>
            <th><?=Yii::t('webmaster_money', 'Время')?></th>
            <th><?=Yii::t('webmaster_money', 'Сумма')?></th>
            <th><?=Yii::t('webmaster_money', 'Статус')?></th>
            <th><?=Yii::t('webmaster_money', 'Комментарии')?></th>
        </tr>

        </thead>

        <tbody>

        <?php foreach($dataProvider->getData() as $payout): ?>

            <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.webmaster.partials._payoutRow', ['payout' => $payout]); ?>

        <?php endforeach; ?>
        </tbody>

    </table>

    <div class="col-sm-12">
        <div class="row">
            <?php $this->renderWidget('LinkPager', ['pages' => $dataProvider->getPagination()]); ?>
        </div>
    </div>

</div>

<?php Yii::app()->clientScript->registerScript('webmasterMoneyGet', '
    MoneyGet.init();
') ?>