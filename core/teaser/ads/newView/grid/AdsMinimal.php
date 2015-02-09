<?php
/**
 * @var \ads\AdsAbstract[] $ads
 * @var string $width
 * @var string $height
 * @var string $hCount
 * @var string $vCount
 * @var string $border
 * @var string $borderColor
 * @var string $backgroundColor
 */
?>

<div class="adld-tsr-table" style="width: <?php echo $hCount*$width; ?>px; height: <?php echo $vCount*$height; ?>px; background: none repeat scroll 0% 0% #<?php echo $backgroundColor; ?>; border-color: #<?php echo $borderColor; ?>; border-width: <?php echo $border; ?>px;">

    <?php if($vCount == 1): ?>
        <?php foreach($ads as $ad): ?>
            <?php echo $ad['html']; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php $i = 0; ?>
        <?php for($v = 0; $v < $vCount; $v++): ?>
            <div class="adld-tsr-row">
                <?php for($h = 0; $h < $hCount; $h++): ?>
                    <?php if(isset($ads[$i])): ?>
                        <?php echo $ads[$i++]['html']; ?>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>
    <?php endif; ?>

</div>

<div class="adloud-load-check" style="display: none;"></div>