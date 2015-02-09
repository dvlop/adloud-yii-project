<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.08.14
 * Time: 13:36
 * To change this template use File | Settings | File Templates.
 */

?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/userAgentAdmin.js', CClientScript::POS_END); ?>

    <div class="col-lg-12 margin-bottom-40">

        <div class="col-lg-2">Показывать:</div>

        <div class="col-lg-3">
            <select id="check-filter-id">
                <option data-text="device" value="device">Устройства</option>
                <option data-text="browser" value="browser">Браузеры</option>
                <option data-text="os" value="os">ОС</option>
            </select>
        </div>

    </div>

    <div class="panel panel-blue margin-bottom-40">

        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon-user"></i>UA-параметры</h3>
        </div>

        <div class="panel-body">

            <table class="table table-striped table-hover" id="prepayment-requests-list">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Версия</th>
                    <?=$type == 'device' ? '<th>Разрешение</th>' : ''?>
                    <th>Просмотры</th>
                    <th>Модерация</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($uaList as $ua): ?>

                    <tr>
                        <td><?=$ua->id?></td>
                        <td><?=$ua->name?></td>
                        <td><?=$ua->value?></td>
                        <?=$type == 'device' ? '<td>'.$ua->resolution.'</td>' : ''?>
                        <td><?=$ua->shows?></td>
                        <?php if(!$ua->is_checked): ?>
                            <td>
                                <button
                                    data-toggle="modal"
                                    data-url="<?php echo Yii::app()->createAbsoluteUrl('/admin/userAgent/allow', ['id'=>$ua->id]); ?>"
                                    data-tooltip="tooltip" data-placement="top" title="Разрешить"
                                    class="btn btn-success btn-xs btnModerateAccept allow-ua"><i class="icon-ok"></i>
                                </button>
                            </td>
                        <?php else: ?>
                            <td>
                                <button
                                    data-toggle="modal"
                                    data-url="<?php echo Yii::app()->createAbsoluteUrl('/admin/userAgent/ban', ['id'=>$ua->id]); ?>"
                                    data-tooltip="tooltip" data-placement="top" title="Запретить"
                                    class="btn btn-danger btn-xs btnModerateAccept ban-ua"><i class="icon-remove"></i>
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>

                <?php endforeach; ?>
                </tbody>

            </table>

        </div>

    </div>

<?php
Yii::app()->clientScript->registerScript('userAgentAdmin', '
    UserAgentAdmin.init({
        getShow: "'.$type.'",
        pageBaseUrl: "/'.Yii::app()->controller->route.'/",
    });
');
?>