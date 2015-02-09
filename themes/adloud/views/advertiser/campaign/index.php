<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 05.05.14
 * Time: 17:27
 * @var \application\models\Campaign $model
 * @var \application\modules\advertiser\controllers\CampaignController $this
 */
?>

<?php echo CHtml::beginForm('', 'post', ['id' => 'create_campaign', 'class' => 'col-sm-12']); ?>

    <div class="row">
    <div class="col-sm-12 col-md-6">
    <div class="row">

    <div class="form-group col-sm-12">
        <label class="adloud_label"><?php echo Yii::t('campaign', 'Введите название кампании:'); ?></label>
        <?php echo CHtml::activeTextField($model, 'description', ['class' => 'form-control flat', 'required' => 'true']); ?>
        <p class="adloud_note"><?php echo Yii::t('main', 'Осталось символов'); ?>: <span class="title_adloud_note">30</span></p>
    </div>

    <div class="form-group col-sm-12">
        <label class="adloud_label"><?php echo Yii::t('campaign', 'Введите адрес рекламируемого сайта:'); ?></label>
        <?php echo CHtml::activeUrlField($model, 'siteUrl', ['class' => 'form-control flat', 'required' => 'true']); ?>
    </div>

    <div class="form-group col-sm-12">
        <label class="adloud_label"><?php echo Yii::t('campaign', 'Установите цену за переход:'); ?></label>
        <?php echo CHtml::activeTextField($model, 'clickPrice', ['class' => 'form-control flat', 'required' => 'true', 'pattern' => '^[-+]?[0-9]*\.?[0-9]+$']); ?>
    </div>

    <div class="form-group col-sm-12">
        <label class="adloud_label">
            <span class="open_section"><?php echo Yii::t('campaign', 'География'); ?> <b class="caret"></b></span>
        </label>

        <p class="adloud_note"><?php echo Yii::t('campaign', 'Выбор страны и города для показа объявления'); ?></p>

        <div class="col-sm-12 section geography">
            <div class="row">
                <p class="adloud_label">
                    <b class="open_region caret active"></b>
                    <label class="checkbox region_check" for="check_all_region">
                        <input type="checkbox" data-toggle="checkbox" name="check_all_region">
                    </label>
                    <span class="open_region active"><?php echo Yii::t('campaign', 'Все страны'); ?></span>
                </p>
                <ul class="region_list">
                    <?php $c = 0?>
                    <?php foreach($model->getCountries() as $country): ?>
                        <li>
                            <p class="adloud_label">
                                <b class="open_region caret active"></b>
                                <label class="checkbox region_check city_check country" for="check_ru">
                                    <input
                                        type="checkbox"
                                        name="<?php echo $model->getModelName().'[country][]'; ?>"
                                        value="<?php echo $country->id; ?>"
                                        <?php if($country->checked) echo 'checked="checked"'; ?>
                                        data-toggle="checkbox"
                                        class="city_check_box"
                                        />
                                </label>
                                <span class="open_region active"><?=$country->name?></span>
                            </p>
                            <ul class="region_list">
                                <?php foreach($model->getRegions($country->id) as $region): ?>
                                    <li>
                                        <p class="adloud_label">
                                            <b class="open_region caret passive"></b>
                                            <label class="checkbox region_check" for="check_ru_east">
                                                <input
                                                    type="checkbox"
                                                    name="<?php echo $model->getModelName().'[region][]'; ?>"
                                                    value="<?php echo $region->id; ?>"
                                                    <?php if($region->checked) echo 'checked="checked"'; ?>
                                                    data-toggle="checkbox"
                                                    class="city_check_box"
                                                    />
                                            </label>
                                            <span class="open_region passive"><?=$region->name?></span>
                                        </p>
                                    </li>
                                <?php endforeach?>
                            </ul>
                        </li>
                    <?php endforeach?>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label class="adloud_label">
            <span class="open_section"><?php echo Yii::t('campaign', 'Устройства'); ?> <b class="caret"></b></span>
        </label>

        <p class="adloud_note"><?php echo Yii::t('campaign', 'Выбор устройств для показа объявления'); ?></p>

        <div class="col-sm-12 section device">
            <div class="row">
                <ul class="first_list">
                    <?php foreach($model->getDevices() as $device): ?>
                        <li>
                            <p class="adloud_label">
                                <b class="open_region caret active"></b>
                                <label class="checkbox region_check city_check device" data-ua="<?php echo $device->id; ?>" for="check_ru">
                                    <input
                                        type="checkbox"
                                        name="<?php echo $model->getModelName().'[device][]'; ?>"
                                        value="<?php echo $device->name; ?>"
                                        <?php if($device->checked) echo 'checked="checked"'; ?>
                                        data-toggle="checkbox"
                                        class="city_check_box"
                                        />
                                </label>
                                <span class="open_region active"><?=$device->name?></span>
                            </p>
                            <ul class="region_list">
                                <?php foreach($model->getDevicesModel($device->name) as $device_model): ?>
                                    <li>
                                        <p class="adloud_label">
                                            <b class="open_region caret passive"></b>
                                            <label class="checkbox region_check deviceModel" data-ua="<?php echo $device_model->id; ?>" for="check_ru_east">
                                                <input
                                                    type="checkbox"
                                                    name="<?php echo $model->getModelName().'[deviceModel][]'; ?>"
                                                    value="<?php echo $device_model->id; ?>"
                                                    <?php if($device_model->checked) echo 'checked="checked"'; ?>
                                                    data-toggle="checkbox"
                                                    class="city_check_box"
                                                    />
                                            </label>
                                            <span class="open_region passive"><?=$device_model->value?></span>
                                        </p>
                                    </li>
                                <?php endforeach?>
                            </ul>
                        </li>
                    <?php endforeach?>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label class="adloud_label">
            <span class="open_section"><?php echo Yii::t('campaign', 'Браузеры'); ?> <b class="caret"></b></span>
        </label>

        <p class="adloud_note"><?php echo Yii::t('campaign', 'Выбор браузера для показа объявления'); ?></p>

        <div class="col-sm-12 section device">
            <div class="row">
                <ul class="first_list">
                    <?php foreach($model->getBrowsers() as $browser): ?>
                        <li>
                            <p class="adloud_label">
                                <b class="open_region caret passive"></b>
                                <label class="checkbox region_check city_check browser" for="check_ru">
                                    <input
                                        type="checkbox"
                                        name="<?php echo $model->getModelName().'[browser][]'; ?>"
                                        value="<?php echo $browser->name; ?>"
                                        <?php if($browser->checked) echo 'checked="checked"'; ?>
                                        data-toggle="checkbox"
                                        class="city_check_box"
                                        />
                                </label>
                                <span class="open_region active"><?=$browser->name?></span>
                            </p>
                        </li>
                    <?php endforeach?>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label class="adloud_label">
            <span class="open_section"><?php echo Yii::t('campaign', 'Операционные системы'); ?> <b class="caret"></b></span>
        </label>

        <p class="adloud_note"><?php echo Yii::t('campaign', 'Выбор ОС для показа объявления'); ?></p>

        <div class="col-sm-12 section device">
            <div class="row">
                <ul class="first_list">
                    <?php foreach($model->getOS() as $os): ?>
                        <li>
                            <p class="adloud_label">
                                <b class="open_region caret active"></b>
                                <label class="checkbox region_check city_check os" data-ua="<?php echo $os->id; ?>" for="check_ru">
                                    <input
                                        type="checkbox"
                                        name="<?php echo $model->getModelName().'[os][]'; ?>"
                                        value="<?php echo $os->name; ?>"
                                        <?php if($os->checked) echo 'checked="checked"'; ?>
                                        data-toggle="checkbox"
                                        class="city_check_box"
                                        />
                                </label>
                                <span class="open_region active"><?=$os->name?></span>
                            </p>
                            <ul class="region_list">
                                <?php foreach($model->getOSVersion($os->name) as $osver): ?>
                                    <li>
                                        <p class="adloud_label">
                                            <b class="open_region caret passive"></b>
                                            <label class="checkbox region_check osVer" data-ua="<?php echo $osver->id; ?>" for="check_ru_east">
                                                <input
                                                    type="checkbox"
                                                    name="<?php echo $model->getModelName().'[osVer][]'; ?>"
                                                    value="<?php echo $osver->id; ?>"
                                                    <?php if($osver->checked) echo 'checked="checked"'; ?>
                                                    data-toggle="checkbox"
                                                    class="city_check_box"
                                                    />
                                            </label>
                                            <span class="open_region passive"><?=$osver->value?></span>
                                        </p>
                                    </li>
                                <?php endforeach?>
                            </ul>
                        </li>
                    <?php endforeach?>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label class="adloud_label">
            <span class="open_section"><?php echo Yii::t('campaign', 'Ретаргетинг'); ?> <b class="caret"></b></span>
        </label>

        <p class="adloud_note"><?php echo Yii::t('campaign', 'Выбор ваших списков для ретаргетинга'); ?></p>

        <div class="col-sm-12 section device">
            <div class="row">
                <ul class="first_list">
                    <?php
                    $lists = $model->getAllTargets();
                    if($lists):
                        foreach($lists as $target): ?>
                            <li>
                                <p class="adloud_label">
                                    <b class="open_region caret passive"></b>
                                    <label class="checkbox region_check city_check targets" data-target="<?php echo $target->id; ?>" for="check_ru">
                                        <input
                                            type="checkbox"
                                            name="<?php echo $model->getModelName().'[targets][]'; ?>"
                                            value="<?php echo $target->id; ?>"
                                            <?php if($target->checked) echo 'checked="checked"'; ?>
                                            data-toggle="checkbox"
                                            class="city_check_box"
                                            />
                                    </label>
                                    <span class="open_region active"><?=$target->name?></span>
                                </p>
                            </li>
                        <?php
                        endforeach;
                    else:?>
                        <li><p class="adloud_label"><?php echo Yii::t('campaign', 'Вы еще не создали ни одного списка таргетов'); ?></p></li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-12">

        <label class="adloud_label">
            <span class="open_section"><?php echo Yii::t('campaign', 'Метка'); ?> <b class="caret"></b></span>
        </label>
        <p class="adloud_note"><?php echo Yii::t('campaign', 'Создайте метку для Вашей кампании'); ?></p>

        <div class="campaighn-mark section col-sm-12">

            <?php $existingLabel = $model->getLabel(); ?>
            <?php $createdLists = $model->getLabelsInputs(); ?>
            <?php $buttonName = $existingLabel ? Yii::t('main', 'Изменить') : Yii::t('main', 'Создать'); ?>

            <div id="existing-label">
                <?php if($existingLabel): ?>
                    <span class="campaighn-mark-color" style="background-color: <?php echo $existingLabel->color; ?>;"></span>
                    <span class="campaighn-mark-name black-mark open"><?php echo $existingLabel->name; ?></span>
                <?php endif; ?>
            </div>

            <div class="campaighn-mark-control new-mark">

                <button data-label="#new-label" id="create-label-button" class="campaighn-mark-btn edit-mark-btn " type="button"><i class="fa fa-edit"></i><?php echo $buttonName; ?></button>

                <?php if($createdLists): ?>
                    <button data-label="#select-label" id="select-label-button" class="campaighn-mark-btn select-mark-btn " type="button"><i class="fa fa-list"></i><?php echo Yii::t('campaign', 'Выбрать из списка'); ?></button>
                <?php endif; ?>

                <?php if($existingLabel): ?>
                    <button data-url="<?php echo Yii::app()->createUrl('advertiser/campaign/removeLabel', ['id'=>$model->id]); ?>" data-confirm="Вы действительно ходите удалить метку?" data-input="#delete-label" id="delete-label-button" class="campaighn-mark-btn remove-mark-btn" type="button"><i class="fa fa-trash-o"></i>Удалить</button>
                    <input id="delete-label" type="hidden" name="<?php echo $model->getModelName(); ?>[label][delete]" value="" />
                <?php endif; ?>

                <div id="new-label" class="edit-mark-content hide">

                    <label for="" class="adloud_label"><?php echo Yii::t('campaign', 'Имя метки'); ?></label>
                    <input data-text="#existing-label .campaighn-mark-name" class="form-control flat" type="text" value="<?php if($existingLabel) echo trim($existingLabel->name); ?>" name="<?php echo $model->getModelName(); ?>[label][name]" />

                    <label class="adloud_label"><?php echo Yii::t('campaign', 'Цвет метки'); ?></label>
                    <select data-color="#existing-label .campaighn-mark-color" name="<?php echo $model->getModelName(); ?>[label][color]" class="select-mark-color">
                        <?php foreach($model->getLabelColors() as $color): ?>
                            <option
                                value="<?php echo $color->value; ?>"
                                <?php if($color->checked) echo 'selected="selected"'; ?>
                            >
                                <?php echo $color->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <?php if($createdLists): ?>
                    <div id="select-label" class="select-mark-content hide">
                        <select data-color="#existing-label .campaighn-mark-color" data-text="#existing-label .campaighn-mark-name" class="select-previous-mark" name="<?php echo $model->getModelName(); ?>[label][existing]">

                            <?php if(!$existingLabel): ?>
                                <option value="" selected="selected"></option>
                            <?php endif; ?>

                            <?php foreach($model->getLabelsInputs() as $label): ?>
                                <?php if(!$label->checked): ?>
                                    <option data-color="<?php echo $label->color; ?>" data-text="<?php echo $label->name; ?>" value="<?php echo $label->value; ?>"><?php echo $label->name; ?></option>
                                <?php else: ?>
                                    <option value="" data-color="<?php echo $label->color; ?>" data-text="<?php echo $label->name; ?>" selected="selected"><?php echo $label->name; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </select>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-embossed adloud_btn col-sm-7">
            <span class="input-icon fui-check fui-lg pull-left"></span><?php echo $model->id ? Yii::t('campaign', 'Редактировать кампанию') : Yii::t('campaign', 'Создать новую кампанию'); ?>
        </button>
    </div>

    </div>
    </div>

    <div class="col-sm-12 col-md-6 check_camp_cat">

        <div class="row">

            <div class="col-sm-12">
                <div class="row">
                    <label class="adloud_label"><?php echo Yii::t('campaign', 'Выберите тематику вашей рекламной кампании'); ?></label>
                    <select name="<?php echo $model->getModelName(); ?>[subject]" class="ad_camp_theme">
                        <?php foreach($model->getCategoriesList() as $cat): ?>
                            <option
                                value="<?php echo $cat->id; ?>"
                                <?php if($cat->checked) echo 'selected="selected"'; ?>
                                >
                                <?php echo Yii::t('campaign', $cat->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <label class="adloud_label"><?php echo Yii::t('campaign', 'Выберите категории сайтов для показа ваших объявлений'); ?></label>
            <table class="table table-bordered adloud_table">

                <thead>
                <tr>
                    <th class="category_check">
                        <label class="checkbox toggle-all" for="checkbox-table-campaign">
                            <input type="checkbox" value="" id="checkbox-table-campaign" class="adloud_checkbox" data-toggle="checkbox">
                        </label>
                    </th>
                    <th class="category_name">
                        <?php echo Yii::t('main', 'Категория'); ?>
                    </th>
                    <!--<th class="category_price">
                        Минимальн.<br/>цена
                    </th>-->
                </tr>
                </thead>

                <tbody>

                <?php foreach($model->getShowCategories() as $cat):?>
                    <tr>
                        <td class="category_check">
                            <label class="checkbox" for="checkbox-table-2">
                                <input
                                    type="checkbox"
                                    class="adloud_checkbox"
                                    value="<?php echo $cat->id; ?>"
                                    data-toggle="checkbox"
                                    name="<?php echo $model->getModelName(); ?>[categories][<?php echo $cat->id; ?>]"
                                    <?php if($cat->checked) echo 'checked="checked"'; ?>
                                    />
                            </label>
                        </td>
                        <td class="category_name">
                            <?php echo Yii::t('campaign', $cat->getName()); ?>
                        </td>

                    </tr>
                <?php endforeach?>

                </tbody>

            </table>

        </div>

    </div>

    </div>

<?php echo CHtml::endForm(); ?>

<?php Yii::app()->clientScript->registerScript('campaign-page', '
    Campaign.init();
'); ?>