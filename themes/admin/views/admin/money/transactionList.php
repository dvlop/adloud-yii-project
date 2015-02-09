<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 23.09.14
 * Time: 15:00
 * @var \application\modules\admin\controllers\MoneyController $this
 * @var \application\models\Transactions $model
 * @var \stdClass[] $columns
 */
?>

    <div class="row">

        <table class="display table table-bordered table-striped datatable" id="transactions-table" data-ajax="<?php echo Yii::app()->createUrl('admin/money/transactionList'); ?>" data-filter=".filters">

            <thead>

                <tr class="filters">
                    <?php foreach($columns as $column): ?>
                        <td>
                            <?php if($column->search): ?>
                                <input type="text" placeholder="<?php echo $column->text; ?>" />
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <tr>
                    <?php foreach($columns as $column): ?>
                        <th><?php echo $column->text; ?></th>
                    <?php endforeach; ?>
                </tr>

            </thead>

            <tbody>

            </tbody>

        </table>

    </div>


<?php Yii::app()->clientScript->registerScript('transactionList', '
    Transactions.init();
'); ?>