<?php
/**
 * @var $this \ads\AdsStandard
 */
?>
<div class="adloud-teaser-item adloud-teaser-240x600">
    <a class="teaser-adld-global-link" href="<?= $this->clickUrl  ?>">
        <div class="teaser_btn text-center">
            <span class="btn btn-block btn-embossed">
                <?= $this->buttonText ?>
            </span>
        </div>
        <p class="teaser_title text-center">
            <?= $this->caption ?>
        </p>
        <div class="teaser_link text-center">
            <span>
                <img src="<?= $this->getFavicon()  ?>" class="teaser_logo"/>
                <?= $this->showUrl ?>
            </span>
        </div>
        <div class="center-content">
            <div class="teaser_img">
                <img src="<?= $this->imageUrl ?>"/>
            </div>
            <p class="teaser_description text-center"><?= $this->description ?></p>
        </div>
    </a>
</div>