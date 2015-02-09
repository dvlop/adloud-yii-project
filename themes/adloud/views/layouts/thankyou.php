<?php
/**
 * @var application\components\ControllerBase $this
 * @var string $content
 */
?>

<?php $img = Yii::app()->theme->baseUrl.'/assets/images'; ?>

<?php
    $this->partial('_simpleHeader');

    $js = Yii::app()->theme->baseUrl.'/assets/js';

    $cs = Yii::app()->clientScript;
    $cs->registerScriptFile($js.'/pages/landing2.js', CClientScript::POS_END);
?>

<div class="wrapper not-in-service">

    <nav class="navbar navbar-default top-nav">

        <div class="container">

            <?php $this->partial('_mainMenu'); ?>

        </div>

    </nav>

    <div class="container inner_wrapper">

        <?php echo $content; ?>

    </div>

</div>

<?php $this->partial('_simpleFooter'); ?>

<?php Yii::app()->clientScript->registerScript('landingPage', '
    Landing.init({});
'); ?>