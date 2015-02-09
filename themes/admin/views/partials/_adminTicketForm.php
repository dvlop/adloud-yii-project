<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 25.09.14
 * Time: 10:28
 * Time: 18:16 *
 * @var \application\components\ControllerAdmin $this
 * @var \application\models\TicketCategory[] $categories
 */
?>


<?php
    if(!isset($action)) $action = Yii::app()->createUrl('ticket/admin/touser');
    if(!isset($userId)) $userId = '';
?>

<form class="admin-ticket-form" role="form" action="<?php echo $action; ?>" method="POST">

    <div class="error-group"></div>

    <div class="form-group">
        <div class="row">

            <div class="col-sm-6">
                <?php foreach($categories as $num => $cat): ?>
                    <div class="radio">
                        <label>
                            <input type="radio" checked="<?php if($num == 0) echo 'true'; ?>" value="<?php echo $cat->id; ?>" name="ticket[category]">
                            <?php echo $cat->name; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-sm-6">

                <div class="form-group">
                    <input
                        class="form-control flat"
                        type="text"
                        placeholder="Заголовок"
                        required="true"
                        name="ticket[name]" value=""
                    />
                </div>

                <div class="form-group">
                    <textarea
                        name="ticket[text]"
                        placeholder="Текст сообщения"
                        class="form-control flat user-notify-text"
                        required="true"
                    ></textarea>
                </div>

            </div>

        </div>

    </div>

    <input type="hidden" name="ticket[userId]" value="<?php echo $userId; ?>" />

    <?php if(isset($redirectUrl)): ?>
        <input type="hidden" name="ticket[redirectUrl]" value="<?php echo $redirectUrl; ?>" />
    <?php endif; ?>

    <input type="submit" class="hide" />

</form>