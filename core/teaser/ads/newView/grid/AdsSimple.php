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
 * @var boolean $backgroundOpacity
 * @var boolean $borderOpacity
 */
?>


<div id="teasers-container" class="adld-simple-tsr-table" style="width: <?php echo $width; ?>px;">
    <?php $i = 0; ?>
    <?php for($v = 0; $v < $vCount; $v++): ?>
        <div class="adld-simple-tsr-row">
            <?php for($h = 0; $h < $hCount; $h++): ?>
                <?php if(isset($ads[$i])): ?>
                    <a target="_blank" class="adld-simple-tsr-cell adld-simple-tsr-<?php echo $size; ?>" href="<?php echo $ads[$i]['clickUrl']; ?>" style="border-style: solid; border-width: 1px; border-color:<?php echo $borderColor; ?>;">
                        <?php echo $ads[$i++]['html']; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endfor; ?>
</div>

<div class="adloud-load-check" style="display: none;"></div>