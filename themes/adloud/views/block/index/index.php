<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 22.08.14
 * Time: 14:33
 * @var \application\modules\block\controllers\IndexController $this
 * @var \application\models\Blocks $model
 */
?>

<?php
    use application\models\Blocks;

    $img = Yii::app()->theme->baseUrl.'/assets/images/';
    $modelName = $model->getModelName();
    $modelId = $modelName.'_';
?>

<div class="col-sm-12 creative-blocks-preview">

    <div class="left-preview-bg">
    </div>
    <div class="top-preview-bg">
    </div>

    <div id="preview-block" class="right-float"></div>

</div>

<div class="creative-blocks-tools-panel">

    <!-- <div class="colorpicker" style="display:none">
      <canvas id="picker" var="1" width="180" height="180"></canvas>
    </div> -->
    <div class="toggle-panel-wrapper">
        <div class="toggle-panel">
            <i class="fa fa-chevron-down"></i>
        </div>
    </div>

    <div class="creative-blocks-tools-wrapper creative-blocks-simple">

        <?php echo CHtml::beginForm('', 'post', [
            'id' => 'create_block',
            'class' => 'creative-blocks-tools',
        ]); ?>

            <div class="creative-blocks-tools-logo creative-blocks-tools-item text-center">

                  <span><?php echo Yii::t('block_market', 'Creative Blocks'); ?>
                    <sup class="version_note">beta&nbsp;
                        <img src="<?php echo $img; ?>adloud/beta_v.png">
                    </sup>
                  </span>
                <br/>
                <img src="<?php echo $img; ?>adloud/cr-blocks-tools-logo.png"/>

            </div>

            <div class="creative-blocks-tools-item creative-blocks-tools-name">

                <div>
                    <?php echo CHtml::activeLabel($model, 'caption'); ?>
                    <?php echo CHtml::activeTextField($model, 'caption', ['class' => 'form-control flat']); ?>
                </div>

                <div class="width-tool">

                    <?php echo CHtml::activeLabel($model, 'size'); ?>
                    <select id="<?php echo $modelId ?>size" name="<?php echo $modelName ?>[size]">
                        <?php foreach($model->getSizes() as $size): ?>
                            <option
                                value="<?php echo $size->value; ?>"
                                data-class="adld-tsr-<?php echo $model->getFormat() == Blocks::FORMAT_SIMPLE ? $model->getFormat().$size->value : $size->value; ?>"
                                data-width="<?php echo $size->width; ?>"
                                data-height="<?php echo $size->height; ?>"
                                <?php if($size->checked) echo 'selected="selected"'; ?>
                            >
                                <?php echo $size->value; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

            </div>

            <div class="creative-blocks-tools-item creative-blocks-tools-format">

                <div class="number-tool">
                    <?php echo CHtml::activeLabel($model, 'verticalCount'); ?>
                    <div id="teaser-vertical-amount" class="teaser-amount"></div>
                </div>

                <div class="number-tool">
                    <?php echo CHtml::activeLabel($model, 'horizontalCount'); ?>
                    <div id="teaser-horizontal-amount" class="teaser-amount"></div>
                </div>

            </div>

            <div class="creative-blocks-tools-item color-group">

                <div>

                    <?php echo CHtml::activeLabel($model, 'captionColor'); ?>

                    <div class="color-tool title-color">
                        <span>#</span>
                        <?php echo CHtml::activeTextField($model, 'captionColor' , [
                            'class' => 'form-control flat color-value',
                            'data-market-selector' => 'div.adld-tsr-title > span',
                            'data-simple-selector' => '.adld-simple-tsr-cell .adld-simple-tsr-title',
                            'data-market-property' => 'color',
                            'data-simple-property' => 'color',
                            'data-opacity-id' => '#'.$modelId.'captionOpacity',
                        ]); ?>
                    </div>

                </div>

                <div>

                    <?php echo CHtml::activeLabel($model, 'textColor'); ?>

                    <div class="color-tool text-color">
                        <span>#</span>
                        <?php echo CHtml::activeTextField($model, 'textColor' , [
                            'class' => 'form-control flat color-value',
                            'data-market-selector' => 'span.adld-tsr-link-url,div.adld-adld-tsr-description > span',
                            'data-simple-selector' => '.adld-simple-tsr-cell .adld-simple-tsr-dscrptn',
                            'data-market-property' => 'color',
                            'data-simple-property' => 'color',
                            'data-opacity-id' => '#'.$modelId.'textOpacity',
                        ]); ?>
                    </div>

                </div>

            </div>

            <div class="creative-blocks-tools-item color-group">

                <div>

                    <?php echo CHtml::activeLabel($model, 'buttonColor'); ?>

                    <div class="color-tool btn-color">
                        <span>#</span>
                        <?php echo CHtml::activeTextField($model, 'buttonColor' , [
                            'class' => 'form-control flat color-value',
                            'data-market-selector' => 'div.adld-tsr-btn > span.btn',
                            'data-simple-selector' => '.adld-simple-tsr-cell .adld-simple-tsr-link',
                            'data-market-property' => 'background',
                            'data-simple-property' => 'color',
                            'data-opacity-id' => '#'.$modelId.'buttonOpacity',
                        ]); ?>
                    </div>

                </div>

                <div>

                    <?php echo CHtml::activeLabel($model, 'backgroundColor'); ?>

                    <div class="color-tool bg-color">
                        <span>#</span>
                        <?php echo CHtml::activeTextField($model, 'backgroundColor' , [
                            'class' => 'form-control flat color-value',
                            'data-market-selector' => 'div.adld-tsr-table',
                            'data-simple-selector' => '.adld-simple-tsr-cell .adld-simple-tsr-txt-cntnr, .adld-simple-tsr-cell .adld-simple-adld-tsr-img-cntnr',
                            'data-market-property' => 'background',
                            'data-simple-property' => 'background',
                            'data-opacity-id' => '#'.$modelId.'backgroundOpacity',
                        ]); ?>
                    </div>

                </div>

            </div>

            <div class="creative-blocks-tools-item border-tool">

                <div class="border-width-tool">
                    <?php if($model->getFormat() == Blocks::FORMAT_MARKET){
                        $params = [
                            'class' => 'form-control flat spinner',
                            'data-market-selector' => 'div.adld-tsr-table',
                            'data-simple-selector' => '.adld-simple-tsr-cell',
                            'data-market-property' => 'border-width',
                            'data-simple-property' => 'border-width',
                        ];
                    }else{
                        $params = [
                            'class' => 'form-control flat spinner ui-spinner-input',
                            'data-market-property' => 'border-width',
                            'data-simple-property' => 'border-width',
                            'data-simple-selector' => '.adld-simple-tsr-cell',
                            'data-market-selector' => 'div.adld-tsr-table',
                            'readonly' => 'readonly',
                            'aria-valuemin' => 0,
                            'aria-valuenow' => 18,
                            'autocomplete' => 'off',
                            'role' => 'spinbutton'
                        ];
                    } ?>
                    <?php echo CHtml::activeLabel($model, 'border'); ?>
                    <?php echo CHtml::activeTextField($model, 'border' , $params); ?>
                </div>

                <div class="border-color-tool">
                    <?php echo CHtml::activeLabel($model, 'borderColor'); ?>

                    <div class="color-tool">
                        <span>#</span>
                        <?php echo CHtml::activeTextField($model, 'borderColor' , [
                            'class' => 'form-control flat color-value',
                            'data-market-selector' => '.adld-tsr-table',
                            'data-simple-selector' => '.adld-simple-tsr-cell',
                            'data-market-property' => 'border-color',
                            'data-simple-property' => 'border-color',
                            'data-opacity-id' => '#'.$modelId.'borderOpacity',
                        ]); ?>
                    </div>

                </div>

            </div>

            <div class="creative-blocks-tools-item btn-group">

                <div>
                    <button id="block-submit-button" type="submit" class="btn btn-block adloud_btn">
                        <span class="input-icon fui-plus pull-left"></span>
                        <?php echo $model->id ? \Yii::t('block_market', 'Сохранить') : \Yii::t('block_market', 'Добавить'); ?>
                    </button>
                </div>
                <div>
                    <button
                        id="block-submit-reset"
                        type="reset"
                        class="btn btn-default btn-block auto-link"
                        data-url="<?php echo Yii::app()->createUrl('webmaster/block/list', ['id' => $model->getSiteId()]); ?>"
                    >
                        <?php echo Yii::t('block_market', \Yii::t('block_market', 'Отменить')); ?>
                        <span class="input-icon pull-left fui-cross"></span>
                    </button>
                </div>

            </div>

            <?php echo CHtml::hiddenField('allowAdult', $model->allowAdult, ['id' => 'allowSms_input']); ?>
            <?php echo CHtml::hiddenField('allowShock', $model->allowShock, ['id' => 'allowShock_input']); ?>
            <?php echo CHtml::hiddenField('allowSms', $model->allowSms, ['id' => 'allowSms_input']); ?>
            <?php echo CHtml::activeHiddenField($model, 'format'); ?>
            <?php echo CHtml::activeHiddenField($model, 'horizontalCount'); ?>
            <?php echo CHtml::activeHiddenField($model, 'verticalCount'); ?>
            <?php echo CHtml::activeHiddenField($model, 'captionOpacity'); ?>
            <?php echo CHtml::activeHiddenField($model, 'textOpacity'); ?>
            <?php echo CHtml::activeHiddenField($model, 'buttonOpacity'); ?>
            <?php echo CHtml::activeHiddenField($model, 'backgroundOpacity'); ?>
            <?php echo CHtml::activeHiddenField($model, 'borderOpacity'); ?>

        <?php echo CHtml::endForm(); ?>
    </div>

