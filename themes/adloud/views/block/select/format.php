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

    <nav class="crtv-blocks-type-nav">
        <ul class="crtv-blocks-type-nav-list">
            <li class="crtv-blocks-type-nav-item active" data-section="#crtv-blocks-type-constructor">
            </li>
            <li class="crtv-blocks-type-nav-item" data-section="#crtv-blocks-type-market">
            </li>
            <li class="crtv-blocks-type-nav-item" data-section="#crtv-blocks-type-simple">
            </li>
        </ul>
    </nav>

    <div class="creative-blocks-preloader-wrapper">
        <div class="creative-blocks-preloader-inner">

            <div class="container" style="display:table; height: 100%;">

                <header class="crtv-blocks-type-page-header">

                    <h1 class="creative-blocks-logo col-sm-12 text-center">
                        <img src="<?php echo $img; ?>/adloud/creative-blocks-logo.png" alt="Creative Blokcs"/>
                    </h1>

                    <p class="col-sm-12 text-center crtv-blocks-type-note"><?php echo Yii::t('select_format', 'Выберите один из форматов'); ?> <span data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='text-center'><img src='<?php echo $img; ?>/adloud/select-teaser-type-note.png'</div><p>Вы можете выбрать более подходящий Вам вариант оформления блоков для вашего сайта - и настроить все нужныее вам варианты размеров, выбрав, и перейдя далее по настройкам в Creative Blocks</p>">?</span></p>

                </header>

                <div class="crtv-blocks-type-variant col-sm-12">
                    <div class="row">
                        <section class="col-sm-10 col-sm-offset-1 crtv-blocks-type-item crtv-blocks-type-constructor active" id="crtv-blocks-type-constructor">
                            <div class="row">
                                <div class="pull-right crtv-blocks-type-description text-center">
                                    <h3 class="crtv-blocks-type-name"><?php echo Yii::t('select_format', 'Конструктор блоков'); ?></h3>

                                    <p class="crtv-blocks-type-about"><?php echo Yii::t('select_format', 'Рекомендуется'); ?>.<br>
                                        <?php echo Yii::t('select_format', '33 настройки, которые помогут адаптировать рекламные блоки под дизайн вашего сайта'); ?>.<br>
                                        <?php echo Yii::t('select_format', 'Совет: создавайте блоки похожими на навигационные ссылки вашего сайта'); ?>
                                    </p>
                                    <a class="crtv-blocks-type-link" href="<?php echo Yii::app()->createUrl('block/index/index', ['siteId' => $siteId, 'format' => Blocks::FORMAT_MAIN]); ?>">
                                        <?php echo Yii::t('select_format', 'Выбрать'); ?> <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                            </div>
                        </section>

                        <section class="col-sm-10 col-sm-offset-1 crtv-blocks-type-item crtv-blocks-type-market" id="crtv-blocks-type-market">
                            <div class="row">
                                <div class="pull-right crtv-blocks-type-description text-center">
                                    <h3 class="crtv-blocks-type-name"><?php echo Yii::t('select_format', 'Товарный блок'); ?></h3>
                                    <p class="crtv-blocks-type-about">
                                        <?php echo Yii::t('select_format', 'Товарные тизеры обладают более низким CTR, что компенсируется за счёт более высокой цены клика'); ?>.<br>
                                        <?php echo Yii::t('select_format', 'Данный формат находится на стадии тестирования'); ?>.
                                    </p>
                                    <a class="crtv-blocks-type-link" href="<?php echo Yii::app()->createUrl('block/index/index', ['siteId' => $siteId, 'format' => Blocks::FORMAT_MARKET]); ?>">
                                        <?php echo Yii::t('select_format', 'Выбрать'); ?> <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                            </div>
                        </section>

                        <!--
                        <section class="col-sm-10 col-sm-offset-1 crtv-blocks-type-item crtv-blocks-type-simple" id="crtv-blocks-type-simple">
                            <div class="row">
                                <div class="pull-right crtv-blocks-type-description text-center">
                                    <h3 class="crtv-blocks-type-name">Экспериментальный блок</h3>
                                    <p class="crtv-blocks-type-about">Все типы настроек <br>включая весь необходимый функционал <br>у вас под руками, с возможностью <br>создания <br>соответственно стилю вашего сайта.</p>
                                    <a class="crtv-blocks-type-link" href="<?php //echo Yii::app()->createUrl('block/index/index', ['siteId' => $siteId, 'format' => Blocks::FORMAT_SIMPLE]); ?>">Выбрать <span class="fa fa-angle-right"></span></a>
                                </div>
                            </div>
                        </section>
                        -->

                        <span class="circle col-sm-10 col-sm-offset-1"><span></span><span></span><span></span></span>

                    </div>
                </div>

            </div>

        </div>
    </div>


<?php Yii::app()->clientScript->registerScript('selectBlockFormat', '
    SelectFormat.init();
'); ?>