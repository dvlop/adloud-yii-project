<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 30.07.14
 * Time: 12:34
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="col-sm-4 add_ticket_btn">
    <button class="btn btn-block adloud_btn btn-embossed">
        <span class="fui-plus"></span>Добавить тикет
    </button>
</div>
<div class="col-sm-12">
    <form id="add_ticket" method="POST" class="col-sm-12">
        <div class="col-sm-5 select_section">
            <div class="col-sm-5">
                <label class="adloud_label adloud_section_title" for="ticket_section">
                    Раздел:
                </label>
            </div>
            <div class="col-sm-7">
                <?php foreach($categoryList as $category):?>
                    <label class="radio adloud_label">
                        <input type="radio" value="<?=$category['id']?>" class="gender_btn_on" data-toggle="radio" name="ticket_section" <?=$category['id'] == 1 ? 'checked' : '';?>/>
                        <?=trim($category['name'])?>
                    </label>
                <?php endforeach;?>
            </div>
        </div>
        <div class="col-sm-7 ticket_form">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label for="ticket_theme" class="adloud_label">Тема:</label>
                    <input type="text" value="" name="ticket_theme" class="form-control flat" required/>
                </div>
                <div class="form-group">
                    <label for="ticket_message" class="adloud_label">Сообщение:</label>
                    <textarea name="ticket_message" class="form-control flat" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="adloud_btn btn col-md-5 col-sm-7" id="send_ticket" data-url="<?php echo Yii::app()->createUrl('ticket/index/create'); ?>">
                        Отправить запрос
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="col-sm-12 all_tickets">
    <?php if($ticketList):?>
    <div class="container dialog-wrapper">
        <label class="col-md-1 col-sm-1 text-center">ID</label>
        <label class="col-md-3 col-sm-3 text-center">Дата и время</label>
        <label class="col-md-5 col-sm-5">Тема тикета</label>
        <label class="col-md-3 col-sm-3 text-center">Статус</label>
    </div>
        <?php foreach($ticketList as $ticket):?>
            <?php if($ticket->isNewMessage()):?>
                <div class="dialog new_ticket ticket_item">
                    <div class="container">
                        <div class="col-md-1 col-sm-1 text-center">
                            <span><?=$ticket->id?></span>
                        </div>
                        <div class="col-md-3 col-sm-3 ticket_time text-center">
                            <time datetime="<?=$ticket->date?>"><?=$ticket->date?></time>
                        </div>
                        <div class="col-md-5 col-sm-5 ticket_title">
                            <span class="fui-mail"></span><?=CHtml::link($ticket->name, '/ticket/'.$ticket->id)?>
                        </div>
                        <div class="col-md-3 col-sm-3 text-center">
                            <span><?=$ticket->status ? 'Открыт' : 'Закрыт' ?></span>
                        </div>
                    </div>
                </div>
            <?php else:?>
                <div class="dialog ticket_item">
                    <div class="container">
                        <div class="col-md-1 col-sm-1 text-center">
                            <span><?=$ticket->id?></span>
                        </div>
                        <div class="col-md-3 col-sm-3 ticket_time text-center">
                            <time datetime="<?=$ticket->date?>"><?=$ticket->date?></time>
                        </div>
                        <div class="col-md-5 col-sm-5 ticket_title">
                            <?=CHtml::link($ticket->name, '/ticket/'.$ticket->id)?>
                        </div>
                        <div class="col-md-3 col-sm-3 text-center">
                            <span><?=$ticket->status ? 'Открыт' : 'Закрыт' ?></span>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        <?php endforeach;?>
    <?php else:?>
        Вы еще не создавали тикетов в нашей системе
    <?php endif;?>
</div>

<?php Yii::app()->clientScript->registerScript('ticket', '
    Ticket.init({});
'); ?>