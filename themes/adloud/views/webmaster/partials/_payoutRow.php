<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.08.14
 * Time: 12:43
 * @var \application\models\UserPayoutRequest $payout
 */
?>

<tr>
    <td><?php echo $payout->id; ?></td>
    <td><time datetime="<?php echo $payout->dateString; ?>"><?php echo $payout->dateString; ?></time></td>
    <td><time datetime="<?php echo $payout->timeString; ?>"><?php echo $payout->timeString; ?></time></td>
    <td><?php echo $payout->getFormattedAmount(); ?></td>
    <td><?php echo Yii::t('webmaster_money', $payout->statusName); ?></td>
    <td><?php echo $payout->comment; ?></td>
</tr>