<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 20.06.14
 * Time: 11:55
 * @var ControllerBase $this
 * @var string $content
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Adloud landing page</title>
    <meta name="description" content="page description"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<?php
    $img = Yii::app()->theme->baseUrl.'/assets/images';
    $css = Yii::app()->theme->baseUrl.'/assets/css';
    $js = Yii::app()->theme->baseUrl.'/assets/js';
    $cs = Yii::app()->clientScript;
    $cs
        ->registerCssFile($css.'/bootstrap.css')
        ->registerCssFile($css.'/flat-ui.css')
        ->registerCssFile($css.'/new-pass.css')
        ->registerCssFile($css.'/adloud.css')
        ->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic')
        ->registerCssFile('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

    $cs
        ->registerScriptFile($js.'/jquery-ui-1.10.4.custom.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/pages/main.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/ajax-adloud.js', CClientScript::POS_END);
?>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<?php $this->partial('_googleAnalytics'); ?>

<div class="new-pass-page">

    <?php $this->partial('_messageBlock'); ?>

    <div class="new-pass-wrapper text-center">

        <section>

            <?php echo $content; ?>

        </section>

        <?php $this->renderWidget('NavMenu', [
            'class' => 'bottom-links',
            'navMenu' => 'bottom-menu',
        ]); ?>

        <a href="<?php echo Yii::app()->createAbsoluteUrl('/'); ?>"><img src="<?php echo $img; ?>/adloud/new-pass/logo.png"/></a>

    </div>
</div>

<?php Yii::app()->clientScript->registerScript('mainScript', '
    Main.init({
        notificationUrl: "'.Yii::app()->createUrl('index/notificationlist').'"
    });
'); ?>

</body>
</html>