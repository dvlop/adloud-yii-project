<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 15.07.14
 * Time: 16:16
 * @var application\components\ControllerBase $this
 */
?>
<footer class="footer-minimal text-center">
    <p>
        <?php echo Yii::t('main', 'Авторские права'); ?> &copy; 2014 AdLoud Inc. <?php echo Yii::t('main', 'Все права защищены'); ?>. <a href="<?php echo Yii::app()->createUrl('index/contacts'); ?>"><?php echo Yii::t('main', 'Связаться с нами'); ?></a>
    </p>
</footer>

<?php Yii::app()->clientScript->registerScript('mainScript', '
    Main.init({
        notificationUrl: "'.Yii::app()->createUrl('index/notificationlist').'"
    });
'); ?>

<?php $this->partial('_thankyouCode'); ?>

</body>
</html>