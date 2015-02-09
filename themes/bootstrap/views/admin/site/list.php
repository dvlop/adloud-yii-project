<?php
/**
 * @var AdminController $this
 * @var array $sitesToModerate
 */

?>

<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Сайты, ожидающие модерацию</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover" id="List">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ссылка</th>
                <th>Кабинет статистики</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Категории</th>
                <th>Статус</th>
                <th>Модерация</th>
            </tr>
            </thead>
            <tbody>
            <?php if($sitesToModerate):?>
                <?php foreach($sitesToModerate AS $site):?>
                    <tr>
                        <td>
                            <?php echo $site->id; ?>
                        </td>
                        <td>
                            <a href="<?php echo $site->url;?>" target="_blank"><?php echo $site->url;?></a>
                        </td>
                        <td>
                            <a href="<?php echo $site->statsUrl;?>" target="_blank"><?php echo $site->statsUrl;?></a>
                        </td>
                        <td>
                            <?php echo $site->statsLogin; ?>
                        </td>
                        <td>
                            <?php echo $site->statsPassword; ?>
                        </td>
                        <td>
                            <?php echo $site->categories; ?>
                        </td>
                        <td>
                            <?php echo $site->statusName; ?>
                        </td>
                        <td>
                            <button
                                data-toggle="modal"
                                data-target="#modalModerate"
                                data-url="<?php echo Yii::app()->createUrl('admin/site/index', ['id' => $site->id, 'state' => 'true']);?>"
                                data-tooltip="tooltip" data-placement="top" title="Подтвердить"
                                class="btn btn-success btn-xs btnModerateAccept"
                                ><i class="icon-ok"></i></button>
                            <button
                                data-toggle="modal"
                                data-target="#modalModerateDecline"
                                data-url="<?php echo Yii::app()->createUrl('admin/site/index', ['id' => $site->id, 'state' => 'false']);?>"
                                data-tooltip="tooltip" data-placement="top" title="Отказать"
                                class="btn btn-danger btn-xs btnModerateDecline"
                                ><i class="icon-remove"></i></button>
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

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-8">
        <?php $this->widget('themes.'.Yii::app()->theme->name.'.widgets.LinkPager', ['pages'=>$pages]); ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalModerate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Подтверждение модерации</h4>
            </div>
            <div class="modal-body">
                Вы действительно желаете подтвердить модерацию этого сайта?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="confirmModerateButton">Подтверждаю модерацию</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalModerateDecline" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Подтверждение отказа</h4>
            </div>
            <div class="modal-body">
                Вы действительно желаете отменить модерацию этого сайта?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="declineModerateButton">Подтверждаю отмену</a>
            </div>
        </div>
    </div>
</div>
