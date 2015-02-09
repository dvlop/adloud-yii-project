<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 31.07.14
 * Time: 15:10
 * @var \application\components\ControllerBase $this
 */
?>

<!doctype html>
<html>

<head>

    <meta charset="utf-8"/>

    <title><?php echo Yii::t('advertisers', 'Рекламная сеть AdLoud - Поднимите свой бизнес с колен!'); ?></title>

    <?php
        $css = Yii::app()->theme->baseUrl.'/assets/css';
        $js = Yii::app()->theme->baseUrl.'/assets/js';
        $img = Yii::app()->theme->baseUrl.'/assets/images';
        $plugins = Yii::app()->theme->baseUrl.'/assets/plugins';

        $cs = Yii::app()->clientScript;

        $cs
            ->registerMetaTag(Yii::t('advertisers', 'тизерная сеть, тизеная реклама, разместищение рекламы, биржа трафика, рекламная сеть'), 'keyword')
            ->registerMetaTag('AdLoud Network - '.Yii::t('advertisers', 'современная рекламная сеть. Мы гарантируем высокий доход нашим клиентам').'.', 'description')
            ->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport')
            ->registerMetaTag('k7XPBrIyywP4bRHAj4SXWR74KTYmXuCzomcpPLk_8RU', 'google-site-verification');

        $cs
            ->registerCssFile($css.'/styles.css')
            ->registerCssFile('http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css')
            ->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic');

        $cs
            ->registerScriptFile($js.'/smoothscroll.js');
    ?>

    <link rel="shortcut icon" href="favicon.ico"/>

    <!--[if lt IE 9]>
        <script src="<?php echo $js; ?>/html5.js"></script>
        <script src="<?php echo $js; ?>/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<!--Landing header-->
<header class="header">

    <?php $this->renderWidget('ContactsNavMenu'); ?>

    <figure class="site-screenshot">
        <img src="<?php echo $img; ?>/screenshot-1.jpg"/>
        <img src="<?php echo $img; ?>/screenshot-2.jpg"/>
        <img src="<?php echo $img; ?>/screenshot-3.jpg"/>
    </figure>

    <p class="slogan">
        <?php echo Yii::t('advertisers', 'Повышайте доход вашего бизнеса'); ?>
        <br/>
        <span>
            <?php echo Yii::t('advertisers', 'работая с источниками качественного трафика'); ?>
        </span>
    </p>

    <div class="header-reg">
        <a class="reg-btn" href="<?php echo Yii::app()->createUrl('index/register') ?>">
            <?php echo Yii::t('advertisers', 'Получить клиентов'); ?> <i class="fa fa-angle-right"></i>
        </a>
    </div>

</header>
<!--/Landing header-->

