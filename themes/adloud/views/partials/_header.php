<?php
/**
 * @var application\components\ControllerBase $this
 */
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="ru" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="ru" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="ru"> <!--<![endif]-->
<head>

    <meta charset="utf-8">
    <title><?php echo $this->title; ?></title>

    <?php
        $css = Yii::app()->theme->baseUrl.'/assets/css';
        $js = Yii::app()->theme->baseUrl.'/assets/js';
        $plugins = Yii::app()->theme->baseUrl.'/assets/plugins';

        $cs = Yii::app()->clientScript;

        $cs
            ->registerMetaTag('page description', 'description')
            ->registerMetaTag('page width=device-width, initial-scale=1.0', 'viewport')
            ->registerMetaTag('k7XPBrIyywP4bRHAj4SXWR74KTYmXuCzomcpPLk_8RU', 'google-site-verification');

        $cs
            ->registerCssFile($css.'/bootstrap.css')
            ->registerCssFile($css.'/flat-ui.css')

            ->registerCssFile($css.'/cropper.min.css')
            ->registerCssFile($css.'/adloud.css')
            ->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic')
            ->registerCssFile('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css')
            ->registerCssFile($css.'/additional-styles.css')
            ->registerCssFile($css.'/jquery.mCustomScrollbar.css');

        $cs
            ->registerScriptFile($js.'/jquery-ui-1.10.4.custom.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/jquery.ui.touch-punch.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/bootstrap.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/bootstrap-select.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/bootstrap-switch.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/bootstrap-multiselect.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/flatui-checkbox.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/cropper.min.js', CClientScript::POS_BEGIN)
            ->registerScriptFile($js.'/jquery.mCustomScrollbar.concat.min.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/flatui-radio.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/jquery.placeholder.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/jquery.tagsinput.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/typeahead.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/jquery.limit-1.2.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/pages/main.js', CClientScript::POS_END)
            ->registerScriptFile($js.'/ajax-adloud.js', CClientScript::POS_END);
    ?>

    <script src="http://code.jquery.com/jquery-latest.min.js"></script>

</head>

<body>

<?php $this->partial('_googleAnalytics'); ?>

<script type="text/javascript">
    var _dateFormat = 'yy-mm-dd';
</script>

<p style="position: fixed; top: 100px;"></p>

<div class="wrapper">

    <?php $this->partial('_messageBlock'); ?>

    <!--=== Header ===-->
    <nav class="navbar navbar-default top-nav">
        <div class="container">
            <?php $this->partial('_mainMenu'); ?>
        </div>
    </nav><!--/header-->
    <!--=== End Header ===-->

    <!--=== Content Part ===-->
    <div class="container inner_wrapper">
