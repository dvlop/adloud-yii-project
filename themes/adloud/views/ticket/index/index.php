<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 30.07.14
 * Time: 17:39
 * To change this template use File | Settings | File Templates.
 */
?>
<main class="new-ticket clearfix" role="main">
    <h1 class="col-sm-12"><?=Yii::app()->controller->action->id == 'index' ? 'Тикет' : 'Тикет от лица Админа'?></h1>
    <div class="ticket-msg-container">
        <div class="ticket-msg-list col-sm-12">
            <?php foreach($messages as $mes):?>
                <?php if(!$mes->is_admin):?>
                    <article class="col-sm-9 col-sm-offset-3 ticket-msg-item user-msg">
                        <div class="ticket-msg-item-content col-sm-10">
                            <pre><?=$mes->content?></pre>
                        </div>
                        <div class="col-sm-2 ticket-msg-avatar-container">
                            <div class="ticket-msg-avatar">
                                <img src="<?=$avatar?>">
                            </div>
                        </div>
                        <footer class="ticket-msg-item-footer">
                            <time class="ticket-msg-item-pubdate text-right col-sm-10" pubdate datetime="2014-03-20T15:32"><span class="fui-time"></span> <?=$mes->date?></time>
                        </footer>
                    </article>
                <?php else:?>
                    <article class="ticket-msg-item col-sm-9 moder-msg">
                        <div class="col-sm-2 ticket-msg-avatar-container text-right">
                            <div class="ticket-msg-avatar">
                                <img src="<?=$theme?>/assets/images/adloud/logo.png">
                            </div>
                        </div>
                        <div class="ticket-msg-item-content col-sm-10 col-sm-offset-2">
                            <pre><?=$mes->content?></pre>
                        </div>
                        <footer class="ticket-msg-item-footer">
                            <time class="ticket-msg-item-pubdate col-sm-10 col-sm-offset-2" pubdate datetime="2014-03-20T15:32"><span class="fui-time"></span> <?=$mes->date?></time>
                        </footer>
                    </article>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    </div>

    <form class="add-new-ticket col-sm-12" id="" method="post" action="">
        <div class="col-sm-10">
            <div class="row add-new-ticket-text-wrapper">
                <div id="add-new-ticket-text" class="add-new-ticket-text form-control" contenteditable="true" draggable="false">
                </div>
            </div>
        </div>
        <div class="col-sm-2 text-right">
            <div class="row">
                <div class="fileinput fileinput-new add-ticket-img" data-provides="fileinput">
                <span class="btn btn-primary btn-file">
                  <span class="fileinput-new"><span>Добавить<br> изображение</span><img src="<?=Yii::app()->theme->baseUrl?>/assets/images/adloud/download-icon.png"></span>
                  <span class="fileinput-exists"><span class="fui-gear"></span> Изменить </span>
                  <input id="ticket-msg-file-uploader" data-url="<?php echo Yii::app()->createUrl('ticket/index/saveImage'); ?>" type="file" accept="image/x-png, image/gif, image/jpeg">
                </span>
                    <span class="fileinput-filename"></span>
                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                </div>
                <?php if(Yii::app()->user->isAdmin): ?>
                    <button
                        type="submit"
                        class="btn adloud_btn btn-embossed new-ticket-submit"
                        id="send_msg" data-ticket="<?php echo $ticket->id; ?>"
                        data-url="<?php echo Yii::app()->createUrl('ticket/index/adminAnswer') ?>"
                    >Отправить</button>
                <?php else:?>
                    <button
                        type="submit"
                        class="btn adloud_btn btn-embossed new-ticket-submit"
                        id="send_msg"
                        data-ticket="<?php echo $ticket->id; ?>"
                        data-url="<?php echo Yii::app()->createUrl('ticket/index/answer'); ?>"
                    >Отправить</button>
                <?php endif;?>
            </div>
        </div>
    </form>

</main>
<div class="zoom-bg hidden"></div>

<?php Yii::app()->clientScript->registerScript('ticket', '
    Ticket.init({});
'); ?>