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


<div class="adld-simple-adld-tsr-img-cntnr">
    <img class="adld-simple-adld-tsr-img" src="<?php echo $ads->imageUrl; ?>"/>
</div>
<div class="adld-simple-tsr-txt-cntnr" style="background: none repeat scroll 0% 0% <?php echo $backgroundColor; ?>;">
	<span class="adld-simple-tsr-title" style="color: <?php echo $captionColor; ?>;">
		<?php echo $ads->caption; ?>
	</span>
	<span class="adld-simple-tsr-dscrptn" style="color: <?php echo $textColor; ?>;">
		<?php echo $ads->description; ?>
	</span>
	<span class="adld-simple-tsr-link" onClick="window.location = '<?php echo $ads->clickUrl; ?>'" style="color: <?php echo $buttonColor; ?>;">
		<?php echo $ads->buttonText; ?><i class="fa fa-angle-right"></i>
	</span>
</div>