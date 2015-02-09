<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 15.07.14
 * Time: 16:12
 * @var application\components\ControllerBase $this
 */
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>

    <meta charset="utf-8"/>
    <title>Adloud</title>

    <?php
    $css = Yii::app()->theme->baseUrl.'/assets/css';
    $js = Yii::app()->theme->baseUrl.'/assets/js';
    $img = Yii::app()->theme->baseUrl.'/assets/images';
    $plugins = Yii::app()->theme->baseUrl.'/assets/plugins';

    $cs = Yii::app()->clientScript;

    $cs
        ->registerMetaTag('page description', 'description')
        ->registerMetaTag('page width=device-width, initial-scale=1.0', 'viewport')
        ->registerMetaTag('k7XPBrIyywP4bRHAj4SXWR74KTYmXuCzomcpPLk_8RU', 'google-site-verification');

    $cs
        ->registerCssFile($css.'/bootstrap.css')
        ->registerCssFile($css.'/flat-ui.css')
        ->registerCssFile($css.'/adloud.css')
        ->registerCssFile($css.'/landing.css')
        ->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic')
        ->registerCssFile('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

    $cs
        ->registerScriptFile($js.'/smoothscroll.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/pages/main.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/ajax-adloud.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/jquery-ui-1.10.4.custom.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/jquery.ui.touch-punch.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/bootstrap.min.js', CClientScript::POS_END);

    $loginFormId = 'login-form';
    $registerFormId = 'registration-form';
    $restoreFormId = 'restore-pass-form';
    $afterRegFormId = 'after-registration';
    $afterRestoreFormId = 'after-restore';
    ?>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
        <?php
            $cs
                ->registerScriptFile($js.'/html5shiv.js', CClientScript::POS_END)
                ->registerScriptFile($js.'/respond.min.js', CClientScript::POS_END);
        ?>
    <![endif]-->

    <?php $this->partial('_googleAnalytics'); ?>

</head>

<body>

