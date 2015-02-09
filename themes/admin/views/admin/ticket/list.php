<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.09.14
 * Time: 10:34
 * @var \application\modules\admin\controllers\TicketController $this
 * @var \application\models\Ticket[] $tickets
 * @var \application\models\Ticket $model
 */
?>

<div class="row table-header">

    <div class="col-lg-6 left-float">
        <label>Показывать:</label>
        <select data-attribute="status" class="form-control auto-select" id="check-status-id">
            <?php foreach($model->getStatusSelectors() as $stat): ?>
                <option
                    value="<?php echo $stat->value; ?>"
                    <?php if($stat->checked) echo 'selected="selected"'; ?>
                    >
                    <?php echo $stat->name; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

</div>

<div class="row">

    <table class="display table table-bordered table-striped datatable" id="tickets-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Имя пользователя</th>
                <th>ID пользователя</th>
                <th>Тема</th>
                <th>Категория</th>
                <th>Дата открытия</th>
                <th>Модерация</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($tickets as $ticket): ?>
                <tr<?php echo $ticket->isNewMessageForAdmin() ? ' class="new-ticket"' : ''; ?>>
                    <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.admin.ticket._ticketTableRow', ['ticket' => $ticket, 'status' => $model->status]); ?>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>

<?php Yii::app()->clientScript->registerScript('ticketList', '
    Tickets.init();
'); ?>