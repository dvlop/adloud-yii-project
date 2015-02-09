<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 16.02.14
 * Time: 21:19
 * @var BlockForm $model
 */
?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/adsBlock.js', CClientScript::POS_END); ?>

<?php if(CHtml::errorSummary($model)):?>
    <div class="alert alert-block alert-danger fade in">
        <?php echo CHtml::errorSummary($model, 'Возникли некоторые ошибки:'); ?>
    </div>
<?php endif;?>

<div class="panel panel-blue margin-bottom-40" id="block-editor">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-edit"></i> Настройка тизерного блока</h3>
    </div>

    <div class="panel-body">
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>

        <div class="tab-v2">
            <ul class="nav nav-tabs" id="block-preview-tabs">
                <li class="active"><a data-toggle="tab" href="#block-preview" id="block-preview-tab">Предпросмотр блока</a></li>
            </ul>
            <div class="tab-content">
                <div id="block-preview" class="tab-pane fade in active">
                    <?php $this->renderPartial('/webmaster/_block/tab_top_block_preview', array('model' => $model, 'id' => $id, 'blockCode' => $blockCode));?>
                </div>
            </div>
        </div>

        <div class="margin-bottom-40"></div>

        <div class="tab-v2">
            <ul class="nav nav-tabs" id="block-editor-tabs">
                <li class="block-preview-tab active"><a data-toggle="tab" href="#block">Блок</a></li>

                <?php if(!empty($id)):?>
                <li class="teaser-preview-tab <?php echo empty($id) ? '' : 'active';?>"><a data-toggle="tab" href="#code">Код для размещения</a></li>
                <?php endif;?>
            </ul>

            <div class="tab-content">

                <div id="block" class="tab-pane fade in active">
                    <?php $this->renderPartial('/webmaster/_block/tab_block', array('model' => $model, 'id' => $id, 'blockCode' => $blockCode));?>
                </div>

                <?php if(!empty($id)):?>
                <div id="code" class="tab-pane fade <?php echo empty($id) ? '' : 'active in';?>">
                    <?php $this->renderPartial('/webmaster/_block/tab_code', array('model' => $model, 'id' => $id, 'blockCode' => $blockCode));?>
                </div>
                <?php endif;?>

            </div>

        </div>

        <div class="margin-bottom-30"></div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?php echo CHtml::submitButton(empty($id) ? 'Создать рекламный блок' : 'Редактировать рекламный блок', array('class' => 'btn-u')); ?> или <a href="<?php echo Yii::app()->createUrl('webmaster/site/list');?>">Отменить</a>
            </div>
        </div>

        <?php echo CHtml::endForm(); ?>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('creatingAdsBlock', '
    AdsBlock.init({});
'); ?>