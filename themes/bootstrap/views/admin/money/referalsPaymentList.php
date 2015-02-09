<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 28.07.14
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */
?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/referalPaymentList.js', CClientScript::POS_END); ?>

<div class="col-lg-12 margin-bottom-40">

    <div class="col-lg-2">Показывать:</div>

    <div class="col-lg-3">
        <select id="check-filter-id">
            <option data-text="new" value="new">Новые</option>
            <option data-text="accepted" value="accepted">Принятые</option>
            <option data-text="denied" value="denied">Отклоненные</option>
        </select>
    </div>

</div>

<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-user"></i> Запросы на реферальные выплаты</h3>
    </div>

    <div class="panel-body">

        <table class="table table-striped table-hover" id="prepayment-requests-list">

            <thead>
            <tr>
                <th>ID</th>
                <th>ID Реферера</th>
                <th>ID Реферала</th>
                <th>Период запроса</th>
                <th>Сумма</th>
                <?php if($show == 'all'): ?>
                    <th>Модерация</th>
                <?php else: ?>
                    <th>Статус</th>
                <?php endif; ?>
            </tr>
            </thead>

            <tbody>
            <?php foreach($requestsList as $request): ?>

                <tr data-url="<?php echo Yii::app()->createUrl('admin/user/stats', ['id' => $request['id']]); ?>">
                    <td><?php echo $request['id']; ?></td>
                    <td><?php echo $request['referer_id']; ?></td>
                    <td><?php echo $request['referal_id']; ?></td>
                    <td><?php echo $request['start_date'] . ' - ' . $request['date']; ?></td>
                    <td><?php echo $request['sum'] .'$'; ?></td>
                    <?php if($show == 'all'): ?>
                        <td>
                            <?php if($request['showActivateLink']): ?>
                                <button
                                    data-toggle="modal"
                                    data-url="<?php echo Yii::app()->createAbsoluteUrl('/admin/money/moderateReferalRequest', ['id'=>$request['id']]); ?>"
                                    data-tooltip="tooltip" data-placement="top" title="Подтвердить"
                                    class="btn btn-success btn-xs btnModerateAccept payment-activate"
                                    ><i class="icon-ok"></i></button>
                            <?php endif; ?>
                            <?php if($request['showDeactivateLink']): ?>
                                <button
                                    data-toggle="modal"
                                    data-url="<?php echo Yii::app()->createAbsoluteUrl('/admin/money/moderateReferalRequest', ['id'=>$request['id']]); ?>"
                                    data-tooltip="tooltip" data-placement="top" title="Отклонить"
                                    class="btn btn-danger btn-xs btnModerateDecline payment-deactivate"
                                    ><i class="icon-remove"></i></button>
                            <?php endif; ?>
                        </td>
                    <?php else: ?>
                        <td><?php echo $request['moderation']; ?></td>
                    <?php endif; ?>
                </tr>

            <?php endforeach; ?>
            </tbody>

        </table>

    </div>

</div>

<?php
    Yii::app()->clientScript->registerScript('referalPaymentList', '
        ReferalPaymentList.init({
            filterPickerId: "#check-filter-id",
            pageBaseUrl: "/'.Yii::app()->controller->route.'/",
            getShow: "'.$show.'"
        });
    ');
?>