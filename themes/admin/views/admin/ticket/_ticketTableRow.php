<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.09.14
 * Time: 11:09
 * @var \application\modules\admin\controllers\TicketController $this
 * @var \application\models\Ticket $ticket
 * @var integer $status
 */
?>
<?php use application\models\Ticket; ?>

<td><?php echo $ticket->id; ?></td>
<td><?php echo $ticket->user->full_name; ?></td>
<td><?php echo $ticket->user->id; ?></td>
<td>
    <?php $url = CHtml::link($ticket->name, Yii::app()->createUrl('ticket/index/admin', ['id' => $ticket->id])); ?>
    <?php if($ticket->isNewMessageForAdmin()): ?>
        <span class="fa fa-envelope-o"></span> <b><?php echo $url; ?></b>
    <?php else: ?>
        <?php echo $url; ?>
    <?php endif; ?>
</td>
<td><?php echo $ticket->category->name; ?></td>
<td><?php echo $ticket->date; ?></td>
<td>
    <?php if($ticket->getIsOpened()): ?>
        <button
            class="btn btn-success btn-xs tooltips auto-ajax"
            data-url="<?php echo Yii::app()->createUrl('admin/ticket/closeTicket', ['id' => $ticket->id, 'status' => $status]); ?>"
            data-closest="tr"
            data-params="status=<?php echo Ticket::STATUS_CLOSED; ?>"
            data-original-title="Закрыть тикет"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-check"></i>
        </button>
    <?php else: ?>
        Закрыт
    <?php endif; ?>
</td>