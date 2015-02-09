<?php
/**
 * @var $this \ads\AdsStandard
 */
?>
<div class="adloud-teaser-item adloud-teaser-240x600">
    <div class="teaser_btn text-center">
        <a href="<?= $this->clickUrl  ?>" target="_blank" class="btn btn-block btn-embossed"><?= $this->buttonText ?></a>
    </div>
    <p class="teaser_title text-center"><a href="<?= $this->clickUrl  ?>"  target="_blank"><?= $this->caption ?></a></p>
    <div class="teaser_link text-center">
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
            <img src="<?= $this->getFavicon()  ?>" class="teaser_logo"/>
            <?= $this->showUrl ?>
        </a>
    </div>
    <div class="center-content">
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
            <div class="teaser_img">
                <img src="<?= $this->imageUrl ?>"/>
            </div>
        </a>
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
            <p class="teaser_description text-center"><?= $this->description ?></p>
        </a>
    </div>
</div>