<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.09.14
 * Time: 18:03
 * @var \application\components\ControllerAdmin $this
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>Adloud admin</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php
        $css = Yii::app()->theme->baseUrl.'/assets/css/';
        $js = Yii::app()->theme->baseUrl.'/assets/js/';
        $plugins = Yii::app()->theme->baseUrl.'/assets/plugins/';

        $cs = Yii::app()->clientScript;

        $cs
            ->registerCssFile($css.'bootstrap.min.css')
            ->registerCssFile($css.'bootstrap-reset.css')
            ->registerCssFile($plugins.'font-awesome/css/font-awesome.css')
            ->registerCssFile($plugins.'jquery-easy-pie-chart/jquery.easy-pie-chart.css')
            ->registerCssFile($css.'owl.carousel.css')
            ->registerCssFile($css.'style.css')
            ->registerCssFile($css.'style-responsive.css')
            ->registerCssFile($css.'main.css');

        $cs
            //->registerScriptFile($js.'jquery.js', CClientScript::POS_END)
            ->registerScriptFile($js.'bootstrap.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'jquery.dcjqaccordion.2.7.js', CClientScript::POS_END)
            ->registerScriptFile($js.'jquery.scrollTo.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'jquery.nicescroll.js', CClientScript::POS_END)
            ->registerScriptFile($js.'jquery.sparkline.js', CClientScript::POS_END)
            ->registerScriptFile($plugins.'jquery-easy-pie-chart/jquery.easy-pie-chart.js', CClientScript::POS_END)
            ->registerScriptFile($js.'owl.carousel.js', CClientScript::POS_END)
            ->registerScriptFile($js.'jquery.customSelect.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'respond.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'slidebars.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'common-scripts.js', CClientScript::POS_END)
            ->registerScriptFile($js.'extensions.js', CClientScript::POS_END)
            ->registerScriptFile($js.'pages/main.js', CClientScript::POS_END);
    ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<section id="container">

<?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._topLine'); ?>

