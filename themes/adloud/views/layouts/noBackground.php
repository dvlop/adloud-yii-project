<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 20.06.14
 * Time: 11:55
 * @var application\components\ControllerBase $this
 * @var string $content
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Разместите рекламу на вашем сайте!</title>

<?php
    $img = Yii::app()->theme->baseUrl.'/assets/images';
    $css = Yii::app()->theme->baseUrl.'/assets/css';
    $js = Yii::app()->theme->baseUrl.'/assets/js';
    $cs = Yii::app()->clientScript;
    $cs
        ->registerMetaTag('page description', 'description')
        ->registerMetaTag('page width=device-width, initial-scale=1.0', 'viewport')
        ->registerMetaTag('k7XPBrIyywP4bRHAj4SXWR74KTYmXuCzomcpPLk_8RU', 'google-site-verification');

    $cs
        ->registerScriptFile($js.'/pages/main.js');
?>

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<?php $this->partial('_googleAnalytics'); ?>

<body marginwidth="0" marginheight="0" bgcolor="#ffffff" style="color:#34495e; font-size: 14px; font-family: Arial, Helvetica, sans-serif;" yahoo="fix" topmargin="0" leftmargin="0">

    <?php echo $content; ?>

</body>

</body>
</html>