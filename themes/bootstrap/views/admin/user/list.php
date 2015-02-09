<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 01.08.14
 * Time: 11:50
 * @var \application\modules\admin\controllers\UserController $this
 * @var \application\models\Users $model
 * @var \application\models\Users $data
 */
?>

<div class="col-lg-12 margin-bottom-40">

    <div class="col-lg-1">Статус:</div>

    <div class="col-lg-2">
        <?php if($model->statuses): ?>
            <select name="userStatus" id="check-ban-id" class="users-filter-selector">
                <?php foreach($model->statuses as $status): ?>
                    <option
                        data-ban="<?php echo $status->value; ?>"
                        value="<?php echo $status->value; ?>"
                        <?php if($status->selected) echo 'selected="selected"'; ?>
                    >
                        <?php echo $status->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </div>

    <div class="col-lg-1">Активность:</div>

    <div class="col-lg-2">
        <?php if($model->activities): ?>
            <select name="userActivity" id="check-activity-id" class="users-filter-selector">
                <?php foreach($model->activities as $activity): ?>
                    <option
                        data-active="<?php echo $activity->value; ?>"
                        value="<?php echo $activity->value; ?>"
                        <?php if($activity->selected) echo 'selected="selected"'; ?>
                    >
                        <?php echo $activity->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </div>

</div>

<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-user"></i>Пользователи системы</h3>
    </div>

    <div class="panel-body">
        <?php $this->widget('zii.widgets.grid.CGridView', [
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => $model->listColumnsValues,
        ]); ?>
    </div>

</div>

<?php Yii::app()->clientScript->registerScript('usersList', '
    Users.init();
'); ?>

