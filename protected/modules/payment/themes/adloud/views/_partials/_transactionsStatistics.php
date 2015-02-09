<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 08.05.14
 * Time: 15:21
 * @var \application\modules\payment\controllers\PaymentController $this
 */
?>

<?php $transactions = $this->module->external->transactions; ?>

<?php if($transactions): ?>

<legend>Статистика по транзакциям</legend>
<table class="table table-striped table-hover adloud_table transactions_stat">

    <thead>
        <tr>
            <th class="col-sm-1">ID</th>
            <th class="col-sm-3">Дата</th>
            <th class="col-sm-2">Сумма</th>
            <th class="col-sm-6">Комментарии</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach($transactions as $transaction): ?>
            <tr>
                <td>158</td>
                <td><time datetime="2014-03-08">8.03.2014</time></td>
                <td class="transaction_plus">+5000 руб.</td>
                <td class="transaction_comment">Попополнение баланса через платёжнуйю систему Webmoney WMR</td>
            </tr>
        <?php endforeach; ?>

    </tbody>

</table>

<?php endif; ?>