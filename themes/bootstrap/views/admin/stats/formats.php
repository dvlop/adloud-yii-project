<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 11.07.14
 * Time: 18:47
 * @var application\modules\admin\controllers\StatsController $this
 * @var array $formats
 * @var Object $block
 */
?>

<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title">
            Статистика блока "<?php echo $block->name; ?>" (ID: <?php echo $block->id; ?>)
        </h3>
    </div>

    <div class="panel-body">
        <table id="formats-stats" class="table table-striped table-hover">

            <thead>
                <tr>
                    <th>Формат</th>
                    <th>Показы</th>
                    <th>Клики</th>
                    <th>CTR</th>
                    <th>Статус</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($formats as $format): ?>
                    <tr>
                        <td><?php echo $format->name; ?></td>
                        <td><?php echo $format->shows; ?></td>
                        <td><?php echo $format->clicks; ?></td>
                        <td><?php echo $format->ctr; ?> %</td>
                        <td>
                            <input
                                data-list="<?php echo $format->name; ?>"
                                data-url="<?php echo Yii::app()->createUrl('admin/block/changeStatus', ['id' => $block->id]); ?>"
                                type="checkbox" name="switch-format-status"
                                <?php if($format->status) echo 'checked'; ?>
                            >
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</div>

<?php Yii::app()->clientScript->registerScript('blockStats', '
    FormatsStat.init();
'); ?>