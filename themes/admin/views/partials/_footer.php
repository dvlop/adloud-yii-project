<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.09.14
 * Time: 18:03
 * @var \application\components\ControllerAdmin $this
 */
?>


<!--footer start-->
<footer class="site-footer">
    <div class="text-center">
        <?php echo date('Y'); ?> &copy; <?php echo Yii::app()->name; ?>.
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
<!--footer end-->

</section>

<?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._modal'); ?>

<?php Yii::app()->getClientScript()->registerScript('mainAdminScript', '
    Main.init();
'); ?>

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

</body>
</html>