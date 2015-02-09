<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 22.09.14
 * Time: 16:07
 * @var \application\modules\admin\controllers\MoneyController $this
 * @var \application\models\UserPayoutRequest $payment
 * @var integer $status
 */
?>

<?php use application\models\UserPayoutRequest; ?>

<td><?php echo $payment->id; ?></td>
<td><?php echo $payment->getRequestDate(); ?></td>
<td><a href="<?php echo Yii::app()->createUrl('admin/user/index', ['id' => $payment->getUserId()]); ?>"><?php echo $payment->getUserId(); ?></a></td>
<td><?php echo $payment->getUserName(); ?></td>
<td><?php echo $payment->getFormattedAmount(); ?></td>
<td><?php echo $payment->getPaymentDate(); ?></td>
<td><?php echo $payment->getStatusName(); ?></td>
<td>
    <?php if($payment->getIsConfirm()): ?>
        <button
            class="btn btn-success btn-xs tooltips auto-ajax"
            data-url="<?php echo \Yii::app()->createUrl('admin/money/setPrepaymentStatus', ['id' => $payment->id, 'state' => UserPayoutRequest::STATUS_IN_WORK, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="prepaymentStatus=1"
            data-original-title="Принять"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-check"></i>
        </button>
    <?php endif; ?>

    <?php if($payment->getInWork() || $payment->getIsConfirm()): ?>
        <button
            class="btn btn-danger btn-xs tooltips auto-ajax"
            data-url="<?php echo \Yii::app()->createUrl('admin/money/setPrepaymentStatus', ['id' => $payment->id, 'state' => UserPayoutRequest::STATUS_REJECTED, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="prepaymentStatus=1"
            data-original-title="Отклонить"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-times"></i>
        </button>

        <button
            class="btn btn-success btn-xs tooltips auto-ajax"
            data-url="<?php echo \Yii::app()->createUrl('admin/money/setPrepaymentStatus', ['id' => $payment->id, 'state' => UserPayoutRequest::STATUS_DONE, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="prepaymentStatus=1"
            data-original-title="Оплатить"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-usd"></i>
        </button>
    <?php endif; ?>

    <button
        class="btn btn-primary btn-xs tooltips write-message"
        data-content="#ticket-form-container"
        data-title="Написать пользователю сообщение"
        data-okbutton="Отправить"
        data-nosubmit="nosubmit"
        data-user="<?php echo $payment->getUserId(); ?>"
        data-original-title="Написать пользователю сообщение"
        data-toggle="tooltip"
        data-placement="top"
        title=""
        >
        <i class="fa fa-pencil"></i>
    </button>

    <?php if(!$payment->getIsConfirm() && !$payment->getInWork()): ?>

        <?php echo $payment->getStatusName(); ?>

    <?php endif; ?>

</td>