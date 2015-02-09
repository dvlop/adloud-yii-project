<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 29.04.14
 * Time: 13:34
 * @var PaymentForm $model
 * @var PaymentController $this
 * @var array $formFields
 * @var string $url
 * @var string $paymentSystem
 * @var array $additionalFormFields
 */
?>
<legend class="<?php echo isset($formTitleClass) ? $formTitleClass : ''; ?>">
    <?php echo Yii::t('payment', 'Платёж с помощью'); ?> <?php echo $paymentSystem ?>
</legend>

<div class="<?php echo isset($formDivClass) ? $formDivclass : 'col-md-9 col-sm-12 col-md-push-2'; ?>">
    <form method="<?php echo isset($method) ? $method : 'POST'; ?>" action="<?php echo $url; ?>" class="<?php echo isset($class) ? $class : 'form-horizontal'; ?>">

        <div class="<?php echo isset($formGroupClass) ? $formGroupClass : 'form-group'; ?>">
            <label class="<?php echo isset($labelClass) ? $labelClass : 'adloud_label'; ?>"><?php echo Yii::t('payment', 'Выберите сумму:'); ?></label>
            <input type="text"
                   id="<?php echo isset($moneyId) ? $moneyId : 'payment-money-id'; ?>"
                   name="<?php echo isset($moneyName) ? $moneyName : 'money' ?>"
                   value="<?php echo isset($money) ? $money : $model->money; ?>"
                   class="<?php echo isset($inputClass) ? $inputClass : 'form-control'; ?>"
                   pattern="[0-9]*"
                   required="true"
                />
        </div>

        <?php if(isset($additionalFormFields)): ?>
        <?php foreach($additionalFormFields as $field): ?>

        <div class="<?php echo isset($formGroupClass) ? $formGroupClass : 'form-group'; ?>">
            <label class="<?php echo isset($labelClass) ? $labelClass : 'form-group'; ?>"><?php echo $field->label ?>:</label>
            <<?php echo isset($field->input) ? $field->input : 'input'; ?>
            type="<?php echo isset($field->type) ? $field->type: 'text' ?>"
            name="<?php echo $field->name ?>"
            value="<?php echo isset($field->value) ? $field->value: '' ?>"
            placeholder="<?php echo isset($field->placeholder) ? $field->placeholder: '' ?>"
            class="<?php echo isset($field->class) ? $field->class: (isset($inputClass) ? $inputClass : 'form-control flat') ?>"
            ><?php echo isset($field->inputValue) ? $field->inputValue : ''; ?></<?php echo isset($field->input) ? $field->input : 'input'; ?>>
        </div>

<?php endforeach; ?>
<?php endif; ?>

<div id="payment-hidden-fields">
    <?php echo $formFields; ?>
</div>


<div class="<?php echo isset($formGroupClass) ? $formGroupClass : 'form-group'; ?>">
    <button class="<?php echo isset($buttonClass) ? $buttonClass: (isset($inputClass) ? $inputClass : 'btn btn-block adloud_btn btn-embossed') ?>" type="submit" name="submit-button">
        <span class="input-icon fui-check pull-left fui-lg"></span><?php echo Yii::t('payment', 'Пополнить баланс'); ?>
    </button>
</div>

</form>
</div>