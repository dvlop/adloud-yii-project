<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/9/14
 * Time: 1:23 AM
 * @var $model AdsForm
 * @var $this AdvertiserController
 */
?>

<?php
Yii::app()->clientScript
    ->registerCssFile(Yii::app()->theme->baseUrl.'/assets/plugins/jquery.imgareaselect/css/imgareaselect-default.css')
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/plugins/jquery.imgareaselect/js/jquery.imgareaselect.min.js', CClientScript::POS_END)
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/plugins/file-uploader/js/vendor/jquery.ui.widget.js', CClientScript::POS_END)
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/plugins/file-uploader/js/jquery.iframe-transport.js', CClientScript::POS_END)
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/plugins/file-uploader/js/jquery.fileupload.js', CClientScript::POS_END)
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/ads.js', CClientScript::POS_END);
?>

<div id="AdsForm_error" class="alert alert-block alert-danger fade in <?php if(!CHtml::errorSummary($model)) echo 'hidden'; ?>">
<?php if(CHtml::errorSummary($model)): ?>
        <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
<?php endif;?>
</div>

<?php $this->renderPartial('themes.bootstrap.views._modals.modalImageUploadCrop', [
    'id' => 'cropping-window-modal',
    'title' => 'Настройка изображения',
    'imageId' => 'ads-image-crop-id',
    'action' => $this->createAbsoluteUrl('advertiser/ads/preview/', [
            'id' => Yii::app()->request->getQuery('id'),
            'campaignId' => Yii::app()->request->getQuery('campaignId'),
        ]),

]); ?>

<div class="panel panel-blue margin-bottom-40">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-edit"></i> Управление обьявлением</h3>
    </div>
    <div class="panel-body">
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal', 'enctype'=>'multipart/form-data')); ?>

        <div class="col-lg-9" id="ads-creating">
            <h4><?php if($model->isNew): ?>Создание объявления<?php else: ?>Редактирование объявления<?php endif ?></h4>

            <div class="form-group <?php if($model->hasErrors('caption')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model, 'caption', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'caption', array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('description')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model, 'description', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextArea($model,'description', array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('showButton')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model, 'showButton', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeCheckBox($model,'showButton', array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('buttonText')) echo 'has-error';?> <?php if(!$model->showButton) echo 'hidden' ?>">
                <?php echo CHtml::activeLabel($model, 'buttonText', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'buttonText', array('class' => 'form-control')); ?>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('url')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model, 'url', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'url', array('class' => 'form-control', 'placeholder' => 'http://')); ?>
                    <small><em>Просим не использовать сокращатели ссылок по типу bit.ly.</em></small>
                    <small><em>В случае использования таких систем мы не несем ответственности за работоспособность ссылок.</em></small>
                </div>
            </div>
            <div class="form-group <?php if($model->hasErrors('imageUpload')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model, 'image', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeFileField($model, 'imageUpload', array('class' => 'form-control')); ?>

                    <?php if(!empty($model->imageUrl)):?>
                        <br><img src="<?php echo $model->imageUrl;?>" style="width: 100px;">
                    <?php endif;?>
                </div>
                <?php echo CHtml::hiddenField('AdsForm[image]', $model->image, array('class' => 'form-control')); ?>
            </div>

            <div class="form-group <?php if($model->hasErrors('additionalCategories')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'additionalCategories', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <div class="input-group">
                        <?php echo CHtml::activeDropDownList($model, 'additionalCategories', $model->getCategories(), array('multiple'=>'multiple', 'class'=>'multiselect')); ?>
                    </div>
                </div>
            </div>

            <div class="form-group <?php if($model->hasErrors('clickPrice')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($model,'clickPrice', array('class'=>'col-lg-3 control-label')); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($model,'clickPrice', array('class' => 'form-control', 'placeholder' => '0.00')); ?>
                </div>
            </div>

        </div>

        <div class="col-lg-3">
            <h4>Предпросмотр объявления</h4>
            <div id="ads-preview"></div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?php echo CHtml::submitButton($model->isNew ? 'Добавить обьявление' : 'Редактировать обьявление', array('class' => 'btn-u')); ?> или <a href="<?php echo Yii::app()->createUrl('advertiser/campaign/list');?>">Отменить</a>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript('creatingAds', '
    Ads.init({
        url: "'.$this->createAbsoluteUrl('advertiser/ads/preview/', [
                    'id' => Yii::app()->request->getQuery('id'),
                    'campaignId' => Yii::app()->request->getQuery('campaignId'),
                ]).'",
        showSystemErrors: '.YII_DEBUG.'
    });
'); ?>