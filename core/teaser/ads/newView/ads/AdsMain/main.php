<?php
/**
 * @var \ads\AdsAbstract $ads
 * @var string $size
 * @var string $width
 * @var string $captionColor
 * @var string $buttonColor
 * @var string $limitedText
 */
?>

<td class="adld-tsr-cell">
    <a target="_blank" href="<?php echo $ads->clickUrl; ?>" class="adld-global-link">

        <span class="adloud-tsr-item">
            <img src="<?php echo $ads->imageUrl; ?>" class="adld-tsr-img">
        </span>

        <span class="adld-tsr-title">
            <?php echo $ads->caption; ?>
        </span>

        <input type="hidden" name="ads-description" value="<?php echo $ads->description; ?>" />
        <span class="adld-tsr-description">
            <?php echo $limitedText; ?>
        </span>

    </a>
</td>