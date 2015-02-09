<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 23.09.14
 * Time: 10:15
 * @var \application\modules\admin\controllers\MoneyController $this
 * @var \application\models\ReferalStats $model
 * @var \application\models\ReferalStats[] $payments
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

        <table class="display table table-bordered table-striped datatable" id="referal-payments-table">

            <thead>
            <tr>
                <th>ID</th>
                <th>ID Реферера</th>
                <th>ID Реферала</th>
                <th>Дата запроса</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Модерация</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach($payments as $payment): ?>
                <tr>
                    <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.admin.money._referalsPaymentTableRow', ['payment' => $payment, 'status' => $model->status]); ?>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>

    </div>


<?php Yii::app()->clientScript->registerScript('referalPaymentsList', '
    PeferalPayments.init();
'); ?>