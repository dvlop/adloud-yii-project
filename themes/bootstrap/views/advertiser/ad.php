<?php

?>

<div data-type="block">
    <div class="teaser-element teaser-image" data-type="image">
        <img src="<?php echo $ad->adsContent->imageUrl;?>" style="width: 100px;">
    </div>
    <div class="teaser-element teaser-header" data-type="header">
        <a href="#"><?php echo $ad->adsContent->urlText;?></a>
    </div>
<!--    <div class="teaser-element teaser-url" data-type="url">-->
<!--        --><?php //echo $ad->text;?>
<!--    </div>-->
    <div class="teaser-element teaser-description" data-type="description">
        <?php echo $ad->adsContent->url;?>
    </div>
</div>