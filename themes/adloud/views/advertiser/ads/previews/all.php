<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 25.06.14
 * Time: 16:28
 * @var ControllerBase $this
 * @var string $title
 * @var string $desk
 * @var string $buttonText
 * @var string $url
 * @var string $urlText
 * @var bool $showButton
 * @var string $img
 */
?>

<div class="tab-pane active" id="all">

    <p class="preview_variant">300px</p>
    <div class="big teaser_preview_item">
        <p class="preview_title pull-right"><?php echo $title; ?></p>
        <p class="preview_description pull-right"<?php echo $desk; ?>></p>
        <a href="<?php echo $url; ?>" class="btn btn-lg btn-block btn-embossed preview_btn pull-right"<?php if(!$showButton) echo ' style="display: none;"'; ?>><?php echo $buttonText; ?></a>
        <div class="image-preview thumbnail" data-trigger="fileinput" style="width:110px;height:110px;">
            <?php echo $img; ?>
        </div>
    </div>

    <p class="preview_variant">240px (Вариант 1)</p>
    <div class="medium teaser_preview_item horizont">
        <p class="preview_title"><?php echo $title; ?></p>
        <p class="preview_description pull-right"><?php echo $desk; ?></p>
        <a href="<?php echo $url; ?>" class="btn btn-lg btn-block btn-embossed preview_btn"<?php if(!$showButton) echo ' style="display: none;"'; ?>><?php echo $buttonText; ?></a>
        <div class="image-preview thumbnail" data-trigger="fileinput" style="width:100px;height:100px;">
            <?php echo $img; ?>
        </div>
    </div>

    <p class="preview_variant">240px (Вариант 2)</p>
    <div class=" medium teaser_preview_item vertical">
        <p class="preview_title"><?php echo $title; ?></p>
        <a href="<?php echo $url; ?>" class="preview_txt_url"></a>
        <div class="image-preview thumbnail" data-trigger="fileinput" style="width:230px;height:230px;">
            <?php echo $img; ?>
        </div>
        <p class="preview_description"><?php echo $desk; ?></p>
        <a href="<?php echo $url; ?>" class="btn btn-lg btn-block btn-embossed preview_btn"<?php if(!$showButton) echo ' style="display: none;"'; ?>><?php echo $buttonText; ?></a>
    </div>

    <p class="preview_variant">160px</p>
    <div class=" small teaser_preview_item">
        <p class="preview_title"><?php echo $title; ?></p>
        <p class="preview_txt_url"></p>
        <div class="image-preview thumbnail" data-trigger="fileinput" style="width:150px;height:150px;">
            <?php echo $img; ?>
        </div>
        <p class="preview_description"><?php echo $desk; ?></p>
        <a href="<?php echo $url; ?>" class="btn btn-lg btn-block btn-embossed preview_btn"<?php if(!$showButton) echo ' style="display: none;"'; ?>><?php echo $buttonText; ?></a>
    </div>

</div>