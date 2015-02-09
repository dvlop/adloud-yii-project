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
    ->registerScriptFile(\Yii::app()->request->baseUrl.'/'.$this->path.'/'.$this->moduleName.'/public/js/payment.js', CClientScript::POS_END) ?>

    <div class="alert alert-block alert-danger fade in <?php if(!CHtml::errorSummary($model)) echo 'hidden'; ?>" id="payment-error-id">
        <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
    </div>

    <div class="panel panel-blue margin-bottom-40">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon-edit"></i>Пополнение счёта</h3>
        </div>
        <div class="panel-body">
            <?php echo CHtml::beginForm(Yii::app()->createUrl('payment/payment/addMoney'), 'post', array('class' => 'form-horizontal')); ?>

            <div class="form-group <?php if($model->hasErrors('currency')) echo 'has-error';?>" id="payment-currency-id">
                <?php echo CHtml::activeLabel($model, 'currency', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeDropDownList($model, 'currency', $model->currencies, [
                        'encode' => false,
                        'class' => 'select',
                    ]); ?>
                </div>
            </div>

            <div id="payment-paymentSystem-id" <?php if(!$model->currency) echo 'class="hidden"' ?>>
                <?php $this->renderPartial('application.modules.'.$this->moduleName.'.views._partials.dropDown', [
                    'model' => $model,
                    'data' => $model->currency ? $model->paymentSystems : array(),
                    'name' => 'paymentSystem',
                    'onChange' => 'Payment.selectPayment(element, checked);',
                ]); ?>
            </div>

            <?php echo CHtml::endForm(); ?>
            <div id="payment-system-form-container-id"></div>
        </div>
    </div>

<?php \Yii::app()->clientScript->registerScript('paymentPageScript', '
    Payment.init({
        currencyUrl: "'.\Yii::app()->createAbsoluteUrl('payment/payment/selectCurrency').'",
        paymentUrl: "'.\Yii::app()->createAbsoluteUrl('payment/payment/selectPayment').'"
    });
'); ?>

<?php \Yii::app()->clientScript->registerScript('selectCurrency', '
    $("#MoneyForm_currency").multiselect({
        maxHeight: "200",
        nonSelectedText: "Выберите валюту",
        onChange: function(element, checked){
            Payment.selectCurrency(element, checked);
        }
    });
'); ?>