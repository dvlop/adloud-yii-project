<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 24.03.14
 * Time: 22:12
 */
$cs = Yii::app()->clientScript;
$cs
    ->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/admin.js', CClientScript::POS_END)
?>

<?php $this->renderPartial('themes.bootstrap.views.layouts._header');?>

    </div>
    <div>
        <?php $this->widget('themes.bootstrap.widgets.SideMenu'); ?>
    </div>

<div id="contentContainer" class="container">
    <div class="row margin-bottom-30">
        <div class="col-md-12 mb-margin-bottom-30">
            <?php echo $content; ?>
        </div>
    </div>

<?php $this->renderPartial('themes.bootstrap.views.layouts._footer');?>