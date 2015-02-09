<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 15.08.14
 * Time: 13:52
 * @var \application\components\ControllerBase $this
 * @var array $data
 */
?>


<div class="adloud-blocks-js-code-bg hide" id="main-modal-window">
    <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.partials._mainModalContent', ['data' => $data]); ?>
</div>