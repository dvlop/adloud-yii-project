<?php
/**
 * @var $this \ads\AdsStandard
 */
?>

<div class="adloud-teaser-item adloud-teaser-240x400">
    <a class="teaser-adld-global-link" href="<?= $this->clickUrl  ?>" target="_blank">
        <div class="teaser_btn transparent_btn">
            <span>
                <?= $this->buttonText ?>
            </span>
        </div>
        <p class="teaser_title text-left">
            <span>
                <?= $this->caption ?>
            </span>
        </p>
        <div class="center-content">
            <div class="teaser_img pull-left">
                <img src="<?= $this->imageUrl ?>"/>
            </div>
            <p class="teaser_description text-left">
                <?= $this->description ?>
            </p>
        </div>
    </a>
</div>