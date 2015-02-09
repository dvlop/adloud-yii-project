<?php
/**
 * @var $this \ads\AdsStandard
 */
?>

<div class="adloud-teaser-item adloud-teaser-240x400">
    <div class="teaser_btn text-center">
        <a href="<?= $this->clickUrl  ?>" target="_blank" class="btn btn-block btn-embossed"><?= $this->buttonText ?></a>
    </div>
    <p class="teaser_title text-left">
        <span>
          <img src="<?=$this->getFavicon()?>" class="teaser_logo"/>
        </span>
        <span>
        	<a href="<?= $this->clickUrl  ?>"  target="_blank"><?= $this->caption ?></a>
        </span>
    </p>
    <div class="center-content">
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
        <div class="teaser_img pull-left">
            <img src="<?= $this->imageUrl ?>"/>
        </div></a>
        <a href="<?= $this->clickUrl  ?>"  target="_blank">
        <p class="teaser_description text-left"><?= $this->description ?></p></a>
    </div>
</div>