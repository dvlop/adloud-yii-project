<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 07.05.14
 * Time: 16:45
 * @var WebmasterController $this
 * @var BlockForm $model
 * @var int $siteId
 * @var int $id
 * @var string $blockCode
 */
?>

<?php echo CHtml::beginForm('', 'post', array('id' => 'add_teaser_block', 'class' => 'col-sm-12')); ?>

    <div id="block-hidden-fields">
        <?php echo CHtml::activeHiddenField($model, 'size', ['class' => 'size']); ?>
        <?php echo CHtml::activeHiddenField($model, 'color', ['class' => 'color']); ?>
        <?php echo CHtml::activeHiddenField($model, 'bg', ['class' => 'bg']); ?>
        <?php echo CHtml::activeHiddenField($model, 'type'); ?>
    </div>

    <div class="row">

        <fieldset class="col-sm-12">
            <legend>Предпросмотр рекламных блоков</legend>
            <div class="col-sm-12 preview_teaser_block">
                <div class="row preview-block-window preview-<?php echo $model->size; ?>">
                    <div id="block-preview"></div>
                </div>
            </div>
        </fieldset>

        <fieldset class="col-sm-12">
            <legend>Настройте блок под свой сайт</legend>
            <div class="col-sm-6">
                <div class="row">
                    <div class="form-group">
                        <?php echo CHtml::activeLabel($model, 'description', ['class' => 'adloud_label']); ?>
                        <?php echo CHtml::activeTextField($model, 'description', ['class' => 'form-control flat', 'required' => true]); ?>
                    </div>
                    <div class="form-group">
                        <label for="teaser_block_name" class="adloud_label">Выберите формат объявлений:</label>

                        <select name="teaser_block_size" class="teaser_block_size">

                            <?php foreach($model->blockSizes as $size): ?>

                                <option value="<?php echo $size->value; ?>"<?php if($size->selected) echo ' selected="selected"'; ?> data-width="<?php echo $size->width; ?>" data-height="<?php echo $size->height; ?>"><?php echo $size->name; ?></option>

                            <?php endforeach;?>

                        </select>

                    </div>
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 submit_teaser_block">
                                <button type="submit" class="btn adloud_btn btn-block">
                                    <span class="input-icon fui-plus pull-left"></span>
                                    <?php echo $id ? 'Сохранить изменения' : 'Добавить блок'; ?>
                                </button>
                            </div>
                            <div class="col-sm-6 reset_teaser_block">
                                <button type="reset" class="btn adloud_btn btn-default btn-block">
                                    <span class="input-icon fui-cross"></span>&nbsp;&nbsp;&nbsp;&nbsp;Отмена
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <div class="col-sm-12">
                            <?php echo CHtml::activeLabel($model, 'bg', ['class' => 'adloud_label']); ?>
                        </div>

                        <?php foreach($model->backgrounds as $bg): ?>

                            <div class="col-sm-4">
                                <label class="radio teaser_block_fill<?php if($bg->selected) echo ' checked'; ?>">
                                    <input type="radio" name="teaser_block_fill" value="<?php echo $bg->value; ?>" data-toggle="radio"><?php echo $bg->name; ?>
                                </label>
                            </div>

                        <?php endforeach; ?>

                    </div>
                    <div class="form-group col-sm-12 color_schemes">
                        <div class="col-sm-12">
                            <?php echo CHtml::activeLabel($model, 'color', ['class' => 'adloud_label select_color']); ?>
                        </div>

                        <?php foreach($model->colors as $color): ?>

                            <div class="col-sm-3 <?php echo $color->class; ?> scheme_item<?php if($color->selected) echo ' selected'; ?>" data-color-scheme="<?php echo $color->value; ?>">
                                <div class="col-sm-4 title_color">
                                </div>
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-4 button_bg">
                                </div>
                                <div class="col-sm-12 text-center scheme_name"><?php echo $color->name; ?></div>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </fieldset>
    </div>

<?php Yii::app()->clientScript->registerScript('blockPreview', '
    Block.init({
        baseUrl: "'.Yii::app()->theme->baseUrl.'/assets/images'.'",
        previewUrl: "'.$this->createAbsoluteUrl('/webmaster/block/getPreview', ['id' => $id, 'siteId' => $siteId]).'",
        returnUrl: "'.$this->createAbsoluteUrl('/webmaster/block/list', ['id' => $siteId]).'"
    });
');
