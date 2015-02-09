<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 13.06.14
 * Time: 14:57
 * @var ControllerBase $this
 */
?>

<div class="notice-wrap">

    <div class="notice bad-msg form-errors-msg">
        <div class="notice-text">
            <span class="close-notice">&times;</span>
            <p></p>
        </div>
        <div class="notice-icon-bad">
            <div>
                <span>!</span>
            </div>
            <div class="arrow">
            </div>
        </div>
    </div>

</div>

<?php if($this->model && $this->model->hasErrors()){
    Yii::app()->clientScript->registerScript(get_class($this->model).'-form-errors', '
        Main.handleFormErrors(\''.$this->parseError($this->model->errors, '', '', true).'\', $(\'#'.get_class($this->model).'_form\'));
    ');
} ?>

<?php if(Yii::app()->user->hasFlash('success')){
    Yii::app()->clientScript->registerScript('showMessage', '
        Main.showMessage(\''.Yii::app()->user->getFlash('success').'\');
    ');
} ?>

<?php if(Yii::app()->user->hasFlash('error')){
    Yii::app()->clientScript->registerScript('showError', '
        Main.showError(\''.Yii::app()->user->getFlash('error').'\');
    ');
} ?>