<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 11.07.14
 * Time: 18:47
 * @var application\modules\admin\controllers\StatsController $this
 * @var array $blocks
 * @var Pagination $pages
 */
?>

<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title">
            Список блоков
        </h3>
    </div>

    <div class="panel-body">
        <table id="formats-stats" class="table table-striped table-hover">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Сайт</th>
                    <th>Категории</th>
                    <th>Тип</th>
                    <th>Дата</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($blocks as $block): ?>
                    <tr>
                        <td><?php echo CHtml::link($block->id, Yii::app()->createUrl('admin/block/formatTestList', ['format' => $block->id])); ?></td>
                        <td><?php echo CHtml::link($block->name, Yii::app()->createUrl('admin/stats/blocks', ['blockId' => $block->id])); ?></td>
                        <td><?php echo $block->siteId; ?></td>
                        <td><?php echo $block->categories; ?></td>
                        <td><?php echo CHtml::link($block->type, Yii::app()->createUrl('admin/block/formatTestList', ['format' => $block->type])); ?></td>
                        <td><?php echo $block->date; ?></td>
                        <td><i
                                class="icon-zoom-in auto-link"
                                data-url="<?php echo Yii::app()->createUrl('admin/stats/formats', ['blockId' => $block->id]); ?>"
                            ></i></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <?php $this->widget('themes.'.Yii::app()->theme->name.'.widgets.LinkPager', ['pages'=>$pages]); ?>

</div>

