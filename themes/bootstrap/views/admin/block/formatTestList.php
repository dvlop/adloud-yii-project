<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title">
            Создать новый тест
        </h3>
    </div>
    <div class="panel-body">
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>
            <?php echo CHtml::textField('name'); ?>
            <?php echo CHtml::submitButton('Создать'); ?>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>

    <div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title">
            Список тестов <?= $format?>
        </h3>
    </div>

    <div class="panel-body">
        <table id="formats-stats" class="table table-striped table-hover">

            <thead>
            <tr>
                <th>Название</th>
                <th>Показы</th>
                <th>Клики</th>
                <th>CTR</th>
                <th>Статус</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach($tests as $test): ?>
                <tr>
                    <td><?php echo CHtml::link($test->name, Yii::app()->createUrl('admin/block/formatTest', ['id' => $test->id])); ?></td>
                    <td><?php echo 0?></td>
                    <td><?php echo 0 ?></td>
                    <td><?php echo 0 ?></td>
                    <td><?php echo $test->state ? 'Запущен' : 'Остановлен'?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>


</div>

