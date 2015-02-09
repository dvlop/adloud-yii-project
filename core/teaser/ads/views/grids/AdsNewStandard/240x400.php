<?php
/**
 * @var $this \ads\AdsRenderer
 */
?>

<div class="adloud-teaser-block adloud-block-240x400 adloud-block-240x400-new">
    <?php foreach($this->ads as $ads):?>
        <?= $ads->render(); ?>
    <?php endforeach?>
</div>
<div class="adloud-load-check"></div>