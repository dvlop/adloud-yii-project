<?php
    $_GET['direction'] = empty($_GET['direction']) || $_GET['direction'] == 'ASC' ? 'DESC' : 'ASC' ;
?>
<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Параметры</h3>
    </div>
    <div class="panel-body">
        <form method="get" action="<?=Yii::app()->createUrl('admin/stats/transactions')?>">
            <input type="text" name="startDate" value="<?=!empty($_GET['startDate']) ? $_GET['startDate'] : ''?>" placeholder="Start date (2014-08-19)">
            <input type="text" name="endDate" value="<?=!empty($_GET['endDate']) ? $_GET['endDate'] : ''?>" placeholder="End date (2014-08-19)">
            <input type="text" name="siteId" value="<?=!empty($_GET['siteId']) ? $_GET['siteId'] : ''?>" placeholder="Сайт">
            <input type="text" name="blockId" value="<?=!empty($_GET['blockId']) ? $_GET['blockId'] : ''?>" placeholder="Блок">
            <input type="text" name="adsId" value="<?=!empty($_GET['adsId']) ? $_GET['adsId'] : ''?>" placeholder="Рекламное сообщение">
            <input type="text" name="recipientId" value="<?=!empty($_GET['recipientId']) ? $_GET['recipientId'] : ''?>" placeholder="Вебмастер">
            <input type="text" name="senderId" value="<?=!empty($_GET['senderId']) ? $_GET['senderId'] : ''?>" placeholder="Рекламодатель">
            <input type="submit" value="Применить">
        </form>
    </div>

</div>
<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Транзакции</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover" id="List">
            <thead>
            <tr>
                <th>
                   <a href="<?php $_GET['orderBy'] = 'timestamp'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                      Дата и время
                   </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'ip'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        IP
                    </a>
                </th>

                <th>
                    <a href="<?php $_GET['orderBy'] = 'referer'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Реферер
                    </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'timestamp'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Ads ID
                    </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'block_id'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Блок ID
                    </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'amount'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Сумма
                    </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'recipient_id'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Веб. ID
                    </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'sender_id'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Рек. ID
                    </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'recipient_balance'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Баланс рек.
                    </a>
                </th>
                <th>
                    <a href="<?php $_GET['orderBy'] = 'sender_balance'; echo Yii::app()->createUrl('admin/stats/transactions', $_GET)?>">
                        Баланс веб.
                    </a>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if($stats):?>
                <?php foreach($stats AS $stat):?>
                    <tr>
                        <td>
                            <?=$stat['timestamp']?>
                        </td>
                        <td>
                            <?=$stat['ip']?>
                        </td>
                        <td style="max-width: 100px; overflow: hidden">
                            <?=$stat['referer']?>
                        </td>
                        <td>
                            <?=$stat['ads_id']?>
                        </td>
                        <td>
                            <?=$stat['block_id']?>
                        </td>
                        <td>
                            <?=$stat['amount']?>
                        </td>
                        <td>
                            <?=$stat['recipient_id']?>
                        </td>
                        <td>
                            <?=$stat['sender_id']?>
                        </td>
                        <td>
                            <?=$stat['sender_balance']?>
                        </td>
                        <td>
                            <?=$stat['recipient_balance']?>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php else: ?>
                <tr>
                    <td colspan="11">
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
