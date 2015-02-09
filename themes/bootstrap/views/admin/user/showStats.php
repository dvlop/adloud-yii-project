<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Текущие данные</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover" id="List">
            <thead>
            <tr>
                <th>Запрос на вывод</th>
                <th>Баланс пользователя</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?=$request?> $</td>
                <td><?=$currentBalance?> $</td>
            </tr>
            </tbody>
        </table>
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>
            <div class="form-group <?php if($model->hasErrors('text')) echo 'has-error';?>" style="margin-top: 10px">
                <?php echo CHtml::activeLabel($model, 'amount', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-2">
                    <?php echo CHtml::activeTelField($model,'amount', array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="form-group <?php if($model->hasErrors('text')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model, 'description', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-2">
                    <?php echo CHtml::activeTelField($model,'description', array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-9">
                    <?php echo CHtml::submitButton( 'Вывести деньги', array('class' => 'btn-u')); ?>
                    <?php echo CHtml::submitButton( 'Отменить', array('class' => 'btn-u', 'name' => 'cancel')); ?>
                </div>
            </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Статистика пользователя</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover" id="List">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Потратил</th>
                <th>Получил</th>
                <th>Баланс</th>
                <th>Заблокировано IP</th>
                <th>CRT</th>
                <th>Среднее время клика</th>
            </tr>
            </thead>
            <tbody>
            <?php if($stats):?>
                <?php foreach($stats AS $stat):?>
                    <tr>
                        <td>
                            <?php echo $stat['date'];?>
                        </td>
                        <td>
                            <?php echo $stat['outcome'];?>
                        </td>
                        <td>
                            <?php echo $stat['income'];?> $
                        </td>
                        <td>
                            <?php echo $stat['balance'];?> $
                        </td>
                        <td>
                            <?php echo $stat['blockedIpsCount'];?>
                        </td>
                        <td>
                            <?php echo $stat['ctr'];?>
                        </td>
                        <td>
                            <?php echo $stat['clickTime'];?>
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