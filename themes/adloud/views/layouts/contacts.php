<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 29.07.14
 * Time: 15:
 * @var \application\components\ControllerBase $this
 */
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>AdLoud</title>

    <?php
        $css = Yii::app()->theme->baseUrl.'/assets/css';
        $js = Yii::app()->theme->baseUrl.'/assets/js';
        $img = Yii::app()->theme->baseUrl.'/assets/images';
        $plugins = Yii::app()->theme->baseUrl.'/assets/plugins';

        $cs = Yii::app()->clientScript;

        $cs
            ->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport');

        $cs
            ->registerCssFile($css.'/bootstrap.css')
            ->registerCssFile($css.'/flat-ui.css')
            ->registerCssFile($css.'/adloud.css');
    ?>

    <!--Loading Open Sans Font-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
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

<body onload="initialize()">
<div class="wrapper contacts-page">

    <?php $this->renderWidget('ContactsNavMenu'); ?>

    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="row">
                    <h1><?php echo Yii::t('contacts', 'Наши контакты:'); ?></h1>
                    <div class="col-sm-4 phone-contacs">
                        <div class="row">
                            <section class="contacts-section">
                                <h2><?php echo Yii::t('contacts', 'Телефон'); ?></h2>
                                <p>+38 067 401 38 14</p>
                            </section>
                        </div>
                    </div>
                    <div class="col-sm-4 skype-contacts text-center">
                        <section class="contacts-section">
                            <h2><?php echo Yii::t('contacts', 'Skype'); ?></h2>
                            <p><?php echo Yii::t('contacts', 'adloud'); ?></p>
                        </section>
                    </div>
                    <div class="col-sm-4 e-main-contacs">
                        <section class="contacts-section">
                            <h2><?php echo Yii::t('contacts', 'E-Mail'); ?></h2>
                            <p><?php echo Yii::t('contacts', 'support@adloud.net'); ?></p>
                        </section>
                    </div>

                    <div class="col-sm-12">
                        <div class="row lead-btn">
                            <div class="col-sm-6">
                                <div class="row">
                                    <span><?php echo Yii::t('contacts', 'Зарегистрироваться и'); ?> </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <a href="<?php echo Yii::app()->createUrl('index/register'); ?>" class="btn adloud_btn btn-block"><?php echo Yii::t('contacts', 'Начать зарабатывать!'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->

    <div class="map-wrapper">
        <div id="map" style="height:380px;"></div>
    </div>

     <!--<div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <form method="" action="" id="" class="contacts-form" role="form">
                                <div class="form-group">
                                    <input type="text" name="" class="form-control flat" placeholder="Ваше имя:"/>
                                    <input type="email" name="" class="form-control flat" placeholder="E-Mail:"/>
                                </div>
                                <div class="form-group">
                                    <textarea name="" class="form-control flat" placeholder="Ваш комментарий:" rows="9"></textarea>
                                </div>
                                <button class="btn btn-inverse" type="submit">Отправить сообщение</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

</div>
<footer class="bottom-menu bottom-menu-inverse">
    <div class="container">
        <div class="col-sm-2 col-md-2 navbar-brand">
            <a href="#">
                <img src="<?php echo $img; ?>/adloud/footer_logo.png"/>
            </a>
        </div>
        <div class="col-sm-8 col-md-8">
            <div class="row">
                <?php $this->renderWidget('NavMenu', ['class' => 'bottom-links',]); ?>
            </div>
        </div>
        <div class="col-sm-2 col-md-2">
            <div class="row">
                <ul class="bottom-icons">
                    <li><a href="#" class="fui-pinterest"></a></li>
                    <li><a href="#" class="fui-facebook"></a></li>
                    <li><a href="#" class="fui-twitter"></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer> <!-- /bottom-menu-inverse -->
<!-- Load JS here for greater good =============================-->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
    function initialize() {
        var myLatlng = new google.maps.LatLng(50.455624, 30.517543);

        var mapOptions = {
            scrollwheel: false,
            zoom: 17,
            center: myLatlng
        }

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var contentString = '<div style="height: 72px;overflow:hidden;" class="mapinfo">Adloud<br/>Большая Житомирская ул., 6/11,<br/> Киев, Киевская область, Украина<br/><a style="color: #f39c12" href="//adloud.net">adloud.net</a></div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'Hello World!',
            icon: '<?php echo $img; ?>/adloud/tag.png'
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker);
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
</body>
</html>