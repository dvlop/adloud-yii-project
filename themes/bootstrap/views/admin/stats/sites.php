<?php
/**
 * Created by PhpStorm.
 * User: M-A-X
 * Date: 10.07.14
 * Time: 13:50
 * @var AdminController $this
 * @var UsersForm $model
 */
?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/usersList.js', CClientScript::POS_END); ?>

    <div class="panel panel-blue margin-bottom-40">

        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon-user"></i>Сайты пользователя</h3>
        </div>

        <div class="panel-body">
            <?php if($model->sites): ?>
                <table class="table table-striped table-hover" id="users-list">

                    <thead>
                    <tr>
                        <th><a href='<?=$model->getSortLink('id')?>'><?=$model->getAttributeLabel('id'); ?></a></th>
                        <th><a href='<?=$model->getSortLink('url')?>'><?=$model->getAttributeLabel('url'); ?></a></th>
                        <th><a href='<?=$model->getSortLink('blocksCount')?>'><?=$model->getAttributeLabel('blocksCount'); ?></a></th>
                        <th><a href='<?=$model->getSortLink('shows')?>'><?=$model->getAttributeLabel('shows'); ?></a></th>
                        <th><a href='<?=$model->getSortLink('clicks')?>'><?=$model->getAttributeLabel('clicks'); ?></a></th>
                        <th><a href='<?=$model->getSortLink('ctr')?>'><?=$model->getAttributeLabel('ctr'); ?></a></th>
                        <th><a href='<?=$model->getSortLink('costs')?>'><?=$model->getAttributeLabel('costs'); ?></a></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach($model->sites as $site): ?>
                        <tr>
                            <td><?=$site->id; ?></td>
                            <td><?=$site->url; ?></td>

                            <td>
                                <?if($site->blocks_count):?>
                                    <a href="<?=Yii::app()->createUrl('admin/stats/blocks/', ['siteId'=>$site->id]); ?>"><?=$site->blocks_count; ?></a>
                                <?else:?>
                                    <?=$site->blocks_count?>
                                <?endif;?>
                            </td>

                            <th><?=$site->shows;?></th>
                            <th><?=$site->clicks;?></th>
                            <th><?=$site->ctr;?>%</th>
                            <th><?=$site->costs;?> $</th>
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
    Users.init({});
'); ?>