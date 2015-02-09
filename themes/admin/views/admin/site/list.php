<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.09.14
 * Time: 16:40
 * @var \application\modules\admin\controllers\SiteController $this
 * @var \application\models\Sites[] $sites
 * @var \application\models\Sites $model
 */
?>

    <div class="row table-header">

        <div class="col-lg-6 left-float">
            <label>Показывать:</label>
            <select data-attribute="status" class="form-control auto-select" id="check-status-id">
                <?php foreach($model->getSelectorStatuses() as $stat): ?>
                    <option
                        value="<?php echo $stat->value; ?>"
                        <?php if($stat->checked) echo 'selected="selected"'; ?>
                    >
                        <?php echo $stat->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

    <div class="row">

        <table class="display table table-bordered table-striped datatable" id="sites-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Адрес сайта</th>
                    <th>ID пользователя</th>
                    <th>URl статискики</th>
                    <th>Login статистики</th>
                    <th>Pass статистики</th>
                    <th>Статус</th>
                    <th>Модерация</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($sites as $site): ?>
                    <tr>
                        <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.admin.site._siteTableRow', ['site' => $site, 'status' => $model->status]); ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>

<?php Yii::app()->clientScript->registerScript('sitesList', '
    Sites.init();
'); ?>