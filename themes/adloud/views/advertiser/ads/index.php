<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 06.05.14
 * Time: 15:47
 * @var \application\models\Ads $model
 * @var $this \application\modules\advertiser\controllers\AdsController
 * @var integer $campaignId
 * @var integer $id
 * @var $buttonName
 * @var string $action
 */
?>
<?php echo CHtml::beginForm('', 'post', [
    'id' => 'create_teaser',
    'class' => 'col-sm-12 col-md-12 fileinput fileinput-new',
    'data-provides' => 'fileinput',
    'enctype' => 'multipart/form-data',
]); ?>
    <div class="row">

        <div class="col-sm-6 col-md-6 create_teaser_form">

            <div class="form-group">
                <label class="adloud_label"><?php echo Yii::t('ads_campaign', 'Введите заголовок:'); ?></label>
                <?php echo CHtml::activeTextField($model, 'caption', [
                    'class' => 'form-control flat',
                    'required' => 'true',
                    'maxlength' => 50,
                ]); ?>
                <span class="adloud_note"><?php echo Yii::t('main', 'Осталось символов'); ?>: <span></span></span>
            </div>

            <div class="form-group">
                <label class="adloud_label"><?php echo Yii::t('ads_campaign', 'Введите описание:'); ?></label>
                <?php echo CHtml::activeTextArea($model, 'description', [
                    'class' => 'form-control flat',
                    'required' => 'true',
                    'maxlength' => 50
                ]); ?>
                <span class="adloud_note"><?php echo Yii::t('main', 'Осталось символов'); ?>: <span></span></span>
            </div>

            <div class="form-group">
                <div class="create_img">
                <span class="btn btn-primary btn-block btn-embossed btn-file btn-inverse">
                  <span class="fileinput-new">
                    <span class="fui-image"></span>&nbsp;&nbsp;<?php echo Yii::t('ads_campaign', 'Загрузить изображение'); ?>
                  </span>
                  <span class="fileinput-exists">
                    <span class="fui-gear"></span>&nbsp;&nbsp;<?php echo Yii::t('main', 'Изменить'); ?>
                  </span>
                  <?php echo CHtml::activeFileField($model, 'image'); ?>
                </span>
                    <a id="remove-img" href="#" class="btn btn-primary btn-embossed fileinput-exists btn-block btn-inverse" data-dismiss="fileinput">
                        <span class="fui-trash"></span>&nbsp;&nbsp;<?php echo Yii::t('main', 'Удалить'); ?>
                    </a>
                </div>
            </div>

            <div class="form-group">
                <label class="adloud_label"><?php echo Yii::t('ads_campaign', 'Введите адрес рекламируемой страницы:'); ?></label>
                <?php echo CHtml::activeUrlField($model, 'url', [
                    'class' => 'form-control flat',
                    'required' => 'true',
                    'placeholder' => 'http://site.example.com',
                ]); ?>
            </div>

            <div class="form-group">
                <label class="adloud_label"><?php echo Yii::t('ads_campaign', 'Введите URL отображаемый в объявлении:'); ?></label>
                <?php echo CHtml::activeTextField($model, 'showUrl', [
                    'class' => 'form-control flat',
                    'required' => 'true',
                    'placeholder' => 'example.com',
                ]); ?>
            </div>

            <!--<div class="form-group action_button">
                <label class="adloud_label">Кнопка призыва к действию:</label>
                <?php /*foreach($model->showButtonList as $button): ?>
                    <label class="radio adloud_label<?php if($button->checked) echo ' checked'; ?>">
                        <?php echo CHtml::radioButton($button->name, $button->checked, [
                            'id' => 'AdsForm_showButton_'.$button->value,
                            'data-toggle' => 'radio',
                            'value' => $button->value,
                        ]); ?> <?php echo $button->text; ?>
                    </label>
                <?php endforeach;*/ ?>
            </div>-->

            <div class="form-group"<?php if(!$model->getShowButton()) echo ' style="display: none;"'; ?>>
                <label class="adloud_label"><?php echo Yii::t('ads_campaign', 'Введите текст кнопки:'); ?></label>
                <?php echo CHtml::activeTextField($model, 'buttonText',[
                    'class' => 'form-control flat',
                    'maxlength' => 16
                ]); ?>
                <span class="adloud_note"><?php echo Yii::t('main', 'Осталось символов'); ?>: <span></span></span>
            </div>

            <div class="form-group">
                <label class="adloud_label"><?php echo Yii::t('ads_campaign', 'Стоимость клика:'); ?></label>
                <?php echo CHtml::activeTextField($model, 'clickPrice', array('class' => 'form-control flat', 'required' => 'true', 'pattern' => '^[-+]?[0-9]*\.?[0-9]+$')); ?>
            </div>

            <?php if($action == 'copy'): ?>
                <div class="form-group campaign-selector">
                    <label class="adloud_label"><?php echo Yii::t('ads_campaign', 'Скопировать объявление в компанию:'); ?></label>
                    <select id="<?php echo $model->getModelName(); ?>_adsCopyCampaign" name="<?php echo $model->getModelName(); ?>[adsCopyCampaign]">
                        <?php foreach($model->getCampaignsList() as $camp): ?>
                            <option value="<?php echo $camp->id; ?>"<?php if($camp->checked) echo 'selected="selected"'; ?>>
                                <?php echo $camp->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>


            <div class="form-group create_or_cancel">

                <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 submit_teaser">
                    <button class="btn btn-embossed adloud_btn create_new_campaign col-md-12 col-sm-12" type="submit">
                        <span class="input-icon fui-check fui-lg pull-left"></span><?php echo $buttonName; ?>
                    </button>
                </div>

                <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 cancel">
                    <button type="reset" class="btn adloud_btn btn-block auto-link" data-url="<?php echo $this->createAbsoluteUrl('/advertiser/ads/list', ['campaignId' => $campaignId]); ?>">
                        <span class="input-icon fui-cross"></span> <?php echo Yii::t('main', 'Отмена'); ?>
                    </button>
                </div>

            </div>

        </div>

        <div class="col-sm-6 col-md-4 col-md-offset-1 teaser_preview adloud_color" id="preview-section">

            <p class="tab_title"><?php echo Yii::t('ads_campaign', 'Предпросмотр объявления шириной:'); ?></p>
            <ul class="list-inline teaser_preview_size">
                <li class="active"><a href="#" data-id="300x250" class="tab_mark create_teaser_bg">300px</a></li>
                <li><a href="#" data-id="240x400" class="tab_mark create_teaser_bg">240px</a></li>
                <li><a href="#" data-id="160x600" class="tab_mark create_teaser_bg">160px</a></li>
                <li><a href="#" data-id="all" class="tab_mark create_teaser_bg"><?php echo Yii::t('ads_campaign', 'Показать все'); ?></a></li>
            </ul>

            <div class="tab-content"></div>

        </div>

    </div>

    <div id="preview-modal" style="display:none;">
        <div id="crop-source" class="fileinput-preview thumbnail" >

        </div>
        <span id="crop-done" class="btn btn-primary btn-block btn-embossed btn-inverse"><?php echo Yii::t('ads_campaign', 'Готово'); ?><span>
    </div>
    <?php echo CHtml::activeHiddenField($model, 'showButton', ['id'=>'show-button']); ?>
    <?php echo CHtml::activeHiddenField($model, 'cropParams', ['id'=>'crop-params']); ?>

<?php echo CHtml::endForm(); ?>

<?php Yii::app()->clientScript->registerScript('ads-create', '
    Ads.init({
        previewUrl: "'.Yii::app()->createUrl('advertiser/ads/preview', ['campaignId' => $campaignId, 'id' => $id]).'",
        selectCampId: "#'.$model->getModelName().'_adsCopyCampaign"
    });
'); ?>