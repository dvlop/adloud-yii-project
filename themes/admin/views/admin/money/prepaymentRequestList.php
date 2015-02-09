<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 22.09.14
 * Time: 16:06
 * @var \application\modules\admin\controllers\MoneyController $this
 * @var \application\models\UserPayoutRequest $model
 * @var \application\models\UserPayoutRequest[] $payments
 * @var \application\models\TicketCategory[] $ticketCategories
 */
?>

    <div class="row table-header">

        <div class="col-lg-6 left-float">
            <label>Показывать:</label>
            <select data-attribute="status" class="form-control auto-select" id="check-status-id">
                <?php foreach($model->getSelectorStatuses() as $stat): ?>
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

        <table class="display table table-bordered table-striped datatable" id="prepayment-requests-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата запроса</th>
                    <th>ID пользователя</th>
                    <th>Имя пользователя</th>
                    <th>Сумма</th>
                    <th>Дата выплаты</th>
                    <th>Статус</th>
                    <th>Модерация</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($payments as $payment): ?>
                    <tr>
                        <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.admin.money._prepaymentRequestTableRow', ['payment' => $payment, 'status' => $model->status]); ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>

    <div id="ticket-form-container" class="hide">
        <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._adminTicketForm', [
            'categories' => $ticketCategories,
            'redirectUrl' => Yii::app()->createUrl('admin/money/prepaymentRequestList', ['status' => $model->status]),
        ]); ?>
    </div>


<?php Yii::app()->clientScript->registerScript('', '
    Prepayments.init();
'); ?>