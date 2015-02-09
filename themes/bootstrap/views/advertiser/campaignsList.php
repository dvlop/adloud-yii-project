<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/5/14
 * Time: 11:38 PM
 */
?>

<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-tasks"></i> Информация о всех ваших рекламных кампаниях</h3>
    </div>
    <div class="panel-body">
        <h4>В таблице ниже вы можете просмотреть список всех ваших рекламных кампаний, а так же управлять ими и смотреть статистику по каждой</h4>

        <table class="table table-striped table-hover" id="List">
            <thead>
            <tr>
                <th></th>
                <th>Название</th>
                <th>Лимит</th>
                <th>Дневной лимит</th>
                <th>Показы</th>
                <th>Клики</th>
                <th>CTR</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <?php if($campaignsList):?>
                <?php foreach($campaignsList AS $campaign):?>
                    <tr id="<?php echo 'campaignId' . $campaign['id'];?>"
                        class="elExpand <?php echo !empty($createdCampaign) && $createdCampaign == $campaign['id'] ? 'active' : '';?>"
                        data-tooltip="tooltip" data-placement="top" title="Нажмите для просмотра списка обьявлений"
                        data-content-url="<?php echo Yii::app()->createUrl('advertiser/ads/list', array('campaignId' => $campaign['id']));?>"
                    >
                        <td>
                            <i class="icon-folder-close-alt iconExpand"></i>
                        </td>
                        <td><?php echo $campaign['description']; ?></td>
                        <td><?php echo $campaign['limit'] ? $campaign['limit'] . ' $' : 'не установлен'; ?></td>
                        <td><?php echo $campaign['dailyLimit'] ? $campaign['dailyLimit'] . ' $' : 'не установлен'; ?></td>
                        <td><?php echo $campaign['shows'];?>  </td>
                        <td><?php echo $campaign['clicks'];?></td>
                        <td><?php echo $campaign['shows'] > 0 ? round($campaign['clicks'] / $campaign['shows'], 3) * 100 : 0; ?></td>
                        <td>
                            <a
                                href="<?php echo Yii::app()->createUrl("advertiser/campaign", array('id'=>$campaign['id']));?>"
                                data-tooltip="tooltip" data-placement="top" title="Редактировать кампанию"
                                class="btn btn-default btn-xs">
                                <i class="icon-edit"></i>
                            </a>
                            <button
                                data-toggle="modal"
                                data-target="#modalCampaignPublish"
                                data-url="<?php echo Yii::app()->createUrl("advertiser/campaignPublish", array('id'=>$campaign['id']));?>"
                                data-tooltip="tooltip" data-placement="top" title="Опубликовать кампанию"
                                class="btn btn-success btn-xs btnCampaignPublish">
                                <i class="icon-play"></i>
                            </button>
                            <button
                                data-toggle="modal"
                                data-target="#modalCampaignUnPublish"
                                data-url="<?php echo Yii::app()->createUrl("advertiser/campaignUnPublish", array('id'=>$campaign['id']));?>"
                                data-tooltip="tooltip" data-placement="top" title="Отменить публикацию кампании"
                                class="btn btn-warning btn-xs btnCampaignUnPublish">
                                <i class="icon-pause"></i>
                            </button>
                            <button
                                data-toggle="modal"
                                data-target="#modalCampaignDelete"
                                data-url="<?php echo Yii::app()->createUrl("advertiser/campaign/delete", array('id'=>$campaign['id']));?>"
                                data-tooltip="tooltip" data-placement="top" title="Удалить рекламную кампанию"
                                class="btn btn-danger btn-xs btnCampaignDelete">
                                <i class="icon-remove"></i>
                            </button>
                        </td>
                    </tr>

                    <tr style="display: none;">
                        <td colspan="8">

                            <div class="tag-box tag-box-v2">

                                <div class="progress progress-striped active">
                                    <div style="width: 100%" class="progress-bar progress-bar-info">
                                    </div>
                                </div>
                                <div class="ajaxContent"></div>

                                <br>
                                <a href="<?php echo Yii::app()->createUrl('advertiser/ads/index', array('campaignId' => $campaign['id']));?>"
                                   class="btn-u btn-u-blue btn-u-large btn-block text-center">
                                    Добавить обьявление
                                </a>

                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                <tr>
                    <td colspan="8">
                        <div class="alert alert-danger" style="text-align: center;">
                            Список рекламных кампаний пуст. Но вы можете создать новую рекламную кампанию, нажав на кнопку ниже.
                        </div>
                    </td>
                </tr>
            <?php endif;?>
            </tbody>
        </table>

        <hr>

        <a href="<?php echo Yii::app()->createUrl('advertiser/campaign/index');?>" class="btn-u btn-u-blue btn-u-large btn-block text-center">Добавить еще одну рекламную кампанию</a>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalCampaignDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Удаление записи</h4>
            </div>
            <div class="modal-body">
                Вы действительно желаете удалить эту рекламную кампанию? Данное действие будет невозможно возобновить!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="confirmCampaignDelete">Подтверждаю удаление</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAdsDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Удаление записи</h4>
            </div>
            <div class="modal-body">
                Вы действительно желаете удалить это рекламное обьявление? Данное действие будет невозможно возобновить!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="confirmAdsDelete">Подтверждаю удаление</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCampaignPublish" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Запуск рекламной кампании</h4>
            </div>
            <div class="modal-body">
                Данное действие запускает рекламную кампанию (все рекламные обьявления в кампании). Подтвердите свое решение.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="confirmCampaignPublish">Подтверждаю запуск</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCampaignUnPublish" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Приостановка рекламной кампании</h4>
            </div>
            <div class="modal-body">
                Данное действие приостанавливает рекламную кампанию (все рекламные обьявления в кампании). Подтвердите свое решение.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="confirmCampaignUnPublish">Подтверждаю приостановку</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAdsPublish" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Запуск рекламного обьявления</h4>
            </div>
            <div class="modal-body">
                Данное действие запускает рекламное обьявление. Подтвердите свое решение.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="confirmAdsPublish">Подтверждаю запуск</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAdsUnPublish" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Приостановка рекламного обьявления</h4>
            </div>
            <div class="modal-body">
                Данное действие приостанавливает рекламное обьявление. Подтвердите свое решение.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="confirmAdsUnPublish">Подтверждаю приостановку</a>
            </div>
        </div>
    </div>
</div>

