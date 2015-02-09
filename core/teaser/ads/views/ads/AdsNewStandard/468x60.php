<?php
/**
 * @var $this \ads\AdsStandard
 */
?>
<div class="adloud-teaser-item adloud-teaser-468x60"><!--start item teaser-->

    <div class="teaser_btn text-center">
        <a href="<?= $this->clickUrl  ?>" target="_blank" class="btn btn-block btn-embossed"><?= $this->buttonText ?></a>
    </div>
    <div class="teaser_img pull-left">
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
        <img src="<?= $this->imageUrl  ?>"/></a>
    </div>
    <p class="teaser_title text-left">
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
        <img src="<?= $this->getFavicon()  ?>" class="teaser_logo"/></a>
        <a href="<?= $this->clickUrl  ?>"  target="_blank"><?= $this->caption ?></a>
    </p>
    <a href="<?= $this->clickUrl  ?>"  target="_blank">
    <p class="teaser_description text-left"><?= $this->description  ?></p></a>

</div>