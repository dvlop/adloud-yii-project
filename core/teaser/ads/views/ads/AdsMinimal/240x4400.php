<?php
/**
 * @var $this \ads\AdsStandard
 */
?>

<div class="adloud-teaser-item adloud-teaser-240x400">
    <div class="teaser_btn transparent_btn">
        <a href="<?= $this->clickUrl  ?>"><?= $this->buttonText ?></a>
    </div>
    <p class="teaser_title text-left">
        <span>
            <a href="<?= $this->clickUrl  ?>"  target="_blank">
                <?= $this->caption ?>
            </a>
        </span>
    </p>
    <div class="center-content">
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
            <div class="teaser_img pull-left">
                <img src="<?= $this->imageUrl ?>"/>
            </div>
        </a>
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
            <p class="teaser_description text-left">
                <?= $this->description ?>
            </p>
        </a>
    </div>
</div>