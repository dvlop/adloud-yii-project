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

<div class="tab-pane active" id="160x600">
    <div class=" small teaser_preview_item">
        <p class="preview_title"><?php echo $title; ?></p>
        <a href="<?php echo $url; ?>" class="preview_txt_url"><?php echo $urlText; ?></a>
        <div class="image-preview thumbnail" data-trigger="fileinput" style="width:150px;height:150px;">
            <?php echo $img; ?>
        </div>
        <p class="preview_description"><?php echo $desk; ?></p>
        <a href="<?php echo $url; ?>" class="btn btn-lg btn-block btn-embossed preview_btn"<?php if(!$showButton) echo ' style="display: none;"'; ?>><?php echo $buttonText; ?></a>
    </div>
</div>