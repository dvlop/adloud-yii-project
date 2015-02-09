<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 19.09.14
 * Time: 16:21
 * @var \application\modules\admin\controllers\BlockController $this
 * @var \stdClass $block
 */
?>

<!--<td><?php echo CHtml::link($block->id, Yii::app()->createUrl('admin/block/formatTestList', ['format' => $block->id])); ?></td>-->
<td><?php echo $block->id; ?></td>
<!--<td><?php echo CHtml::link($block->name, Yii::app()->createUrl('admin/stats/blocks', ['blockId' => $block->id])); ?></td>-->
<td><?php echo $block->name; ?></td>
<td><?php echo $block->siteId; ?></td>
<td><?php echo $block->categories; ?></td>
<!--<td><?php echo CHtml::link($block->type, Yii::app()->createUrl('admin/block/formatTestList', ['format' => $block->type])); ?></td>-->
<?php echo $block->type; ?>
<td><?php echo $block->date; ?></td>
<td>
    <i
        class="icon-zoom-in auto-link"
        data-url="<?php echo Yii::app()->createUrl('admin/stats/formats', ['blockId' => $block->id]); ?>"
    ></i>
</td>
