<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 18.09.14
 * Time: 13:55
 * @var \application\modules\admin\controllers\UserController $this
 * @var \stdClass $user
 * @var \UsersForm $model
 */
?>

<td>
    <?php echo $user->id; ?>
</td>
<td>
    <a
        class="auto-login"
        data-text="Вы действительно хотите залогиниться под пользователем?"
        href="<?php echo Yii::app()->createUrl('account/loginbyid', ['id'=>$user->id]); ?>"
    >
        <?php echo $user->email; ?>
    </a>
</td>
<td><?php echo $user->full_name; ?></td>
<td>
    <?php if($user->sites_count): ?>
        <a href="<?php echo Yii::app()->createUrl('admin/stats/sites', ['userId'=>$user->id]); ?>"><?php echo $user->sites_count; ?></a>
    <?php else: ?>
        <?php echo $user->sites_count; ?>
    <?php endif; ?>
</td>
<td><?php echo $user->shows; ?></td>
<td><?php echo $user->clicks; ?></td>
<td><?php echo $user->ctr; ?></td>
<td><?php echo $user->costs; ?></td>
<td>
    <button
        data-url="<?=Yii::app()->createUrl('admin/user/index', ['id'=>$user->id]); ?>"
        class="btn btn-primary btn-xs tooltips auto-url"
        data-original-title="Редактировать профиль пользователя"
        data-toggle="tooltip"
        data-placement="top"
        title=""
    >
        <i class="fa fa-wrench"></i>
    </button>

    <button
        class="btn btn-primary btn-xs tooltips write-message"
        data-content="#ticket-form-container"
        data-title="Написать пользователю сообщение"
        data-okbutton="Отправить"
        data-nosubmit="nosubmit"
        data-user="<?php echo $user->id; ?>"
        data-original-title="Написать пользователю сообщение"
        data-toggle="tooltip"
        data-placement="top"
        title=""
    >
        <i class="fa fa-pencil"></i>
    </button>

    <?php if($model->getIsBanned($user->id)): ?>
        <button
            class="btn btn-success btn-xs tooltips auto-ajax"
            data-url="<?php echo Yii::app()->createUrl('admin/user/ban', ['id' => $user->id, 'status' => $model->status, 'activity' => $model->activity]); ?>"
            data-closest="tr"
            data-params="banUser=0"
            data-original-title="Разбанить пользователя"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-check"></i>
        </button>
    <?php else: ?>
        <button
            class="btn btn-danger btn-xs tooltips auto-ajax"
            data-url="<?php echo Yii::app()->createUrl('admin/user/ban', ['id' => $user->id, 'status' => $model->status, 'activity' => $model->activity]); ?>"
            data-closest="tr"
            data-params="banUser=1"
            data-original-title="Забанить пользователя"
            data-toggle="tooltip"
            data-placement="top"
            title=""
        >
            <i class="fa fa-times"></i>
        </button>
    <?php endif; ?>
</td>