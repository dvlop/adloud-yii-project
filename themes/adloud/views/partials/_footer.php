<?php
/**
 * @var \application\components\ControllerBase $this
 */
?>
</div><!--/container-->
<!--=== End Content Part ===-->

</div><!--/wrapper-->
<?php $this->partial('_editableAttrsFom'); ?>

<!--=== Footer Part ===-->
<div class="bottom-menu bottom-menu-inverse">
    <div class="container">
        <div class="col-sm-2 col-md-2 navbar-brand">
            <a href="#">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/images/adloud/footer_logo.png">
            </a>
        </div>
        <div class="col-sm-8 col-md-8">
            <div class="row">
                <?php $this->renderWidget('NavMenu', ['class' => 'bottom-links',]); ?>
            </div>
        </div>
        <div class="col-sm-2 col-md-2">
            <div class="row">
                <?php $this->widget('social.widgets.SocialMenu', ['ulClass' => 'bottom-icons']); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->partial('_modal', ['data' => $this->modalContent]); ?>


<!--=== End Footer Part ===-->
<?php Yii::app()->clientScript->registerScript('mainScript', '
    Main.init({
        notificationUrl: "'.Yii::app()->createUrl('index/notificationlist').'"
    });
'); ?>
</body>
</html>