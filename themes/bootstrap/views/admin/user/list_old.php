<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.08.14
 * Time: 11:10
 * @var \application\modules\admin\controllers\UserController $this
 * @var \UsersForm $model
 */
?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/usersList.js', CClientScript::POS_END); ?>
<div class="col-lg-12 margin-bottom-40">

    <div class="col-lg-1">Статус:</div>

    <div class="col-lg-2">
        <select id="check-ban-id">
            <option data-ban="normal" value="normal">Нормальные</option>
            <option data-ban="banned" value="banned">Забаненные</option>
        </select>
    </div>

    <div class="col-lg-1">Активность:</div>

    <div class="col-lg-2">
        <select id="check-activity-id">
            <option data-active="active" value="active">Активные</option>
            <option data-active="passive" value="passive">Пассивные</option>
        </select>
    </div>

</div>


<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-user"></i> Пользователи системы</h3>
    </div>

    <div class="panel-body">
        <?php if($model->users): ?>
        <table class="table table-striped table-hover" id="users-list">

            <thead>
            <tr>
                <th><a href='<?=$model->getSortLink('id')?>'><?=$model->getAttributeLabel('id'); ?></a></th>
                <th><a href='<?=$model->getSortLink('email')?>'><?=$model->getAttributeLabel('email'); ?></a></th>
                <th><?=$model->getAttributeLabel('fullName'); ?></th>
                <th><a href='<?=$model->getSortLink('sitesCount')?>'><?=$model->getAttributeLabel('sitesCount'); ?></a></th>
                <th><a href='<?=$model->getSortLink('shows')?>'><?=$model->getAttributeLabel('shows'); ?></a></th>
                <th><a href='<?=$model->getSortLink('clicks')?>'><?=$model->getAttributeLabel('clicks'); ?></a></th>
                <th><a href='<?=$model->getSortLink('ctr')?>'><?=$model->getAttributeLabel('ctr'); ?></a></th>
                <th><a href='<?=$model->getSortLink('costs')?>'><?=$model->getAttributeLabel('costs'); ?></a></th>
                <th>Модерация</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model->users as $user): ?>
            <tr>
                <td>
                <td><a onclick="return confirm('Вы действительно хотите залогиниться под пользователем?');" href="<?=Yii::app()->createUrl('account/loginbyid', ['id'=>$user->id]); ?>"><?=$user->id; ?></a></td>
                <td><a onclick="return confirm('Вы действительно хотите залогиниться под пользователем?');" href="<?=Yii::app()->createUrl('account/loginbyid', ['id'=>$user->id]); ?>"><?=$user->email; ?></a></td>

                <td><?=$user->full_name; ?></td>

                <td>
                    <?if($user->sites_count):?>
                            <a href="<?=Yii::app()->createUrl('admin/stats/sites/', ['userId'=>$user->id]); ?>"><?=$user->sites_count; ?></a>
                    <?else:?>
                    <?=$user->sites_count?>
                    <?endif;?>
                </td>

                <th><?=$user->shows;?></th>
                <th><?=$user->clicks;?></th>
                <th><?=$user->ctr;?>%</th>
                <th><?=$user->costs;?> $</th>

            <td>
                <button
                    data-toggle="modal"
                    data-url="<?=Yii::app()->createUrl('admin/user/index', ['id'=>$user->id]); ?>"
                    data-tooltip="tooltip" data-placement="top" title="Модерировать"
                    class="btn btn-success btn-xs btnModerateAccept user-moderate"
                ><i class="icon-pencil"></i></button>

                <?php if($model->getIsBanned($user->id)): ?>
                    <button
                        data-toggle="modal"
                        data-url="<?=Yii::app()->createUrl('admin/user/ban', ['id'=>$user->id]); ?>"
                        data-tooltip="tooltip" data-placement="top" title="Разбанить пользователя"
                        class="btn btn-success btn-xs btnModerateAccept user-disban"
                        ><i class="icon-ok"></i></button>
                <?php else: ?>
                    <button
                        data-toggle="modal"
                        data-url="<?=Yii::app()->createUrl('admin/user/ban', ['id'=>$user->id]); ?>"
                        data-tooltip="tooltip" data-placement="top" title="Забанить пользователя"
                        class="btn btn-danger btn-xs btnModerateDecline user-ban"
                        ><i class="icon-remove"></i></button>
                <?php endif; ?>

            </td>
            </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
        <?php else: ?>
            Список польователей пуст
        <?php endif; ?>
    </div>

</div>
<?php Yii::app()->clientScript->registerScript('usersList', '
    Users.init({
        getBan: "'.$ban.'",
        getActive: "'.$active.'",
        pageBaseUrl: "/'.Yii::app()->controller->route.'/",
    });
'); ?>


