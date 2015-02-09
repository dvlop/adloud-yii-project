<?php
/**
 * @var $this \ads\AdsRenderer
 */
?>


<div class="adloud-teaser-block adloud-block-728x90"><!--start teaser block-->

    <a target=__blank href="<?= \config\Config::getInstance()->getHomeUrl()?>" class="adloud_logo"></a>
    <a href="#" class="refresh">
        <span class="glyphicon glyphicon-repeat"></span>
    </a>
    <?php foreach($this->ads as $ads):?>
        <?= $ads->render(); ?>
    <?php endforeach?>
</div>
<div class="adloud-load-check"></div>