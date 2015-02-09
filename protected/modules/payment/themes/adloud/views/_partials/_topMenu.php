<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 08.05.14
 * Time: 14:47
 * @var \application\modules\payment\controllers\PaymentController $this
 */
?>

<div class="col-md-5 col-sm-6 balance_state">
    <label for="balance_state">
        <?php echo Yii::t('payment', 'Состояние баланса:'); ?>
    </label>
    <input type="text" value="<?php echo $this->module->currencyRates->getFormatted($this->module->balance); ?>" readonly name="balance_state"/>
</div>