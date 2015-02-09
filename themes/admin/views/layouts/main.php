<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.09.14
 * Time: 18:03
 * @var \application\components\ControllerAdmin $this
 */
?>

<?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._header'); ?>

<?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._leftMenu'); ?>

<section id="main-content">
    <section class="wrapper">
        <?php if($this->subLayout): ?>
            <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.subLayouts.'.$this->subLayout, ['content' => $content]); ?>
        <?php else: ?>
            <?php echo $content; ?>
        <?php endif; ?>
    </section>
</section>

<?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._footer'); ?>