<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 22.09.14
 * Time: 12:27
 * @var \application\modules\admin\controllers\AdsController $this
 * @var \application\models\Ads $model
 * @var \application\models\Ads[] $adsList
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
            <th>Изображение</th>
            <th>Название</th>
            <th>Ссылка</th>
            <th>Стоимость клика</th>
            <th>Категории</th>
            <th>Товарный</th>
            <th>Адалт</th>
            <th>Статус</th>
            <th>Модерация</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach($adsList as $ads): ?>
            <tr>
                <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.admin.ads._adsTableRow', ['ads' => $ads, 'status' => $model->status]); ?>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

</div>

<?php Yii::app()->clientScript->registerScript('adsListScript', '
    Teasers.init();
'); ?>