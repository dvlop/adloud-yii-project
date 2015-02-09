<?php
/**
 * @var \ads\AdsAbstract $ads
 * @var string $size
 * @var string $captionColor
 * @var string $textColor
 * @var string $buttonColor
 * @var string $backgroundColor
 */
?>

<a target="_blank" class="adld-tsr-cell adld-tsr-<?php echo $size; ?>">

    <div class="adld-tsr-title">
        <span class="adld-tsr-icon">
            <img src="<?php echo $ads->imageUrl; ?>"/>
        </span>
        <span style="color: #<?php echo $captionColor; ?>;">
            <?php echo $ads->caption; ?>
        </span>
    </div>

    <div class="adld-tsr-content">

        <div class="adld-adld-tsr-img">
            <img src="<?php echo $ads->imageUrl; ?>"/>
        </div>

        <div class="adld-adld-tsr-description">

            <span style="color: #<?php echo $textColor; ?>;">
                <?php echo $ads->description; ?>
            </span>

            <div class="adld-tsr-btn">
                <span class="btn" onClick="window.location = '<?php echo $ads->clickUrl; ?>'"  style="background: none repeat scroll 0% 0% #<?php echo $buttonColor; ?>;">
                    <?php echo $ads->buttonText; ?>
                </span>
            </div>
        </div>

    </div>

</a>
