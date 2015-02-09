<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 24.04.14
 * Time: 14:31
 * @var MoneyForm $model
 * @var PaymentController $this
 * @var string $name
 * @var array $data
 */
?>

<div class="form-group <?php if($model->hasErrors($name)) echo 'has-error';?>" <?php if(isset($containerId)) echo 'id="'.$containerId.'"'; ?>>
    <?php echo CHtml::activeLabel($model, $name, array('class'=>'col-lg-3 control-label')); ?>
    <div class="col-lg-9">
        <?php echo CHtml::activeDropDownList($model, $name, $data, [
            'encode' => false,
            'class' => 'select',
        ]); ?>
    </div>
</div>

<?php
if(!isset($id))
    $id = get_class($model).'_'.$name;

if(!isset($onChange))
    $onChange = 'return true;';

\Yii::app()->clientScript->registerScript('multiSelect'.$name, '
    $("#'.$id.'").multiselect({
        maxHeight: "200",
        nonSelectedText: "Выберите платёжную систему",
        onChange: function(element, checked){'.$onChange.'}
    });
');
?>