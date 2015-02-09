<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/2/14
 * Time: 11:10 PM
 */

$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/workspace.js', CClientScript::POS_END)
?>

<?php $this->renderPartial('/layouts/_header');?>

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
    <?php $this->renderPartial('/layouts/_footer');?>