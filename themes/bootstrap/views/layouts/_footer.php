<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/2/14
 * Time: 11:03 PM
 * @var \application\components\ControllerBase $this
 */
?>

</div><!--/container-->
<!--=== End Content Part ===-->

<!--=== Footer ===-->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 md-margin-bottom-40">
                <!-- About -->
                <div class="headline"><h2>О нас</h2></div>
                <p class="margin-bottom-25 md-margin-bottom-40">Unify is an incredibly beautiful responsive Bootstrap Template for corporate and creative professionals.</p>

                <!-- Stay Connected -->
                <div class="headline"><h2>Оставайтесь с нами</h2></div>
                <ul class="social-icons">
                    <li><a href="#" data-original-title="Feed" class="social_rss"></a></li>
                    <li><a href="#" data-original-title="Facebook" class="social_facebook"></a></li>
                    <li><a href="#" data-original-title="Twitter" class="social_twitter"></a></li>
                    <li><a href="#" data-original-title="Goole Plus" class="social_googleplus"></a></li>
                    <li><a href="#" data-original-title="Pinterest" class="social_pintrest"></a></li>
                    <li><a href="#" data-original-title="Linkedin" class="social_linkedin"></a></li>
                    <li><a href="#" data-original-title="Vimeo" class="social_vimeo"></a></li>
                </ul>
            </div><!--/col-md-4-->

            <div class="col-md-4 md-margin-bottom-40">
                <!-- Monthly Newsletter -->
                <div class="headline"><h2>Подписаться на рассылку</h2></div>
                <p>Subscribe to our newsletter and stay up to date with the latest news and deals!</p>
                <form class="footer-subsribe">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Email Address">
                        <span class="input-group-btn">
                            <button class="btn-u" type="button">Subscribe</button>
                        </span>
                    </div><!-- /input-group -->
                </form>
            </div><!--/col-md-4-->

            <div class="col-md-4">
                <div class="headline"><h2>Контакты</h2></div>
                <address class="md-margin-bottom-40">
                    25, Lorem Lis Street, Orange <br />
                    California, US <br />
                    Phone: 800 123 3456 <br />
                    Fax: 800 123 3456 <br />
                    Email: <a href="mailto:info@anybiz.com" class="">info@anybiz.com</a>
                </address>
            </div><!--/col-md-4-->
        </div><!--/row-->
    </div><!--/container-->
</div><!--/footer-->
<!--=== End Footer ===-->

<!--=== Copyright ===-->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <p class="copyright-space">
                    2014 &copy; adloud.net. Все права сохранены.
                    <a href="#">Политика приватности</a> | <a href="#">Правила обслуживания</a>
                </p>
            </div>
            <div class="col-md-5">
                <a href="<?php echo Yii::app()->createUrl('index/index');?>">
                    <h1><b style="color:#72C02C">T</b>easeWith .me</h1>
                </a>
            </div>
        </div><!--/row-->
    </div><!--/container-->
</div><!--/copyright-->
<!--=== End Copyright ===-->

<?php if($this->cssFiles): ?>
    <?php foreach($this->cssFiles as $css): ?>
        <?php Yii::app()->clientScript->registerCssFile($css, CClientScript::POS_HEAD); ?>
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

<?php Yii::app()->clientScript->registerScript('mainScript', '
    Main.init({});
'); ?>

<!-- JS Global Compulsory -->
<script type="text/javascript">
    jQuery(document).ready(function() {
        if(typeof App != 'undefined')
            App.init();
//
//      App.initBxSlider();
    });
</script>
<!--[if lt IE 9]>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/plugins/respond.js"></script>
<![endif]-->

<?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._mainModal', ['data' => $this->modalContent]); ?>

</body>
</html>