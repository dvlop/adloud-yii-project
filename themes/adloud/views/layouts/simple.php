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
    <title><?php echo Yii::app()->name; ?></title>

    <?php
    $img = Yii::app()->theme->baseUrl.'/assets/images';
    $css = Yii::app()->theme->baseUrl.'/assets/css';
    $js = Yii::app()->theme->baseUrl.'/assets/js';
    $cs = Yii::app()->clientScript;
    $cs
        ->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport');

    $cs
        ->registerCssFile($css.'/bootstrap.css')
        ->registerCssFile($css.'/flat-ui.css')
        ->registerCssFile($css.'/adloud.css');

    $cs
        ->registerScriptFile($js.'/pages/main.js')
        ->registerScriptFile($js.'/bootstrap.min.js');
    ?>

    <!--Loading Open Sans Font-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>

<?php $this->partial('_googleAnalytics'); ?>

<?php if($this->cssFiles): ?>
    <?php foreach($this->cssFiles as $css): ?>
        <?php Yii::app()->clientScript->registerCssFile($css); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if($this->scripts): ?>
    <?php foreach($this->scripts as $name=>$script): ?>
        <?php Yii::app()->clientScript->registerScript($name, $script); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if($this->scriptFiles): ?>
    <?php foreach($this->scriptFiles as $file): ?>
        <?php Yii::app()->clientScript->registerScriptFile($file, CClientScript::POS_END); ?>
    <?php endforeach; ?>
<?php endif; ?>

<body>

<?php echo $content; ?>

</body>

</html>