<!--Main landing content-->
<main class="content" role="main">

    <section class="section-wrapper target-audience">
        <div class="section">
            <div class="section-text">
                <h2 class="section-title" role="heading">
                    <?php echo Yii::t('advertisers', 'Определяйте'); ?><br/>
                    <?php echo Yii::t('advertisers', 'целевую аудиторию'); ?><br/>
                    <?php echo Yii::t('advertisers', 'максимально точно'); ?>
                </h2>
                <p class="section-description">
                    <?php echo Yii::t('advertisers', 'Благодаря расширенным функциям таргетинга'); ?><br/>
                    <?php echo Yii::t('advertisers', 'ваши объявления увидят целевые клиенты'); ?>.<br/>
                </p>
            </div>
        </div>
    </section>

    <section class="section-wrapper results">
        <div class="section">
            <div class="section-text">
                <h2 class="section-title" role="heading">
                    <?php echo Yii::t('advertisers', 'Следите'); ?><br/>
                    <?php echo Yii::t('advertisers', 'за получением'); ?><br/>
                    <?php echo Yii::t('advertisers', 'результата'); ?>
                </h2>
                <p class="section-description">
                    <?php echo Yii::t('advertisers', 'При помощи простой и понятной'); ?><br/>
                    <?php echo Yii::t('advertisers', 'realtime статистики'); ?>.<br/>
                    <a href="#">
                        <?php echo Yii::t('advertisers', 'Попробовать'); ?> <i class="fa fa-angle-right"></i>
                    </a>
                </p>
            </div>
        </div>
    </section>

    <section class="section-wrapper pay-per-results">
        <h2 class="section-title" role="heading">
            <?php echo Yii::t('advertisers', 'Платите только за результат'); ?>
        </h2>
        <div class="section-img">
            <img src="<?php echo $img; ?>/pay-per-results.png"/>
        </div>
        <p class="section-description">
            <?php echo Yii::t('advertisers', 'В нашей системе вы платите только за клики по рекламному блоку'); ?><br/>
            <a class="reg-btn" href="<?php echo Yii::app()->createUrl('index/register'); ?>">
                <?php echo Yii::t('advertisers', 'Присоединиться'); ?> <i class="fa fa-angle-right"></i>
            </a>
        </p>
    </section>

    <div class="border-section">
        <div></div>
    </div>

    <div class="section-wrapper accomplishments">
        <div class="section">
            <div class="accomplishments-item">
						<span class="accomplishments-img">
							<img src="<?php echo $img; ?>/accomplishments-item-1.png"/>
						</span>
						<span class="accomplishments-txt">
							<?php echo Yii::t('advertisers', 'Вы будете работать с новыми'); ?><br/>
                            <?php echo Yii::t('advertisers', 'привлекательными форматами'); ?><br/>
                            <?php echo Yii::t('advertisers', 'рекламных блоков'); ?>
						</span>
            </div>
            <div class="accomplishments-item">
						<span class="accomplishments-img">
							<img src="<?php echo $img; ?>/accomplishments-item-2.png"/>
						</span>
						<span class="accomplishments-txt">
							<?php echo Yii::t('advertisers', 'Ваши объявления будут размещены'); ?><br/>
                            <?php echo Yii::t('advertisers', 'на качественных площадках'); ?><br/>
                            <?php echo Yii::t('advertisers', 'с огромным охватом аудитории'); ?>
						</span>
            </div>
            <div class="accomplishments-item">
						<span class="accomplishments-img">
							<img src="<?php echo $img; ?>/accomplishments-item-3.png"/>
						</span>
						<span class="accomplishments-txt">
							<?php echo Yii::t('advertisers', 'Вы сможете управлять рекламной'); ?><br/>
                            <?php echo Yii::t('advertisers', 'кампанией, пользуясь простым'); ?><br/>
                            <?php echo Yii::t('advertisers', 'и удобным интерфейсом'); ?>
						</span>
            </div>
            <div class="accomplishments-item">
						<span class="accomplishments-img">
							<img src="<?php echo $img; ?>/accomplishments-item-4.png"/>
						</span>
						<span class="accomplishments-txt">
							<?php echo Yii::t('advertisers', 'Вы получите помощь персонального'); ?><br/>
                            <?php echo Yii::t('advertisers', 'менеджера и сможете настроить'); ?><br/>
                            <?php echo Yii::t('advertisers', 'рекламную кампанию наиболее'); ?><br/>
                            <?php echo Yii::t('advertisers', 'эффективным образом'); ?>
						</span>
            </div>
        </div>
    </div>

    <div class="link-to-footer">
        <button type="button">
            <img src="<?php echo $img; ?>/link-to-footer.png"/>
        </button>
    </div>

    <section class="section-wrapper login-or-reg">
        <div class="section">
            <h2 class="section-title">
                <?php echo Yii::t('advertisers', 'Зарегистрируйтесь в нашей системе'); ?>
            </h2>
            <p class="section-description">
                <?php echo Yii::t('advertisers', 'и оцените на деле все преимущества работы с современной тизерной рекламой'); ?>
            </p>

            <div class="log-reg-btn">
                <a class="login-btn btn" href="<?php echo Yii::app()->createUrl('index/auth'); ?>">
                    <?php echo Yii::t('advertisers', 'Войти'); ?>
                </a>
                <a class="reg-btn btn" href="<?php echo Yii::app()->createUrl('index/register'); ?>">
                    <?php echo Yii::t('advertisers', 'Присоединиться'); ?>
                </a>
            </div>
        </div>
    </section>

</main>
<!--/Main landing content-->



<!--Landing footer-->
<footer class="footer">
    <div class="footer-nav">

        <ul class="footer-nav-list">

            <?php $this->renderWidget('BottomMenu'); ?>

            <?php $this->widget('social.widgets.SocialMenuLight'); ?>

        </ul>

    </div>
</footer>
<!--/Landing footer-->

<!--Loading jquery-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
    $('.link-to-footer button').click(function() {
        $('html, body').animate({
            scrollTop: $(".footer").offset().top
        }, 1000);
    });
</script>

</body>

</html>
