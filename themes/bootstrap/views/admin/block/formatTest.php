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
            Остановить/начать сплит тест
        </h3>
    </div>
    <input
        data-url="<?php echo Yii::app()->createUrl('admin/block/changeFormatSplitTestStatus', ['id' => htmlspecialchars($_GET['id']), 'status' => $status ? 0 : 1]); ?>"
        type="checkbox" name="switch-format-status"
        <?php if($status) echo 'checked'; ?>
        >
</div>
    <div class="panel panel-blue margin-bottom-40">

        <div class="panel-heading">
            <h3 class="panel-title">
                Статистика формата
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
                <?php foreach($types as $name=>$stats): ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $stats['shows']; ?></td>
                        <td><?php echo $stats['clicks']; ?></td>
                        <td><?php echo $stats['ctr']; ?> %</td>
                        <td>
                            <input
                                data-list="<?php echo $name; ?>"
                                data-url="<?php echo Yii::app()->createUrl('admin/block/AddSplitTestType', ['type' => $name, 'format' => $format, 'state' => $stats['status'] ? 0 : 1, 'id'=>htmlspecialchars($_GET['id'])]); ?>"
                                type="checkbox" name="switch-format-status"
                                <?php if($stats['status']) echo 'checked'; ?>
                                >
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>
<?php Yii::app()->clientScript->registerScriptFile(\Yii::app()->theme->baseUrl.'/assets/js/pages/formatsStat.js'); ?>
<?php Yii::app()->clientScript->registerScript('blockStats', '
    FormatsStat.init();
'); ?>
