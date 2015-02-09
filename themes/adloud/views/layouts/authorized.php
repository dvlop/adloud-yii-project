<?php
/**
 * @var \application\components\ControllerBase $this
 * @var string $content
 */
?>
<?php $this->partial('_header');?>

    <div class="row">

        <?php $this->renderWidget('SideMenu'); ?>

        <?php $this->renderWidget('BreadCrumbs'); ?>

        <?php if(!$this->alterPageName): ?>
            <div class="col-md-6 col-sm-8">
                <h1><?php echo $this->pageName;?></h1>
            </div>
        <?php else: ?>
            <?php $this->renderWidget('AlterPageName'); ?>
        <?php endif; ?>

        <?php $this->renderWidget('TopButtons'); ?>

        <?php echo $content; ?>

    </div>

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

<?php $this->partial('_footer');?>