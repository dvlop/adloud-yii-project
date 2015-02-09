<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 23.09.14
 * Time: 10:16
 * @var \application\modules\admin\controllers\MoneyController $this
 * @var \application\models\ReferalStats $payment
 * @var integer $status
 */
?>

<td><?php echo $payment->id; ?></td>
<td><?php echo $payment->getRefererId(); ?></td>
<td><?php echo $payment->getRefererId(); ?></td>
<td><?php echo $payment->date; ?></td>
<td><?php echo $payment->sum; ?></td>
<td><?php echo $payment->getStatusName(); ?></td>
<dt>
    ---
</dt>