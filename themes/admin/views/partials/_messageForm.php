<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 18.09.14
 * Time: 16:41
 * Time: 18:16 * @var \application\components\ControllerAdmin $this
 * @var $userId
 */
?>

<?php
    if(!isset($action)) $action = Yii::app()->createUrl('admin/user/notify');
    if(!isset($userId)) $userId = '';
?>

<form class="ticket-form" role="form" action="<?php echo $action; ?>" method="POST">

    <div class="error-group"></div>

    <div class="form-group">
        <input
            type="textarea"
            name="notify[text]"
            placeholder="Текст сообщения"
            class="form-control user-notify-text"
            required="true"
            data-error="Укажите сообщение"
        />
    </div>

    <input type="hidden" name="notify[userId]" value="<?php echo $userId; ?>" />

</form>