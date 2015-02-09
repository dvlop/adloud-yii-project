<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>adLoud.net | Добро пожаловать...</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <script>
        var _baseUrl = '<?php echo Yii::app()->theme->baseUrl;?>';
    </script>

    <?php
    $css = Yii::app()->theme->baseUrl.'/assets/css';
    $js = Yii::app()->theme->baseUrl.'/assets/js';
    $plugins = Yii::app()->theme->baseUrl.'/assets/plugins';

    $cs = Yii::app()->clientScript;
    $cs
        ->registerCssFile($plugins.'/bootstrap/css/bootstrap.min.css')
        ->registerCssFile($css.'/responsive.css')
        ->registerCssFile($plugins.'/font-awesome/css/font-awesome.css')
        ->registerCssFile($css.'/style.css')
        ->registerCssFile($css.'/headers/header2.css')
        ->registerCssFile($plugins.'/bxslider/jquery.bxslider.css')
        ->registerCssFile($css.'/pages/page_log_reg_v1.css')
        ->registerCssFile($css.'/themes/headers/header2-blue.css')
        ->registerCssFile($css.'/themes/blue.css')
        ->registerCssFile($css.'/toastr.min.css')
        ->registerCssFile($css.'/bootstrap-multiselect.css')
        ->registerCssFile($css.'/bootstrap-slider.css')
        ->registerCssFile($css.'/bootstrap-colorpicker.min.css')
        ->registerCssFile($css.'/bootstrap-switch.css')
        ->registerCssFile($css.'/site.css')
        ->registerCssFile($css.'/additional-styles.css')
    ;

    $cs
        ->registerScriptFile($plugins.'/jquery-migrate-1.2.1.min.js', CClientScript::POS_END)
        ->registerScriptFile($plugins.'/bootstrap/js/bootstrap.min.js', CClientScript::POS_END)
        ->registerScriptFile($plugins.'/hover-dropdown.min.js', CClientScript::POS_END)
        ->registerScriptFile($plugins.'/back-to-top.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/jquery.cookie.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/jquery.scrollTo.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/toastr.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/jquery.spinner.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/moment.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/bootstrap-multiselect.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/bootstrap-slider.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/bootstrap-colorpicker.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/bootstrap-switch.min.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/app.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/ajax-adloud.js', CClientScript::POS_END)
        ->registerScriptFile($js.'/pages/main.js', CClientScript::POS_END);
    ?>

    <link rel="shortcut icon" href="/favicon.ico">
</head>

<body>

<?php if(Yii::app()->user->hasFlash('success')):?>
    <script>
        $(function(){
            toastr.success('<?php echo Yii::app()->user->getFlash('success'); ?>');
        });
    </script>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('error')):?>
    <script>
        $(function(){
            toastr.error('<?php echo Yii::app()->user->getFlash('error'); ?>');
        });
    </script>
<?php endif; ?>

<!--=== Header ===-->
<div class="header">
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo Yii::app()->createUrl('index/index');?>">
                    <i class="icon-cloud icon-color-blue icon-no-border"></i> adLoud<b>.</b>net
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php if(Yii::app()->user->isGuest):?>
                        <li>
                            <a href="<?php echo Yii::app()->createUrl('index/login');?>">Вход</a>
                        </li>

                        <li>
                            <a href="<?php echo Yii::app()->createUrl('index/registration');?>">Регистрация</a>
                        </li>
                    <?php else :?>

                        <li class="<?php echo ($this->id == 'advertiser' || ($this->getId() == 'finances' && $this->action->getId() == 'moneyadd')) ? 'active' : '';?>">
                            <a href="<?php echo Yii::app()->createUrl('advertiser/campaign/list');?>">Рекламодателю</a>
                        </li>

                        <li class="<?php echo ($this->id == 'webmaster' || ($this->getId() == 'finances' && $this->action->getId() == 'moneyshow')) ? 'active' : '';?>">
                            <a href="<?php echo Yii::app()->createUrl('webmaster/site/list');?>">Вебмастеру</a>
                        </li>

                        <?php if( Yii::app()->user->isAdmin):?>
                            <li class="<?php echo ($this->id == 'admin') ? 'active' : '';?>">
                                <a href="<?php echo Yii::app()->createUrl('admin/index/index');?>">Админ-панель</a>
                            </li>
                        <?php endif;?>


                        <li>
                            <a href="<?php echo Yii::app()->createUrl('index/logout');?>">Выход</a>
                        </li>
                    <?php endif;?>


<!--                    <li>-->
<!--                        <a href="--><?php //echo Yii::app()->createUrl('partners/index');?><!--">-->
<!--                            Партнерам-->
<!--                        </a>-->
<!--                    </li>-->

                    <li class="hidden-sm"><a class="search"><i class="icon-search search-btn"></i></a></li>
                </ul>
                <div class="search-open">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Поиск">
                        <span class="input-group-btn">
                            <button class="btn-u" type="button">Go</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div><!-- /navbar-collapse -->
        </div>
    </div>
</div><!--/header-->
<!--=== End Header ===-->

<?php $this->widget('themes.bootstrap.widgets.BreadCrumbs'); ?>

<!--=== Content Part ===-->
<div class="container">
