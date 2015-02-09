<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 29.04.14
 * Time: 13:34
 * @var MoneyForm $model
 * @var PaymentController $this
 * @var array $formFields
 * @var string $url
 * @var string $paymentSystem
 * @var array $additionalFormFields
 */
?>
<div class="<?php echo isset($formDivClass) ? $formDivclass : 'col-lg-7'; ?>">
    <form method="<?php echo isset($method) ? $method : 'POST'; ?>" action="<?php echo $url; ?>" class="<?php echo isset($class) ? $class : 'form-horizontal'; ?>">

        <div class="<?php echo isset($formTitleClass) ? $formTitleClass : (isset($formGroupClass) ? $formGroupClass : 'headline'); ?>">
            <?php echo Yii::t('payment', 'Платёж с помощью'); ?> <?php echo $paymentSystem ?>
        </div>

        <div class="<?php echo isset($formGroupClass) ? $formGroupClass : 'form-group'; ?>">
            <label class="<?php echo isset($labelClass) ? $labelClass : 'col-lg-3 control-label'; ?>"><?php echo Yii::t('payment', 'Сумма к оплате:'); ?></label>
            <div class="<?php echo isset($inputDivClass) ? $inputDivClass : 'col-lg-7' ?>">
                <input type="text"
                       name="<?php echo isset($moneyName) ? $moneyName : 'money' ?>"
                       value="<?php echo isset($money) ? $money : $model->money; ?>"
                       class="<?php echo isset($inputClass) ? $inputClass : 'form-control'; ?>"
                    />
                <?php echo isset($currency) ? $currency : $model->currency; ?>
            </div>
        </div>

        <?php if(isset($additionalFormFields)): ?>
        <?php foreach($additionalFormFields as $field): ?>

        <div class="<?php echo isset($formGroupClass) ? $formGroupClass : 'form-group'; ?>">
            <label class="<?php echo isset($labelClass) ? $labelClass : 'col-lg-3 control-label'; ?>"><?php echo $field->label ?>:</label>
            <div class="<?php echo isset($field->inputDivClass) ? $field->inputDivClass : (isset($inputDivClass) ? $inputDivClass : 'col-lg-7'); ?>">
                <<?php echo isset($field->input) ? $field->input : 'input'; ?>
                type="<?php echo isset($field->type) ? $field->type: 'text' ?>"
                name="<?php echo $field->name ?>"
                value="<?php echo isset($field->value) ? $field->value: '' ?>"
                placeholder="<?php echo isset($field->placeholder) ? $field->placeholder: '' ?>"
                class="<?php echo isset($field->class) ? $field->class: (isset($inputClass) ? $inputClass : 'form-control') ?>"
                ><?php echo isset($field->inputValue) ? $field->inputValue : ''; ?></<?php echo isset($field->input) ? $field->input : 'input'; ?>>
            </div>
        </div>

<?php endforeach; ?>
<?php endif; ?>

<?php echo $formFields; ?>

<div class="<?php echo isset($formGroupClass) ? $formGroupClass : 'form-group'; ?>">
    <input class="<?php echo isset($buttonClass) ? $buttonClass: (isset($inputClass) ? $inputClass : 'btn-u') ?>" type="submit" name="submit-button" value="<?php echo Yii::t('payment', 'Оплатить'); ?>">
</div>

</form>
</div>