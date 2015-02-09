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
 * @var string $size
 * @var boolean $backgroundOpacity
 * @var boolean $borderOpacity
 */
?>


<div id="teasers-container" class="adld-tsr-table" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; background: none repeat scroll 0% 0% <?php echo $backgroundColor; ?>; border-color: <?php echo $borderColor; ?>; border-width: <?php echo $border; ?>px;">
    <?php $i = 0; ?>
    <?php for($v = 0; $v < $vCount; $v++): ?>
        <div class="adld-tsr-row">
            <?php for($h = 0; $h < $hCount; $h++): ?>
                <?php if(isset($ads[$i])): ?>
                    <a target="_blank" class="adld-tsr-cell adld-tsr-<?php echo $size; ?>" href="<?php echo $ads[$i]['clickUrl']; ?>" style="border: 1px solid transparent;">
                        <?php echo $ads[$i++]['html']; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endfor; ?>
</div>

<div class="adloud-load-check" style="display: none;"></div>