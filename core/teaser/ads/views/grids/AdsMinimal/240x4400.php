<?php
/**
 * @var $this \ads\AdsRenderer
 */
?>
<div class="adloud-teaser-block adloud-block-240x400 adloud-block-240x400-min">
    <?php foreach($this->ads as $ads):?>
        <?= $ads->render(); ?>
    <?php endforeach?>
</div>
<div class="adloud-load-check"></div>