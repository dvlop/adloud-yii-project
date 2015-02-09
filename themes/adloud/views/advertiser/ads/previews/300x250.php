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
 * @var bool
 * @var string $img
 * @var bool $showButton
 */
?>

<div class="tab-pane active" id="300x250">
    <div class="big teaser_preview_item">
        <p class="preview_title pull-right"><?php echo $title; ?></p>
        <p class="preview_description pull-right"><?php echo $desk; ?></p>
        <a href="<?php echo $url; ?>" class="btn btn-lg btn-block btn-embossed preview_btn pull-right"<?php if(!$showButton) echo ' style="display: none;"'; ?>><?php echo $buttonText; ?></a>
        <div class="image-preview thumbnail" data-trigger="fileinput" style="width:110px;height:110px;">
            <?php echo $img; ?>
        </div>
    </div>
</div>