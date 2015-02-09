<?php
/**
 * @var ControllerBase $this
 * @var array $adsToModerate
 */
?>

<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Объявления, ожидающие модерацию</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover" id="List">
            <thead>
            <tr>
                <th>Изображение</th>
                <th>Название</th>
                <th>ссылка</th>
                <th>Стоимость клика</th>
                <th>Категории</th>
                <th>Товарный</th>
                <th>Адалт</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <?php if($adsToModerate):?>
                <?php foreach($adsToModerate AS $adv):?>
                    <tr>
                        <td>
                            <img src="<?php echo $adv['content']['imageUrl'];?>" width="75px" />
                        </td>
                        <td>
                            <?php echo $adv['content']['caption'];?>
                        </td>
                        <td>
                            <a href="<?php echo $adv['content']['url'];?>" target="_blank"><?php echo $adv['content']['url'];?></a>
                        </td>
                        <td>
                            <?php echo round($adv['clickPrice'], 2);?> $
                        </td>
                        <td>
                            <?php /* if(is_array($adv['categoriesText'])):?>
                                <?php foreach($adv['categoriesText'] AS $category):?>
                                    <span class="label label-default"><?php echo $category['description'];?></span>
                                <?php endforeach;?>
                            <?php endif; */?>
                        </td>
                        <td><input type="checkbox" class="shock-checkbox"></td>
                        <td><input type="checkbox" class="adult-checkbox"></td>
                        <td>
                            <button
                                data-toggle="modal"
                                data-target="#modalModerate"
                                data-url="<?php echo Yii::app()->createUrl('admin/ads/index', array('id'=>$adv['id'], 'state'=>'true'));?>"
                                data-tooltip="tooltip" data-placement="top" title="Подтвердить"
                                class="btn btn-success btn-xs btnModerateAccept"
                                ><i class="icon-ok"></i></button>
                            <button
                                data-toggle="modal"
                                data-target="#modalModerateDecline"
                                data-url="<?php echo Yii::app()->createUrl('admin/ads/index', array('id'=>$adv['id'], 'state'=>'false'));?>"
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
        <?php $this->widget('themes.bootstrap.widgets.LinkPager', array('pages'=>$pages)); ?>
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
                Вы действительно желаете подтвердить модерацию этого обьявления?
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
                Вы действительно желаете отменить модерацию этого обьявления?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="declineModerateButton">Подтверждаю отмену</a>
            </div>
        </div>
    </div>
</div>
