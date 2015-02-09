<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 24.04.14
 * Time: 10:32
 * @var \application\modules\payment\models\MoneyForm $model
 * @var \application\modules\payment\controllers\PaymentController $this
 * @var array $paymentUrls
 */
?>

<?php \Yii::app()->clientScript
    ->registerScriptFile(\Yii::app()->request->baseUrl.'/'.$this->path.'/'.$this->moduleName.'/public/js/payment.js'); ?>

    <div class="alert alert-block alert-danger fade in <?php if(!CHtml::errorSummary($model)) echo 'hidden'; ?>" id="payment-error-id">
        <?php echo CHtml::errorSummary($model, \Yii::t('payment', 'Возникли некоторые ошибки:')); ?>
    </div>

    <div class="row">

        <?php $this->renderPartial($this->partials.'_topMenu'); ?>

        <!-- Adloud content -->
        <div class="col-sm-12">

            <?php echo CHtml::beginForm(Yii::app()->createUrl('payment/payment/addMoney'), 'post', array('id' => 'replenishment_balance', 'class' => 'col-sm-12')); ?>

                <div class="col-sm-6 select_payment_method">

                    <legend><?php echo Yii::t('payment', 'Пополнение'); ?></legend>
                    <div class="col-sm-4">
                        <label class="adloud_label payment_method_title" for="payment_method">
                            <?php echo Yii::t('payment', 'Способ оплаты:'); ?>
                        </label>
                    </div>

                    <div class="col-sm-8">
                        <?php foreach($model->paymentSystems as $key => $value): ?>

                            <?php
                                if($model->paymentSystem == $key){
                                    $checked = true;
                                    $class = 'gender_btn_on';
                                }else{
                                    $checked = false;
                                    $class = 'gender_btn_off';
                                }
                            ?>

                            <label class="radio adloud_label">
                                <input type="radio"
                                       value="<?php echo $key; ?>"
                                       class="<?php echo $class; ?>"
                                       data-toggle="radio"
                                       name="<?php echo get_class($model).'[paymentSystem]'; ?>"
                                        <?php if($checked) echo 'checked="true"'; ?>
                                    />
                                <?php echo  $value; ?>
                            </label>

                        <?php endforeach; ?>

                    </div>
                </div>

                <div class="col-sm-6 replenish_form" id="payment-form-container">

                </div>

            <?php echo CHtml::endForm(); ?>

            <?php $this->renderPartial($this->partials.'_transactionsStatistics'); ?>

        </div>

    </div>

<?php \Yii::app()->clientScript->registerScript('paymentPageScript', '
    Payment.init({
        paymentUrl: "'.\Yii::app()->createAbsoluteUrl('payment/payment/selectPayment').'",
        moneyUrl: "'.\Yii::app()->createAbsoluteUrl('payment/payment/enterMoney').'"
    });
'); ?>