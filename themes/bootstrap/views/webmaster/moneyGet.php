<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 05.03.14
 * Time: 2:16
 */
?>
<?php if(CHtml::errorSummary($model)):?>
    <div class="alert alert-block alert-danger fade in">
        <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
    </div>
<?php endif;?>

<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-edit"></i> Вывести деньги</h3>
    </div>
    <div class="panel-body">
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>

        <div class="form-group <?php if($model->hasErrors('amount')) echo 'has-error';?>">
            <?php echo CHtml::activeLabel($model,'amount', array('class'=>'col-lg-3 control-label')); ?>
            <div class="col-lg-9">
                <?php echo CHtml::activeTextField($model,'amount', array('class' => 'form-control', 'placeholder' => '0.00')); ?>
            </div>
        </div>
        <div class="col-lg-3" style="text-align: right">
            <b>Баланс </b>
        </div>
        <div class="col-lg-9">
            <?=$balance?> $
        </div>
        <hr>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?php echo CHtml::submitButton('Вывести деньги', array('class' => 'btn-u')); ?>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>

        <table class="table table-striped table-hover" id="List">
            <thead>
            <tr>
                <th>Дата запроса</th>
                <th>Сумма</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <?php if($moneyPayoutRequestList):?>
                <?php foreach($moneyPayoutRequestList AS $request):?>
                    <tr>
                        <td>
                            <?php echo $request['dateTime'];?>
                        </td>
                        <td>
                            <?php echo $request['amount'];?>
                        </td>
                        <td>
                            <?php if($request['status'] == 1):?>
                                <i class="icon-check-sign icon-color-green icon-no-border"
                                   data-tooltip="tooltip" data-placement="top" title="Деньги выведены"
                                    ></i>
                            <?php elseif($request['status'] == 2):?>
                                <i class="icon-check-sign icon-color-red icon-no-border"
                                   data-tooltip="tooltip" data-placement="top" title="Отказ"
                                    ></i>
                            <?php else:?>
                                <i class="icon-check-sign icon-color-grey icon-no-border"
                                   data-tooltip="tooltip" data-placement="top" title="Запрос ожидает рассмотрения"
                                    ></i>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <div class="alert alert-danger" style="text-align: center;">
                            Список пуст.
                        </div>
                    </td>
                </tr>
            <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

