<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 16.07.14
 * Time: 16:16
 * @var \application\modules\payment\controllers\PaymentController $this
 */
?>

<div class="row">
    <div class="col-sm-6">
        <h3>К сожалению, не удалось вполнить платёж. Поробуйте прозже</h3>
    </div>
    <div class="col-sm-6">
        <h3><?php echo \CHtml::link('Продолжить', '/payment'); ?></h3>
    </div>
</div>