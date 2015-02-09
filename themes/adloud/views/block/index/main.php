<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 22.08.14
 * Time: 14:33
 * @var \application\modules\block\controllers\IndexController $this
 * @var \application\models\Blocks $model
 * @var string $url
 * @var integer $siteId
 * @var string $buttonName
 */
?>

<?php
    use application\models\Blocks;

    $img = Yii::app()->theme->baseUrl.'/assets/images/';
    $modelName = $model->getModelName();
    $modelId = $modelName.'_';
?>

<div class="col-sm-12 crtv-blocks-preview">

    <!-- <div class="crtv-blocks-preview-bg crtv-blocks-preview-left-bg pull-left"></div>
    <div class="crtv-blocks-preview-bg crtv-blocks-preview-top-bg pull-right"></div> -->

    <div id="main-block-preview" class="crtv-blocks-tsr-container">

    </div>

</div>

<div class="crtv-blocks-toggle-btn">
    <button class="toggle-crtv-blocks-panel" type="button"><i class="fa fa-chevron-down"></i></button>
</div>

<div class="crtv-blocks-panel">

    <form id="main-CB-form" method="post">

        <div class="crtv-blocks-panel-container">

            <div class="crtv-blocks-logo-container text-center pull-left">
                <div class="crtv-blocks-logo">
                    <img src="<?php echo $img; ?>/adloud/crtv-blocks-logo.png">
                </div>
                <div class="crtv-blocks-adld-logo">
                    <img src="<?php echo $img; ?>/adloud/crtv-blocks-adld-logo.png">
                </div>
            </div>

            <div class="crtv-blocks-tools pull-left">

                <div class="crtv-blocks-tools-type pull-left">
                    <button class="active btn crtv-blocks-tools-type-btn pull-left" data-toolstype=".crtv-blocks-table-tools" type="button"><?php echo Yii::t('block_market', 'Настройки блока'); ?></button>
                    <button class="btn crtv-blocks-tools-type-btn pull-left" data-toolstype=".crtv-blocks-borderspadding-tools" type="button"><?php echo Yii::t('block_market', 'Границы и отступы'); ?></button>
                    <button class="btn crtv-blocks-tools-type-btn pull-left" data-toolstype=".crtv-blocks-tsr-tools" type="button"><?php echo Yii::t('block_market', 'Настройки тизера'); ?></button>
                    <button class="btn crtv-blocks-tools-type-btn pull-left" data-toolstype=".crtv-blocks-txt-tools" type="button"><?php echo Yii::t('block_market', 'Форматирование текста'); ?></button>
                </div>

                <div class="crtv-blocks-tools-type-item crtv-blocks-table-tools show pull-left">

                    <div class="adld-tsr-tbl-name-tool crtv-blocks-table-tools-item crtv-blocks-tools-item pull-left">
                        <?php echo CHtml::activeLabel($model, 'caption'); ?>
                        <?php echo CHtml::activeTextField($model, 'caption', ['class' => 'form-control flat']); ?>
                    </div>

                    <div class="adld-tsr-tbl-width-tool crtv-blocks-table-tools-item crtv-blocks-tools-item pull-left">
                        <?php echo CHtml::activeLabel($model, 'width'); ?>
                        <?php echo CHtml::activeTextField($model, 'width', ['class' => 'form-control flat pull-left']); ?>

                        <button class="btn adld-tsr-tbl-width-units pull-left<?php if($model->getWidthStyle() == 'px') echo ' active'; ?>" data-units="px" type="button">px</button>
                        <button class="btn adld-tsr-tbl-width-units pull-left<?php if($model->getWidthStyle() == '%') echo ' active'; ?>" data-units="%" type="button">%</button>

                        <div class="crtv-blocks-slider adld-tsr-tbl-width-slider"></div>
                    </div>

                    <div class="adld-tsr-tbl-number-tool crtv-blocks-table-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'horizontalCount', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'horizontalCount', ['class' => 'form-control flat slider-input pull-left']); ?>
                        <div class="crtv-blocks-slider adld-tsr-tbl-hor-number-slider pull-left"></div>

                        <?php echo CHtml::activeLabel($model, 'verticalCount', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'verticalCount', ['class' => 'form-control flat slider-input pull-left']); ?>
                        <div class="crtv-blocks-slider adld-tsr-tbl-vert-number-slider pull-left"></div>

                    </div>

                    <div class="adld-tsr-tbl-bg-tool crtv-blocks-table-tools-item crtv-blocks-tools-item pull-left">

                        <label for="adld-tsr-tbl-bg"><?php echo Yii::t('block_market', 'Цвет фона блока:'); ?></label>

                        <?php echo CHtml::activeTextField($model, 'backgroundColor' , [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-tbl',
                            'data-property' => 'background-color',
                            'data-opacity' => 'backgroundOpacity',
                            'data-colorpicker' => 'backgroundColor',
                            'maxlength' => 7,
                            'size' => 7
                        ]); ?>

                        <span id="backgroundColorId" class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                    </div>

                </div>

                <div class="crtv-blocks-tools-type-item crtv-blocks-borderspadding-tools hide pull-left">

                    <div class="adld-tsr-tbl-border-tool crtv-blocks-borderspadding-tools-item crtv-blocks-tools-item pull-left">

                        <label for="adld-tsr-tbl-bordercolor"><?php echo Yii::t('block_market', 'Цвет границы блока:'); ?></label>

                        <?php echo CHtml::activeTextField($model, 'borderColor' , [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-tbl',
                            'data-property' => 'border-color',
                            'data-opacity' => 'borderOpacity',
                            'data-colorpicker' => 'borderColor',
                            'maxlength' => 7,
                            'size' => 7
                        ]); ?>

                        <span id="borderColorId" class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                        <label class="pull-left" for="adld-tsr-tbl-border-width"><?php echo Yii::t('block_market', 'Размер границы блока:'); ?></label>

                        <?php echo CHtml::activeTextField($model, 'border' , [
                            'class' => 'form-control flat pull-left adld-tsr-tbl-border-width',
                            'data-selector' => '.adld-tsr-tbl',
                            'data-property' => 'border-width',
                            'maxlength' => 2,
                        ]); ?>

                        <span class="adld-tsr-tbl-border-width-units">px</span>

                    </div>

                    <div class="adld-tsr-tbl-borderstyle-tool crtv-blocks-borderspadding-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'borderType'); ?>

                        <?php foreach($model->getBorderTypes() as $type): ?>
                            <label class="radio">
                                <input
                                    type="radio"
                                    name="<?php echo $modelName.'[borderType]'; ?>"
                                    data-toggle="radio"
                                    value="<?php echo $type->value; ?>"
                                    data-selector=".adld-tsr-tbl"
                                    data-property="border-style"
                                    <?php if($type->checked) echo 'checked="checked"'; ?>
                                /><?php echo Yii::t('block_market', $type->name); ?>
                            </label>
                        <?php endforeach; ?>

                    </div>

                    <div class="adld-tsr-cell-padding-tool crtv-blocks-borderspadding-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'indentAds', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'indentAds', [
                            'class' => 'form-control flat slider-input pull-left',
                            'data-selector' => '.adld-tsr-cell',
                            'data-property' => 'border-spacing',
                        ]); ?>
                        <div class="crtv-blocks-slider adld-tsr-cell-padding-slider pull-left"></div>

                        <?php echo CHtml::activeLabel($model, 'indentBorder', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'indentBorder', ['class' => 'form-control flat slider-input pull-left']); ?>
                        <div class="crtv-blocks-slider adld-tsr-cell-inner-padding-slider pull-left"></div>

                    </div>

                    <div class="adld-tsr-cell-border-tool crtv-blocks-borderspadding-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'adsBorderColor'); ?>
                        <?php echo CHtml::activeTextField($model, 'adsBorderColor', [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-cell',
                            'data-property' => 'border-color',
                            'data-opacity' => 'adsBorderOpacity',
                            'data-colorpicker' => 'adsBorderColor',
                            'maxlength' => 7,
                            'size' => 7,
                        ]); ?>

                        <span id="adsBorderColorId" class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                        <?php echo CHtml::activeLabel($model, 'adsBorder', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'adsBorder', [
                            'class' => 'form-control flat pull-left adld-tsr-cell-border-width',
                            'data-selector' => '.adld-tsr-cell',
                            'data-property' => 'border-width',
                            'maxlength' => 2,
                        ]); ?>
                        <span class="adld-tsr-tbl-border-width-units">px</span>

                    </div>

                    <div class="adld-tsr-cell-borderstyle-tool crtv-blocks-borderspadding-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'adsBorderType'); ?>
                        <?php foreach($model->getAdsBorderTypes() as $type): ?>
                            <label class="radio">
                                <input
                                    type="radio"
                                    name="<?php echo $modelName ?>[adsBorderType]"
                                    data-toggle="radio"
                                    value="<?php echo $type->value ?>"
                                    data-selector=".adld-tsr-cell"
                                    data-property="border-style"
                                    <?php if($type->checked) echo 'checked="checked"'; ?>
                                /><?php echo Yii::t('block_market', $type->name); ?>
                            </label>
                        <?php endforeach; ?>

                    </div>

                </div>

                <div class="crtv-blocks-tools-type-item crtv-blocks-tsr-tools hide pull-left">

                    <div class="adld-tsr-bg-tool crtv-blocks-tsr-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'adsBackColor'); ?>
                        <?php echo CHtml::activeTextField($model, 'adsBackColor', [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-cell',
                            'data-property' => 'background',
                            'data-opacity' => 'adsBackOpacity',
                            'data-colorpicker' => 'adsBackColor',
                            'maxlength' => 7,
                            'size' => 7,
                        ]); ?>

                        <span class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                        <?php echo CHtml::activeLabel($model, 'backHoverColor', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'backHoverColor', [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-cell:hover',
                            'data-property' => 'background',
                            'data-opacity' => 'backHoverOpacity',
                            'data-colorpicker' => 'backHoverColor',
                            'data-hover' => 1,
                            'maxlength' => 7,
                            'size' => 7,
                        ]); ?>

                        <span class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                    </div>

                    <div class="adld-adld-tsr-img-width-tool crtv-blocks-tsr-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'imgWidth', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'imgWidth', ['class' => 'form-control flat slider-input pull-left']); ?>
                        <span>px</span>
                        <div class="crtv-blocks-slider adld-adld-tsr-img-width-slider pull-left"></div>

                        <?php echo CHtml::activeLabel($model, 'borderRadius', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'borderRadius', ['class' => 'form-control flat slider-input pull-left']); ?>
                        <span>%</span>
                        <div class="crtv-blocks-slider adld-adld-tsr-img-border-radius-slider pull-left"></div>

                    </div>

                    <div class="adld-tsr-align-tool crtv-blocks-tsr-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'alignment'); ?>
                        <?php foreach($model->getAlignments() as $ali): ?>
                            <label class="radio">
                                <input
                                    type="radio"
                                    name="<?php echo $modelName; ?>[alignment]"
                                    data-toggle="radio"
                                    value="<?php echo $ali->value; ?>"
                                    data-selector=".adld-tsr-cell"
                                    data-property="text-align"
                                    <?php if($ali->checked) echo 'checked="checked"'; ?>
                                    /><?php echo Yii::t('block_market', $ali->name); ?>
                            </label>
                        <?php endforeach; ?>

                    </div>

                    <div class="adld-adld-tsr-img-border-tool crtv-blocks-tsr-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'imgBorderColor'); ?>
                        <?php echo CHtml::activeTextField($model, 'imgBorderColor', [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-img',
                            'data-property' => 'border-color',
                            'data-opacity' => 'imgBorderOpacity',
                            'data-colorpicker' => 'imgBorderColor',
                            'maxlength' => 7,
                            'size' => 7,
                        ]); ?>

                        <span class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                        <?php echo CHtml::activeLabel($model, 'imgBorderWidth', ['class' => 'pull-left']); ?>
                        <?php echo CHtml::activeTextField($model, 'imgBorderWidth', [
                            'class' => 'form-control flat pull-left adld-adld-tsr-img-border-width',
                            'data-selector' => '.adld-tsr-img',
                            'data-property' => 'border-width',
                            'maxlength' => 2,
                        ]); ?>
                        <span class="adld-tsr-tbl-border-width-units">px</span>

                    </div>

                    <div class="adld-adld-tsr-img-borderstyle-tool crtv-blocks-tsr-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'imgBorderType'); ?>
                        <?php foreach($model->getImgBorderTypes() as $type): ?>
                            <label class="radio">
                                <input
                                    type="radio"
                                    name="<?php echo $modelName; ?>[imgBorderType]"
                                    data-toggle="radio"
                                    value="<?php echo $type->value; ?>"
                                    data-selector=".adld-tsr-img"
                                    data-property="border-style"
                                    <?php if($type->checked) echo 'checked="checked"'; ?>
                                    /><?php echo Yii::t('block_market', $type->name); ?>
                            </label>
                        <?php endforeach; ?>

                    </div>

                </div>

                <div class="crtv-blocks-tools-type-item crtv-blocks-txt-tools hide pull-left">

                    <div class="adld-tsr-fontfamily-tool crtv-blocks-txt-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'font'); ?>
                        <select name="<?php echo $modelName; ?>[font]" class="adld-tsr-fontfamily" data-selector=".adld-tsr-tbl .adld-tsr-title, .adld-tsr-tbl .adld-tsr-description" data-attribute="font-family">
                            <?php foreach($model->getFonts() as $font): ?>
                                <option
                                    value="<?php echo $font->value; ?>"
                                    data-value="<?php echo $font->value; ?>"
                                    <?php if($font->checked) echo 'selected = selected'; ?>
                                >
                                    <?php echo $font->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="checkbox use-description">
                            <input
                                id="<?php echo $modelId; ?>useDescription"
                                type="checkbox"
                                name="<?php echo $modelName; ?>[useDescription]"
                                value="1"
                                data-toggle="checkbox"
                                data-selector=".adld-tsr-description"
                                <?php if($model->getUseDescription()) echo 'checked="checked"'; ?>
                            />
                            <?php echo $model->getAttributeLabel('useDescription'); ?>
                        </label>

                    </div>

                    <div class="adld-tsr-txt-pos-tool crtv-blocks-txt-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'textPosition'); ?>
                        <?php foreach($model->getTextPositions() as $pos): ?>
                            <label class="radio">
                                <input
                                    type="radio"
                                    name="<?php echo $modelName; ?>[textPos]"
                                    data-toggle="radio"
                                    value="<?php echo $pos->value; ?>"
                                    data-selector="<?php echo $pos->selector; ?>"
                                    data-property="<?php echo $pos->property; ?>"
                                    data-selector2="<?php echo $pos->selector2; ?>"
                                    data-property2="<?php echo $pos->property2; ?>"
                                    data-value2="<?php echo $pos->value2; ?>"
                                    <?php if($pos->checked) echo 'checked="checked"'; ?>
                                /><?php echo Yii::t('block_market', $pos->name); ?>
                            </label>
                        <?php endforeach; ?>

                    </div>

                    <div class="adld-tsr-title-tool crtv-blocks-txt-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'captionColor'); ?>
                        <?php echo CHtml::activeTextField($model, 'captionColor', [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-title',
                            'data-property' => 'color',
                            'data-opacity' => 'captionOpacity',
                            'data-colorpicker' => 'captionColor',
                            'maxlength' => 7,
                            'size' => 7,
                        ]); ?>

                        <span class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                        <div class="pull-left font-size">

                            <?php echo CHtml::activeLabel($model, 'captionFontSize', ['class' => 'pull-left']); ?>
                            <?php echo CHtml::activeTextField($model, 'captionFontSize', [
                                'class' => 'form-control flat pull-left adld-tsr-title-font-size',
                                'data-selector' => '.adld-tsr-title',
                                'data-property' => 'font-size',
                                'maxlength' => 2,
                            ]); ?>
                            <span class="adld-tsr-tbl-border-width-units">px</span>

                        </div>

                        <div class="pull-left txt-style">
                            <?php echo CHtml::activeLabel($model, 'captionStyle'); ?>
                            <button class="btn txt-style-weight" data-input="captionStyle" data-name="B" type="button" data-selector=".adld-tsr-title" data-property="font-weight" data-value="700">B</button>
                            <button class="btn txt-style-decoration" data-input="captionStyle" data-name="U" type="button" data-selector=".adld-tsr-title" data-property="text-decoration" data-value="underline">U</button>
                        </div>

                    </div>

                    <div class="adld-tsr-titlehover-tool crtv-blocks-txt-tools-item crtv-blocks-tools-item pull-left">

                        <?php echo CHtml::activeLabel($model, 'captionHoverColor'); ?>
                        <?php echo CHtml::activeTextField($model, 'captionHoverColor', [
                            'class' => 'form-control flat colorpicker-current-color-value pull-left',
                            'data-selector' => '.adld-tsr-title:hover',
                            'data-property' => 'color',
                            'data-opacity' => 'captionHoverOpacity',
                            'data-colorpicker' => 'captionHoverColor',
                            'data-hover' => 1,
                            'maxlength' => 7,
                            'size' => 7,
                        ]); ?>

                        <span class="crtv-blocks-colorpicker-btn pull-left">
                            <span class="colorpicker-current-color">
                                <span></span>
                                <span></span>
                            </span>
                        </span>

                        <div class="pull-left font-size">

                            <?php echo CHtml::activeLabel($model, 'captionHoverFontSize', ['class' => 'pull-left']); ?>
                            <?php echo CHtml::activeTextField($model, 'captionHoverFontSize', [
                                'class' => 'form-control flat pull-left adld-tsr-title-font-size',
                                'data-selector' => '.adld-tsr-title:hover',
                                'data-property' => 'font-size',
                                'data-hover' => 1,
                                'maxlength' => 2,
                            ]); ?>
                            <span class="adld-tsr-tbl-border-width-units">px</span>

                        </div>

                        <div class="pull-left txt-style">

                            <?php echo CHtml::activeLabel($model, 'captionHoverStyle'); ?>
                            <button
                                class="btn txt-style-weight"
                                data-hover="400"
                                data-input="captionHoverStyle"
                                data-name="B"
                                type="button"
                                data-selector=".adld-tsr-title:hover"
                                data-property="font-weight"
                                data-value="700"
                            >B</button>
                            <button
                                class="btn txt-style-decoration"
                                data-hover="none"
                                data-input="captionHoverStyle"
                                data-name="U"
                                type="button"
                                data-selector=".adld-tsr-title:hover"
                                data-property="text-decoration"
                                data-value="underline"
                            >U</button>

                        </div>

                    </div>

                    <div class="adld-adld-tsr-description-tool crtv-blocks-txt-tools-item crtv-blocks-tools-item pull-left">

                        <div class="pull-left">
                            <?php echo CHtml::activeLabel($model, 'textColor'); ?>
                            <?php echo CHtml::activeTextField($model, 'textColor', [
                                'class' => 'form-control flat colorpicker-current-color-value pull-left',
                                'data-selector' => '.adld-tsr-description',
                                'data-property' => 'color',
                                'data-opacity' => 'textOpacity',
                                'data-colorpicker' => 'textColor',
                                'maxlength' => 7,
                                'size' => 7,
                            ]); ?>

                            <span class="crtv-blocks-colorpicker-btn pull-left">
                                <span class="colorpicker-current-color">
                                    <span></span>
                                    <span></span>
                                </span>
                            </span>
                        </div>

                        <div class="pull-right">
                            <?php echo CHtml::activeLabel($model, 'descLimit'); ?>
                            <?php echo CHtml::activeTextField($model, 'descLimit', ['maxlength' => 2]); ?>
                        </div>

                        <div class="pull-left font-size">
                            <?php echo CHtml::activeLabel($model, 'descFontSize', ['class' => 'pull-left']); ?>
                            <?php echo CHtml::activeTextField($model, 'descFontSize', [
                                'class' => 'form-control flat pull-left adld-tsr-title-font-size',
                                'data-selector' => '.adld-tsr-description',
                                'data-property' => 'font-size',
                                'maxlength' => 2,
                            ]); ?>
                            <span class="adld-tsr-tbl-border-width-units">px</span>
                        </div>

                        <div class="pull-left txt-style">
                            <?php echo CHtml::activeLabel($model, 'descStyle'); ?>
                            <button
                                class="btn txt-style-weight"
                                data-input="descStyle"
                                data-name="B"
                                type="button"
                                data-selector=".adld-tsr-description"
                                data-property="font-weight"
                                data-value="700"
                            >
                                B
                            </button>
                            <button
                                class="btn txt-style-decoration"
                                data-input="descStyle"
                                data-name="U"
                                type="button"
                                data-selector=".adld-tsr-description"
                                data-property="text-decoration"
                                data-value="underline"
                            >
                                U
                            </button>
                        </div>

                    </div>

                </div>

            </div>

            <div class="crtv-blocks-btn pull-left">

                <div class="crtv-blocks-submit-btn">
                    <button class="submit-crtv-blocks-tools btn adloud_btn btn-embossed btn-block" type="submit"><span class="fui-check"></span><?php echo $buttonName; ?></button>
                </div>

                <div class="crtv-blocks-reset-btn">
                    <button class="reset-crtv-blocks-tools btn btn-default btn-embossed btn-block auto-link" data-url="<?php echo Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId]) ?>" type="reset"><span class="fui-cross"></span><?php echo Yii::t('block_market', 'Выйти'); ?></button>
                </div>

            </div>

        </div>

        <div class="crtv-blocks-colorpicker">
            <label class="checkbox colorpicker-opacity">
                <input type="checkbox" value="" id="pickeropacity" data-toggle="checkbox">
                <?php echo Yii::t('block_market', 'Прозрачность'); ?>
            </label>
            <div id="colorpicker"></div>
        </div>

        <?php echo CHtml::activeHiddenField($model, 'backgroundOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'borderOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'adsBorderOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'adsBackOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'backHoverOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'imgBorderOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'captionHoverOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'captionOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'textOpacity'); ?>
        <?php echo CHtml::activeHiddenField($model, 'widthStyle'); ?>
        <?php echo CHtml::activeHiddenField($model, 'captionStyle'); ?>
        <?php echo CHtml::activeHiddenField($model, 'captionHoverStyle'); ?>
        <?php echo CHtml::activeHiddenField($model, 'descStyle'); ?>
        <input
            type="hidden"
            name="<?php echo $modelName; ?>[textPosition]"
            id="<?php echo $modelId; ?>textPosition"
            value="<?php echo $model->getTextPosition(); ?>"
        />

    </form>

</div>

<?php Yii::app()->clientScript->registerScript('main-creative-blocks', '
    MainBlock.init({
        '.$model->getSerializedMainFormIds().',
        id: "'.$model->id.'",
        format: "'.$model->getFormat().'",
        previewUrl: "'.Yii::app()->createUrl('block/index/getPreview', ['siteId' => $model->getSiteId()]).'",
        modelName: "'.$modelName.'"
    });
'); ?>
