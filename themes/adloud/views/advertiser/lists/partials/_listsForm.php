<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 09.07.14
 * Time: 18:03
 * @var \application\modules\advertiser\controllers\ListsController $this
 * @var ListsSitesForm $model
 */
?>

<?php Yii::app()->clientScript->registerScriptFile(\Yii::app()->theme->baseUrl.'/assets/js/pages/listsSites.js', \CClientScript::POS_END); ?>

<?php echo CHtml::beginForm('', 'POST', [
    'class' => 'col-md-12',
    'id' => 'sites_lists',
    'style' => $model->id ? 'display: block;' : 'display: none;',
]); ?>

    <div class="row">

        <div class="col-sm-6">

            <div class="row">

                <div class="form-group col-sm-12">
                    <?php echo CHtml::activeTextField($model, 'name', [
                        'class' => 'form-control flat',
                        'required' => true,
                        'placeholder' => $model->getAttributeLabel('siteName'),
                    ]); ?>
                </div>

                <div class="form-group col-sm-12">
                    <?php foreach($model->typesNames as $list): ?>

                        <label class="radio adloud_label col-sm-5">
                            <?php echo CHtml::radioButton(get_class($model).'[type]', $list->checked, [
                                'data-toggle' => 'radio',
                                'value' => $list->value,
                                'id' => get_class($model).'_type_'.$list->value,
                            ]); ?>
                            <?php echo \Yii::t('lists', $list->name); ?>
                        </label>

                    <?php endforeach; ?>
                </div>

                <div class="form-group col-sm-12">
                    <?php echo CHtml::TextArea(get_class($model).'[sites]', $model->sitesText, [
                        'class' => 'form-control flat',
                        'placeholder' => $model->getAttributeLabel('sites'),
                        'required' => true,
                    ]); ?>
                </div>

                <div class="form-group col-sm-7 col-md-6">
                    <button type="submit" class="btn btn-inverse btn-block"><span class="input-icon fui-check fui-lg pull-left"></span> <?php echo Yii::t('lists', 'Сохранить список'); ?></button>
                </div>

            </div>

        </div>

        <div class="col-sm-6 check_camp_for_list">

            <div class="col-sm-12">
                <?php echo CHtml::activeLabel($model, 'campaigns', [
                    'class' => 'adloud_label',
                ]); ?>
                <label class="checkbox adloud_label select-all" for="check_all_camp">
                    <input type="checkbox" class="adloud_checkbox" id="check_all_camp" data-toggle="checkbox"> <?php echo Yii::t('lists', 'Выбрать все'); ?>
                </label>
            </div>

            <div class="form-goup check_camp_list col-sm-12">
                <?php foreach($model->campaignsList as $campaign): ?>
                    <?php if(!$campaign->disabled): ?>
                        <label
                            class="checkbox adloud_label col-sm-6 pull-left"
                            for="<?php echo $campaign->description; ?>"
                        >
                    <?php else: ?>
                        <label
                            class="checkbox adloud_label col-sm-6 pull-left"
                            for="<?php echo $campaign->description; ?>"
                            data-toggle="tooltip"
                            data-tooltip-style="light"
                            data-placement="left"
                            data-original-title="Эта кампания уже указана в другом списке"
                        >
                    <?php endif; ?>


                        <?php echo CHtml::activeCheckBox($model, 'campaigns', [
                            'data-toggle' => 'checkbox',
                            'class' => 'adloud_checkbox',
                            'id' => 'campaign-'.$campaign->id,
                            'value' => $campaign->id,
                            'checked' => $campaign->checked,
                            'disabled' => $campaign->disabled,
                        ]); ?>
                        <?php echo $campaign->description; ?>
                    </label>
                <?php endforeach; ?>
            </div>

        </div>

    </div>

<?php echo CHtml::endForm(); ?>

<?php Yii::app()->clientScript->registerScript('listsSites', '
    ListsSites.init();
'); ?>