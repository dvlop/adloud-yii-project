<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 07.05.14
 * Time: 12:25
 * @var \application\models\Sites $model
 * @var \application\modules\webmaster\controllers\SiteController $this
 */
?>

<?php $class = str_replace('\\', '_', get_class($model)); ?>

<?php echo CHtml::beginForm('', 'post', ['id' => 'add_site', 'class' => 'col-sm-6']); ?>

    <div class="form-group">
        <?php echo CHtml::activeLabel($model, 'url', ['class'=>'adloud_label']); ?>
        <?php echo CHtml::activeUrlField($model, 'url', ['class' => 'form-control flat', 'required' => 'true']); ?>
        <!--<p class="add_site_mirror"><label class="adloud_label">Добавить зеркало вашего сайта</label></p>-->
    </div>

    <div class="form-group site_mirror">
        <?php echo CHtml::activeLabel($model, 'mirror', ['class'=>'adloud_label']); ?>
        <?php echo CHtml::activeUrlField($model, 'mirror', ['class' => 'form-control flat']); ?>
    </div>

    <div class="form-group add_site_cat">
        <?php echo CHtml::activeLabel($model, 'categoryLabel', ['class'=>'adloud_label']); ?>
        <select name="site-category">
            <?php foreach($model->categories as $cat): ?>
                <option value="<?php echo $cat->id; ?>"<?php if($cat->isChecked) echo 'checked="checked"'; ?>><?php echo Yii::t('webmaster_site',$cat->name); ?></option>
            <?php endforeach; ?>
        </select>
        <?php if($model->additionalCatName): ?>
            <?php echo CHtml::activeHiddenField($model, 'additionalCategory'); ?>
            <p id="additional-cat-name" class="add_more_cat" style="font-size: 11px;"><?=Yii::t('webmaster_site','Дополнительная категория:');?> <?php echo Yii::t('webmaster_site',$model->additionalCatName); ?></p>
        <?php endif; ?>
        <p id="add-category" class="add_more_cat">
            <label class="adloud_label"><?php echo $model->additionalCatName ? Yii::t('webmaster_site', 'Изменить') : Yii::t('webmaster_site', 'Указать дополнительную категорию'); ?></label>
            <label class="adloud_label" style="display: none;"><?=Yii::t('webmaster_site', 'Убрать')?></label>
        </p>
    </div>

    <div class="form-group add_site_cat">
        <?php echo CHtml::activeLabel($model, 'additionalCategory', ['class'=>'adloud_label']); ?>
        <select name="site-additional-category">
            <?php foreach($model->additionalCats as $cat): ?>
                <option value="<?php echo $cat->id; ?>"<?php if($cat->isChecked) echo 'checked="checked"'; ?>><?php echo Yii::t('webmaster_site',$cat->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group add_site_cat">
        <?php echo CHtml::activeLabel($model, 'bannedCategories', array('class'=>'adloud_label')); ?>
        <select class="remove_site_cat" name="ban_site_category" multiple="multiple">
            <?php foreach($model->bannedCats as $cat): ?>
                <option value="<?php echo $cat->id; ?>"<?php if($cat->isChecked) echo 'checked="checked"'; ?>><?php echo Yii::t('webmaster_site',$cat->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabel($model, 'statsUrl', ['class'=>'adloud_label']); ?>
        <?php echo CHtml::activeUrlField($model, 'statsUrl', ['class' => 'form-control flat', 'required' => 'true']); ?>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabel($model, 'statsLogin', ['class'=>'adloud_label']); ?>
        <?php echo CHtml::activeTextField($model, 'statsLogin', ['class' => 'form-control flat', 'required' => 'true']); ?>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabel($model, 'statsPassword', ['class'=>'adloud_label']); ?>
        <?php echo CHtml::activeTextField($model, 'statsPassword', ['class' => 'form-control flat', 'required' => 'true']); ?>
    </div>

    <div class="form-group add_site_check">
        <label class="checkbox" for="<?php echo $class; ?>_containsAdult">
            <input
                type="checkbox"
                <?php if($model->containsAdult) echo 'checked="checked"'; ?>
                value="<?php echo (int)$model->containsAdult; ?>"
                id="<?php echo $class; ?>_containsAdult"
                name="<?php echo $class; ?>[containsAdult]"
                data-toggle="checkbox"
                class="adloud_checkbox"
            />
            <?php echo $model->getAttributeLabel('containsAdult'); ?>
        </label>
        <label class="checkbox" for="<?php echo $class; ?>_allowAdult">
            <input
                type="checkbox"
                <?php if($model->allowAdult) echo 'checked="checked"'; ?>
                value="<?php echo (int)$model->allowAdult; ?>"
                id="<?php echo $class; ?>_allowAdult"
                name="<?php echo $class; ?>[allowAdult]"
                data-toggle="checkbox"
                class="adloud_checkbox"
            />
            <?php echo $model->getAttributeLabel('allowAdult'); ?>
        </label>
        <label class="checkbox" for="<?php echo $class; ?>_allowShock">
            <input
                type="checkbox"
                <?php if($model->allowShock) echo 'checked="checked"'; ?>
                value="<?php echo (int)$model->allowShock; ?>"
                id="<?php echo $class; ?>_allowShock"
                name="<?php echo $class; ?>[allowShock]"
                data-toggle="checkbox"
                class="adloud_checkbox"
            />
            <?php echo $model->getAttributeLabel('allowShock'); ?>
        </label>
        <label class="checkbox" for="<?php echo $class; ?>_allowSms">
            <input
                type="checkbox"
                <?php if($model->allowSms) echo 'checked="checked"'; ?>
                value="<?php echo (int)$model->allowSms; ?>"
                id="<?php echo $class; ?>_allowSms"
                name="<?php echo $class; ?>[allowSms]"
                data-toggle="checkbox"
                class="adloud_checkbox"
            />
            <?php echo $model->getAttributeLabel('allowSms'); ?>
        </label>
        <label class="checkbox" for="<?php echo $class; ?>_allowAnimation">
            <input
                type="checkbox"
                <?php if($model->allowAnimation) echo 'checked="checked"'; ?>
                value="<?php echo (int)$model->allowAnimation; ?>"
                id="<?php echo $class; ?>_allowAnimation"
                name="<?php echo $class; ?>[allowAnimation]"
                data-toggle="checkbox"
                class="adloud_checkbox"
            />
            <?php echo $model->getAttributeLabel('allowAnimation'); ?>
        </label>
    </div>

    <div class="form-group">
        <button class="btn btn-embossed adloud_btn col-md-6 col-sm-8" type="submit">
            <span class="input-icon fui-plus pull-left"></span><?php echo $model->id ? Yii::t('webmaster_site', 'Изменить площадку') : Yii::t('webmaster_site', 'Добавить площадку'); ?>
        </button>
    </div>

<?php echo CHtml::endForm(); ?>

<div class="col-sm-6">
    <div class="add_site_recommendations">
        <p class="recommendations_title"><?=Yii::t('webmaster_site', 'Требования к площадкам')?></p>
        <p><?=Yii::t('webmaster_site', 'На время бета-тестирования площадки проходят ручную модерацию. В этот период к сайтам выдвигаются минимальные требования. К участию принимаются веб-ресурсы, которые:')?></p>
        <ul>
            <li><?=Yii::t('webmaster_site', 'имеют от 1000 уникальных посещений в сутки;')?></li>
            <li><?=Yii::t('webmaster_site', 'не используют какие-либо механизмы накрутки и принуждения пользователей к переходам по рекламе;')?></li>
            <li><?=Yii::t('webmaster_site', 'не содержат вредоносных программ;')?></li>
            <li><?=Yii::t('webmaster_site', 'имеют внешний счетчик посещаемости или подключены к системе аналитики.')?></li>
        </ul>
        <p><?=Yii::t('webmaster_site', 'Запрещается:')?></p>
        <ul>
            <li><?=Yii::t('webmaster_site', 'размещение надписей, побуждающих посетителей перейти по объявлению, и искусственное привлечение внимания к рекламному блоку;')?></li>
            <li><?=Yii::t('webmaster_site', 'работа в системе AdLoud.net с более чем одного аккаунта: подключение дополнительного аккаунта проводится только с разрешения администрации;')?></li>
            <li><?=Yii::t('webmaster_site', 'размещение рекламных блоков на пустующих страницах сайта без статистики;')?></li>
        </ul>
        <p><?=Yii::t('webmaster_site', 'Администрация оставляет за собой право прекратить сотрудничество, если качество получаемого трафика окажется не удовлетворительным.')?></p>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('addSite', '
    Site.init({
        showAllText: "'.Yii::t('webmaster_site','Показывать все объявления').'",
        nonSelText: "'.Yii::t('webmaster_site','Ничего не выбрано').'",
    });
'); ?>