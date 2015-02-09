<?php
    /**
     * @var CController $this
     * @var string $content
     */
?>

<?php $this->renderPartial('themes.bootstrap.views.layouts._header');?>

<?php echo $content; ?>

<?php $this->renderPartial('themes.bootstrap.views.layouts._footer');?>