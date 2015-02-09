<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 31.07.14
 * Time: 11:08
 * To change this template use File | Settings | File Templates.
 */
?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/ticketAdmin.js', CClientScript::POS_END); ?>

    <div class="col-lg-12 margin-bottom-40">

        <div class="col-lg-2">Показывать:</div>

        <div class="col-lg-3">
            <select id="check-filter-id">
                <option data-text="opened" value="opened">Открытые</option>
                <option data-text="closed" value="closed">Закрытые</option>
            </select>
        </div>

    </div>

    <div class="panel panel-blue margin-bottom-40">

        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon-user"></i> Тикеты пользователей</h3>
        </div>

        <div class="panel-body">

            <table class="table table-striped table-hover" id="prepayment-requests-list">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя пользователя</th>
                    <th>ID пользователя</th>
                    <th>Тема</th>
                    <th>Категория</th>
                    <th>Дата открытия</th>
                    <?php if($show == 'opened'): ?>
                        <th>Модерация</th>
                    <?php else: ?>
                        <th>Статус</th>
                    <?php endif; ?>
                </tr>
                </thead>

                <tbody>
                <?php foreach($ticketList as $ticket): ?>

                    <tr <?=$ticket->isNewMessageForAdmin() ? 'class="new-ticket"' : ''?>>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo $ticket->user->full_name; ?></td>
                        <td><?php echo $ticket->user->id; ?></td>
                        <td><?=$ticket->isNewMessageForAdmin() ? '<span class="fui-mail"></span>' : ''?><?php echo CHtml::link($ticket['name'], Yii::app()->createUrl('ticket/index/admin', ['id' => $ticket['id']])) ?></td>
                        <td><?php echo $ticket->category->name; ?></td>
                        <td><?php echo $ticket['date']; ?></td>
                        <?php if($show == 'opened'): ?>
                            <td>
                                <button
                                    data-toggle="modal"
                                    data-url="<?php echo Yii::app()->createAbsoluteUrl('admin/ticket/closeTicket', ['id'=>$ticket['id']]); ?>"
                                    data-tooltip="tooltip" data-placement="top" title="Закрыть тикет"
                                    class="btn btn-success btn-xs btnModerateAccept ticket-close"><i class="icon-remove"></i>
                                </button>
                            </td>
                        <?php else: ?>
                            <td>Закрыт</td>
                        <?php endif; ?>
                    </tr>

                <?php endforeach; ?>
                </tbody>

            </table>

        </div>

    </div>

<?php
Yii::app()->clientScript->registerScript('ticketAdmin', '
    TicketAdmin.init({
        getShow: "'.$show.'",
        pageBaseUrl: "/'.Yii::app()->controller->route.'/",
    });
');
?>