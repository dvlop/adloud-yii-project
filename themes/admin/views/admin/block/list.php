<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.09.14
 * Time: 16:18
 * @var \application\modules\admin\controllers\BlockController $this
 * @var \stdClass[] $blocks
 */
?>

<div class="row">

</div>

<div class="row">

    <table class="display table table-bordered table-striped datatable" id="blocks-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>ID сайта</th>
                <th>Категории</th>
                <th>Тип</th>
                <th>Дата</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($blocks as $block): ?>
                <tr>
                    <?php $this->renderPartial('themes.'.Yii::app()->theme->name.'.views.admin.block._blockTableRow', ['block' => $block]); ?>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>

<?php Yii::app()->clientScript->registerScript('', '
    Blocks.init();
'); ?>