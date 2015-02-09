<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 20.08.14
 * Time: 15:23
 * @var \application\modules\block\controllers\SelectController $this
 * @var integer $siteId
 */
?>

<?php
    use application\models\Blocks;

    $img = Yii::app()->theme->baseUrl.'/assets/images';
?>

<div class="creative-blocks-preloader">
    <div class="creative-blocks-preloader-wrapper">
        <div class="creative-blocks-preloader-inner">

            <div class="container">
                <h1 class="creative-blocks-logo col-sm-12 text-center">
                    <img src="<?php echo $img; ?>/adloud/creative-blocks-logo.png" alt="Creative Blokcs"/>
                </h1>
                <p class="col-sm-12 text-center select-teaser-type-note">
                    Выберите один из форматов
                    <span
                        data-toggle="tooltip"
                        data-placement="right"
                        data-html="true"
                        title="<div class='text-center'><img src='<?php echo $img; ?>/adloud/select-teaser-type-note.png'</div><p>Выберите один из предлагамых форматов.</p> <p>После чего воспользуйтесь возможностями редактора Creative Blocks</p>"
                    >?</span>
                </p>
                <div class="col-sm-12 select-teaser-type-variant">
                    <section class="col-sm-6">
                        <div class="col-sm-5 teaser-type simple-teaser">
                            <h3 class="teaser-type-name">Товарный</h3>
                            <p class="teaser-type-description">Формат раскрывающий суть предложения с помощью подробной информации.</p>
                            <p class="teaser-type-description">Блоки дополнены фавиконкой и кнопкой призыва к действию.</p>
                            <a class="btn btn-block adloud_btn btn-embossed" href="<?php echo Yii::app()->createUrl('block/index/index', ['siteId' => $siteId, 'format' => Blocks::FORMAT_MARKET]); ?>">Выбрать<span class="fui-arrow-right"></span></a>
                        </div>
                        <div class="col-sm-7 teaser-type-img text-right">
                            <div class="row">
                                <img src="<?php echo $img; ?>/adloud/marketable-teaser.png"/>
                            </div>
                        </div>
                    </section>
                    <section class="col-sm-6">
                        <div class="col-sm-7 teaser-type-img">
                            <div class="row">
                                <img src="<?php echo $img; ?>/adloud/simple-teaser.png"/>
                            </div>
                        </div>
                        <div class="col-sm-5 teaser-type marketable-teaser">
                            <h3 class="teaser-type-name">Простой <br/>(в разработке)</h3>
                            <p class="teaser-type-description">Минималистичный формат тизеров, ничего лишнего. Картинка, заголовок и текст.</p>
                            <p class="teaser-type-description">При использовании больших размеров, данный формат дополнен ссылкой.</p>
                            <!--<a style="background: #A0A0A0; cursor: not-allowed;" class="btn btn-block adloud_btn btn-embossed" href="#<?php //echo Yii::app()->createUrl('block/index/index', ['siteId' => $siteId]); ?>">Выбрать<span class="fui-arrow-right"></span></a>-->
                            <a class="btn btn-block adloud_btn btn-embossed" href="<?php echo Yii::app()->createUrl('block/index/index', ['siteId' => $siteId, 'format' => Blocks::FORMAT_SIMPLE]); ?>">Выбрать<span class="fui-arrow-right"></span></a>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('selectBlockFormat', '
    SelectFormat.init();
'); ?>