</div>

<?php Yii::app()->clientScript->registerScript('newCreativeBlock', '
    NewCreativeBlock.init({
        blockId: '.($model->id ? $model->id : 0).',
        previewUrl: "'.Yii::app()->createUrl('block/index/getPreview', ['siteId' => $model->getSiteId()]).'",
        blockDescription: "#'.$modelId.'description",
        selectSizeId: "#'.$modelId.'size",
        horizontalCountInput: "#'.$modelId.'horizontalCount",
        verticalCountInput: "#'.$modelId.'verticalCount",
        typeInputId: "#'.$modelId.'format",
        sizeInputId: "#'.$modelId.'size",
        captionColorId: "#'.$modelId.'captionColor",
        textColorId: "#'.$modelId.'textColor",
        buttonColorId: "#'.$modelId.'buttonColor",
        backgroundColorId: "#'.$modelId.'backgroundColor",
        borderId: "#'.$modelId.'border",
        borderColorId: "#'.$modelId.'borderColor",
        borderWidthInputId: "#'.$modelId.'border",
        img: "'.$img.'adloud/",
        marketFormat: "'.Blocks::FORMAT_MARKET.'",
        captionOpacityId: "#'.$modelId.'captionOpacity",
        textOpacityId: "#'.$modelId.'textOpacity",
        buttonOpacityId: "#'.$modelId.'buttonOpacity",
        backgroundOpacityId: "#'.$modelId.'backgroundOpacity",
        borderOpacityId: "#'.$modelId.'borderOpacity"
    });
'); ?>