<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 23.09.14
 * Time: 15:00
 * @var \application\modules\admin\controllers\MoneyController $this
 * @var \application\models\Transactions $transaction
 */
?>

<td><?php echo $transaction->id; ?></td>
<td><?php echo $transaction->getDateTime(); ?></td>
<td><?php echo $transaction->ip; ?></td>
<td><?php echo $transaction->referer; ?></td>
<td><?php echo $transaction->getAdsId(); ?></td>
<td><?php echo $transaction->getBlockId(); ?></td>
<td><?php echo $transaction->amount; ?></td>
<td><?php echo $transaction->getSenderId(); ?></td>
<td><?php echo $transaction->getRecipientId(); ?></td>
<td><?php echo $transaction->getSenderBalance(); ?></td>
<td><?php echo $transaction->getRecipientBalance(); ?></td>
