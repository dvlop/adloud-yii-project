<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 18.09.14
 * Time: 10:39
 * @var \application\modules\admin\controllers\UserController $this
 * @var \stdClass[] $users
 * @var \UsersForm $model
 * @var \application\models\TicketCategory[] $ticketCategories
 */
?>

<div class="row table-header">

    <div class="col-lg-12">

        <div class="col-lg-6 left-float">
            <label>Статус:</label>
            <select data-attribute="status" class="form-control auto-select" id="check-ban-id">
                <?php foreach($model->getStatusSelectors() as $status): ?>
                    <option
                        value="<?php echo $status->value; ?>"
                        <?php if($status->checked) echo 'selected="selected"'; ?>
                    >
                        <?php echo $status->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-lg-6 right-float">
            <label>Активность:</label>
            <select data-attribute="activity" class="form-control auto-select" id="check-activity-id">
                <?php foreach($model->getActivitySelectors() as $activity): ?>
                    <option
                        value="<?php echo $activity->value; ?>"
                        <?php if($activity->checked) echo 'selected="selected"'; ?>
                    >
                        <?php echo $activity->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

</div>

<div class="row">

    <table class="display table table-bordered table-striped datatable" id="users-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Логин</th>
                <th>Кол-во сайтов</th>
                <th>Показы</th>
                <th>Клики</th>
                <th>CTR (%)</th>
                <th>Заработок ($)</th>
                <th>Модерация</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                   <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.admin.user._userTableRow', [
                       'user' => $user,
                       'model' => $model,
                   ]); ?>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>

<div id="ticket-form-container" class="hide">
    <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._adminTicketForm', [
        'categories' => $ticketCategories,
        'redirectUrl' => Yii::app()->createUrl('admin/user/list', ['status' => $model->status, 'activity' => $model->activity]),
    ]); ?>
</div>

<?php Yii::app()->clientScript->registerScript('adminUsersListScript', '
    Users.init();
'); ?>