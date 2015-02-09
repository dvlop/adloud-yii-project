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
 *
 * @var string $borderStyle
 * @var string $font
 * @var integer $blockWidth
 * @var string $backHoverColor
 * @var string $captionHoverColor
 * @var string $captionHoverFontSize
 * @var string $captionHoverStyleB
 * @var string $captionHoverStyleU
 * @var integer $indentAds
 *
 * @var integer $adsBorder
 * @var string $adsBorderType
 * @var string $adsBorderColor
 * @var string $adsBackColor
 * @var string $textPosition
 * @var integer $indentBorder
 * @var integer $imgBorderWidth
 * @var string $imgBorderType
 * @var string $imgBorderColor
 * @var integer $imgWidth
 * @var integer $borderRadius
 * @var string $captionColor
 * @var integer $captionFontSize
 * @var string $captionStyleB
 * @var string $captionStyleU
 * @var string $descStyleB
 * @var string $descStyleU
 * @var string $textColor
 * @var string $descFontSize
 * @var string $font
 * @var string $alignment
 * @var bool $useDescription
 * @var integer $horizontalCount
 */
?>

<?php
    $styles = '';
    $styles .= 'width: '.$width.' !important; ';
    $styles .= 'border-collapse: separate !important; ';
    $styles .= 'background: none repeat scroll 0% 0% '.$backgroundColor.' !important; ';
    $styles .= 'border-width: '.$border.'px !important; ';
    $styles .= 'border-style: '.$borderStyle.' !important; ';
    $styles .= 'border-color: '.$borderColor.' !important; ';
    $styles .= 'border-spacing: '.$indentAds.'px !important; ';
    $styles .= 'font-family: '.$font.' !important; ';

    $tdStyles = '';
    $tdStyles .= 'width: '.(100/$horizontalCount).'% !important; ';
    $tdStyles .= 'background: none repeat scroll 0% 0% '.$adsBackColor.' !important; ';
    $tdStyles .= 'border-width: '.$adsBorder.'px !important; ';
    $tdStyles .= 'border-style: '.$adsBorderType.' !important; ';
    $tdStyles .= 'border-color: '.$adsBorderColor.' !important; ';
    $tdStyles .= 'padding: '.$indentBorder.'px !important; ';
    $tdStyles .= 'text-align: '.$alignment.' !important; ';

    $imgStyles = '';
    $imgStyles .= 'border-style: solid !important; ';
    $imgStyles .= 'border-width: '.$imgBorderWidth.'px !important; ';
    $imgStyles .= 'border-style: '.$imgBorderType.' !important; ';
    $imgStyles .= 'border-color: '.$imgBorderColor.' !important; ';
    $imgStyles .= 'width: '.$imgWidth.'px !important; ';
    $imgStyles .= 'border-radius: '.$borderRadius.'% !important; ';

    $titleStyles = '';
    $titleStyles .= 'color: '.$captionColor.' !important; ';
    $titleStyles .= 'font-size: '.$captionFontSize.'px !important; ';
    $titleStyles .= $captionStyleB ? 'font-weight: 700 !important; ' : '';
    $titleStyles .= $captionStyleU ? 'text-decoration: underline !important; ' : '';
    $titleStyles .= 'font-family: '.$font.' !important; ';

    $descStyles = '';
    $descStyles .= 'color: '.$textColor.' !important; ';
    $descStyles .= 'font-size: '.$descFontSize.'px !important; ';
    $descStyles .= $descStyleB ? 'font-weight: 700 !important; ' : '';
    $descStyles .= $descStyleU ? 'text-decoration: underline !important; ' : '';
    $descStyles .= 'font-family: '.$font.' !important; ';

    $txtStyles = '';

    if(strpos($textPosition, '{') === false){
        $txtStyles .= $useDescription ? 'display: '.$textPosition.' !important; ' : 'display: none !important;';
        $textPosition = '.adld-tsr-title{ display: table-row-group !important; }';
    }else{
        $txtStyles .= $useDescription ? '' : 'display: none !important;';
        if(!$useDescription)
            $textPosition .= ' .adld-tsr-description{ display: none !important; } ';
    }
?>


<style id="ADLOUD-teasers-styles">
    .adld-tsr-tbl{ <?php echo $styles; ?> }

    .adld-tsr-cell{ <?php echo $tdStyles; ?> }

    .adld-tsr-img{ <?php echo $imgStyles; ?> }

    .adld-tsr-title{ <?php echo $titleStyles; ?> }

    .adld-tsr-description{
        cursor: initial;
        <?php echo $descStyles; ?>
        <?php echo $txtStyles; ?>
    }

    #adld-ifrm-hddn{
        display: none !important;
    }

    .adld-global-link{
        display: table !important;
        width: 100% !important;
        text-decoration: none !important;
    }

    .adld-tsr-cell:hover{
        background: none repeat scroll 0% 0% <?php  echo $backHoverColor; ?> !important;
    }
    .adld-tsr-title:hover{
        color: <?php  echo $captionHoverColor; ?> !important;
    }
    .adld-tsr-title:hover{
        font-size: <?php  echo $captionHoverFontSize; ?>px !important;
    }
    .adld-tsr-title:hover{
        <?php echo $captionHoverStyleB ? 'font-weight: 700 !important;' : ''; ?>
        <?php echo $captionHoverStyleU ? 'text-decoration: underline !important;' : ''; ?>
    }
    <?php if($useDescription): ?>
        .adld-tsr-title, .adld-tsr-description, .adloud-tsr-item{
            display: table-row-group !important;
        }
    <?php else: ?>
        .adld-tsr-title, .adloud-tsr-item{
            display: table-row-group !important;
        }
    <?php endif; ?>

    .adloud-tsr-item{
        margin-right: 4px !important;
    }

    <?php echo $textPosition; ?>
</style>


<div id="ADLOUD-teasers-container" class="crtv-blocks-tsr-container">

    <table class="adld-tsr-tbl">
        <tbody class="adld-tsr-tbl-tbody">
            <?php $i = 0; ?>
            <?php for($v = 0; $v < $vCount; $v++): ?>
                <tr class="adld-tsr-row">
                    <?php for($h = 0; $h < $hCount; $h++): ?>
                        <?php if(isset($ads[$i])): ?>
                            <?php echo $ads[$i++]['html']; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

</div>


<div class="adloud-load-check" style="display: none;"></div>