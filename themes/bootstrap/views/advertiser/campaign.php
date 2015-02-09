<?php

/**
 * @var $model CampaignForm
 * @var $this AdvertiserController
 */
?>

<?php
Yii::app()->clientScript
    ->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/bootstrap-datepicker.css')
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/bootstrap-datepicker.js', CClientScript::POS_END)
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/locales/bootstrap-datepicker.ru.js', CClientScript::POS_END);
?>

<?php if(CHtml::errorSummary($model)):?>
    <div class="alert alert-block alert-danger fade in">
        <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
    </div>
<?php endif;?>

    <div class="panel panel-blue margin-bottom-40">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon-edit"></i> Информация о кампании</h3>
        </div>
        <div class="panel-body">
            <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>

            <div class="headline"><h4>Основные настройки</h4></div>

            <div class="form-group <?php if($model->hasErrors('description')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'description', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextArea($model,'description', array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('categories')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'categories', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <div class="input-group">
                        <?php echo CHtml::activeDropDownList($model, 'categories', $model->getCategories(), array('multiple'=>'multiple', 'class'=>'multiselect')); ?>
                    </div>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('limit')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'limit', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'limit', array('class' => 'form-control', 'placeholder' => '0.00')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('dailyLimit')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'dailyLimit', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'dailyLimit', array('class' => 'form-control', 'placeholder' => '0.00')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('clickPrice')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'clickPrice', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'clickPrice', array('class' => 'form-control', 'placeholder' => '0.00')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('startDate')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'startDate', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'startDate', array('class' => 'form-control', )); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('stopDate')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'stopDate', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'stopDate', array('class' => 'form-control', )); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('geoZoneCountry')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'geoZoneCountry', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeDropDownList($model, 'geoZoneCountry', CHtml::listData($model->getCountries(), 'id', 'name'), [
                        'encode' => false,
                        'multiple' => 'multiple',
                        'class' => 'select',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => Yii::app()->createUrl('advertiser/selectCountry'),
                            'data' => 'js:$(this).closest("form").serializeArray()',
                            'success' => 'function(json){
                            var selectRegionsMainContainer = $("#campaignModel-dropDown-regions-container");
                            var selectRegionsContainer = $("#campaignModel-dropDown-regions");
                            var selectRegionsFieldsId = "#CampaignForm_geoZoneRegion";

                            var selectCitiesMainContainer = $("#campaignModel-dropDown-cities-container");
                            var selectCitiesContainer = $("#campaignModel-dropDown-cities");
                            var selectCitiesFieldsId = "#CampaignForm_geoZoneCity";

                            if(json){
                                selectRegionsContainer.html(json);
                                $(selectRegionsFieldsId).multiselect({
                                    maxHeight: "200",
                                    enableFiltering: true,
                                    //includeSelectAllOption: true,
                                    //selectAllText: "Выбрать все",
                                    filterPlaceholder: "Поиск",
                                    nonSelectedText: "Не выбрано",
                                    nSelectedText: "выбрано",
                                    enableCaseInsensitiveFiltering: true,
                                    onChange: function(element, checked){
                                        $.ajax({
                                            type: "POST",
                                            url: "'.Yii::app()->createAbsoluteUrl('advertiser/selectRegion').'",
                                            data: selectCitiesContainer.closest("form").serializeArray(),
                                            success: function(json){
                                                if(json){
                                                    selectCitiesContainer.html(json);
                                                    $(selectCitiesFieldsId).multiselect({
                                                        maxHeight: "200",
                                                        enableFiltering: true,
                                                        //includeSelectAllOption: true,
                                                        //selectAllText: "Выбрать все",
                                                        filterPlaceholder: "Поиск",
                                                        nonSelectedText: "Не выбрано",
                                                        nSelectedText: "выбрано",
                                                        enableCaseInsensitiveFiltering: true
                                                    });
                                                    selectCitiesMainContainer.removeClass("hidden");
                                                }else if(!selectCitiesMainContainer.hasClass("hidden")){
                                                    selectCitiesMainContainer.addClass("hidden");
                                                    $(selectCitiesFieldsId).remove();
                                                }
                                            },
                                        });
                                    }
                                });
                                selectRegionsMainContainer.removeClass("hidden");
                            }else{
                                if(!selectRegionsMainContainer.hasClass("hidden")){
                                    selectRegionsMainContainer.addClass("hidden");
                                    $(selectRegionsFieldsId).remove();
                                }

                                if(!selectCitiesMainContainer.hasClass("hidden")){
                                    selectCitiesMainContainer.addClass("hidden");
                                    $(selectCitiesFieldsId).remove();
                                }
                            }
                        }',
                        ),
                    ]); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('geoZoneRegion')) echo 'has-error';?> <?php if(!$model->geoZoneCountry): ?> hidden<?php endif ?>" id="campaignModel-dropDown-regions-container">
                <?php echo CHtml::activeLabel($model,'geoZoneRegion', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9" id="campaignModel-dropDown-regions">
                    <?php if($model->geoZoneCountry): ?>
                        <?php if(count($model->geoZoneCountry) > 1): ?>
                            <?php echo CHtml::activeDropDownList($model, 'geoZoneRegion', CHtml::listData($model->getRegionsAndCountries($model->geoZoneCountry), 'id', 'region_name', 'country_name'), [
                                'encode' => false,
                                'multiple' => 'multiple',
                                'class' => 'select',
                            ]); ?>
                        <?php else: ?>
                            <?php echo CHtml::activeDropDownList($model, 'geoZoneRegion', CHtml::listData($model->getRegions($model->geoZoneCountry), 'id', 'name'), [
                                'encode' => false,
                                'multiple' => 'multiple',
                                'class' => 'select',
                            ]); ?>
                        <?php endif; ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group <?php if(!$model->geoZoneRegion): ?> hidden<?php endif ?>" id="campaignModel-dropDown-cities-container">
                <?php echo CHtml::activeLabel($model,'geoZoneCity', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9" id="campaignModel-dropDown-cities">
                    <?php if($model->geoZoneRegion): ?>
                        <?php if(count($model->geoZoneRegion) > 1): ?>
                            <?php echo CHtml::activeDropDownList($model, 'geoZoneCity', CHtml::listData($model->getCitiesAndRegions($model->geoZoneRegion), 'id', 'city_name', 'region_name'), [
                                'encode' => false,
                                'multiple' => 'multiple',
                                'class' => 'select',
                            ]); ?>
                        <?php else: ?>
                            <?php echo CHtml::activeDropDownList($model, 'geoZoneCity', CHtml::listData($model->getCities($model->geoZoneRegion), 'id', 'name'), [
                                'encode' => false,
                                'multiple' => 'multiple',
                                'class' => 'select',
                            ]); ?>
                        <?php endif; ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-9">
                    <?php echo CHtml::submitButton(empty($model->description) ? 'Добавить кампанию' : 'Редактировать кампанию', array('class' => 'btn-u')); ?> или <a href="<?php echo Yii::app()->createUrl('advertiser/campaign/list');?>">Отменить</a>
                </div>
            </div>

            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
<?php Yii::app()->clientScript->registerScript('setDateLimit', '
    $("#CampaignForm_startDate").datepicker({
        language: "ru",
        format: "yyyy-mm-dd"
    });

    $("#CampaignForm_stopDate").datepicker({
        language: "ru",
        format: "yyyy-mm-dd"
    });
'); ?>

<?php Yii::app()->clientScript->registerScript('countriesMultiselect', '
    $("#CampaignForm_geoZoneCountry").multiselect({
        maxHeight: "200",
        enableFiltering: true,
        filterPlaceholder: "Поиск",
        nonSelectedText: "Не выбрано",
        nSelectedText: "выбрано",
        enableCaseInsensitiveFiltering: true
    });
'); ?>
<?php if($model->geoZoneCountry){
    Yii::app()->clientScript->registerScript('regionsMultiselect', '
    var selectCitiesMainContainer = $("#campaignModel-dropDown-cities-container");
    var selectCitiesContainer = $("#campaignModel-dropDown-cities");
    var selectCitiesFieldsId = "#CampaignForm_geoZoneCity";

    $("#CampaignForm_geoZoneRegion").multiselect({
        maxHeight: "200",
        enableFiltering: true,
        filterPlaceholder: "Поиск",
        nonSelectedText: "Не выбрано",
        nSelectedText: "выбрано",
        enableCaseInsensitiveFiltering: true,
        onChange: function(element, checked){
            $.ajax({
                type: "POST",
                url: "'.Yii::app()->createAbsoluteUrl('advertiser/selectRegion').'",
                data: selectCitiesContainer.closest("form").serializeArray(),
                success: function(json){
                    if(json){
                        selectCitiesContainer.html(json);
                        $(selectCitiesFieldsId).multiselect({
                                    maxHeight: "200",
                                    enableFiltering: true,
                                    //includeSelectAllOption: true,
                                    //selectAllText: "Выбрать все",
                                    filterPlaceholder: "Поиск",
                                    nonSelectedText: "Не выбрано",
                                    nSelectedText: "выбрано",
                                    enableCaseInsensitiveFiltering: true
                                });
                                selectCitiesMainContainer.removeClass("hidden");
                            }else if(!selectCitiesMainContainer.hasClass("hidden")){
                        selectCitiesMainContainer.addClass("hidden");
                        $(selectCitiesFieldsId).remove();
                    }
                },
            });
        }
    });
');
} ?>

<?php if($model->geoZoneRegion){
    Yii::app()->clientScript->registerScript('citiesMultiselect', '
    $("#CampaignForm_geoZoneCity").multiselect({
        maxHeight: "200",
        enableFiltering: true,
        filterPlaceholder: "Поиск",
        nonSelectedText: "Не выбрано",
        nSelectedText: "выбрано",
        enableCaseInsensitiveFiltering: true
    });
');
} ?>