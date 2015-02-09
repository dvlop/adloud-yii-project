<?php
/**
 * Created by PhpStorm.
 * User: JanGolle
 * Date: 24.07.14
 * Time: 10:50
 */
?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/pages/blockAdsStats.js', CClientScript::POS_END); ?>

    <div class="panel panel-blue margin-bottom-40">

        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon-user"></i>Статистика объявлений по блоку</h3>
        </div>

        <div class="panel-body">
            <?php if($ads): ?>
                <table class="table table-striped table-hover" id="users-list">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Просмотры</th>
                        <th>Клики</th>
                        <th>CTR</th>
                        <th>Доход</th>
                        <th>Трансляция</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach($ads as $ad): ?>
                        <tr>
                            <td><?php echo $ad['id']; ?></td>
                            <td><?php echo $ad['description']; ?></td>
                            <td><?php echo $ad['shows']; ?></td>
                            <td><?php echo $ad['clicks']; ?></td>
                            <td><?php echo $ad['ctr']; ?></td>
                            <td><?php echo $ad['costs']; ?></td>
                            <td>
                                <input
                                    data-id="<?php echo $ad['id']; ?>"
                                    data-url="<?php echo Yii::app()->createUrl('admin/block/changeAdsStatus', ['blockId' => $blockId]); ?>"
                                    type="checkbox" name="switch-ads-status"
                                    <?php if($ad['status'] == 1) echo 'checked'; ?>
                                />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else: ?>
                В данном блоке не транслировались объявления
            <?php endif; ?>
        </div>

    </div>
<?php Yii::app()->clientScript->registerScript('blockAdsStats', '
    BlockAdsStats.init({});
'); ?>