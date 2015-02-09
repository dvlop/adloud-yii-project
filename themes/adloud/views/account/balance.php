<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 29.07.14
 * Time: 10:49
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="col-sm-12">
    <div class="order_withdrawal col-sm-12">
        <div class="col-sm-6 balance_state" style="font-size: 20px; margin-top: 5px;">
            <?php echo Yii::t('balance', 'Состояние баланса'); ?>: <?=$balance?> $
        </div>
        <div class="col-md-3">
            <a href="<?php echo Yii::app()->createUrl('payment/payment/index'); ?>" class="btn btn-lg btn-embossed btn-block adloud_btn">
                <?php echo Yii::t('balance', 'Пополнить баланс'); ?>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo Yii::app()->createUrl('webmaster/money/index'); ?>" class="btn btn-lg btn-embossed btn-block adloud_btn" style="background-color: #34495E;">
                <?php echo Yii::t('balance', 'Вывод средств'); ?>
            </a>
        </div>
    </div>

    <legend><?php echo Yii::t('balance', 'История транзакций'); ?></legend>
    <?php if($logList):?>
    <table class="table table-striped table-hover adloud_table transactions_stat">
        <thead>
        <tr>
            <th class="col-sm-1">ID</th>
            <th class="col-sm-2"><?php echo Yii::t('balance', 'Дата'); ?></th>
            <th class="col-sm-2"><?php echo Yii::t('balance', 'Сумма'); ?></th>
            <th class="col-sm-7"><?php echo Yii::t('balance', 'Комментарии'); ?></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($logList as $log):?>
                <tr>
                    <td><?=$log['id']?></td>
                    <td><time datetime="<?=$log['date']?>"><?=$log['date']?></time></td>
                    <td class="transaction_<?= $log['sign'] == 'plus' ? 'plus">+'.$log['amount'] : 'minus">-'.$log['amount'] ?> $</td>
                    <td class="transaction_comment"><?=$log['comment']?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php else:?>
        <?php echo Yii::t('balance', 'По вашему балансу еще не проводились операции'); ?>
    <?php endif;?>
</div>