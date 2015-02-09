<?php
/**
* Created by PhpStorm.
* User: rem
* Date: 08.07.14
* Time: 16:10
* @var \application\modules\advertiser\controllers\ListsController $this
* @var ListsSitesForm $model
* @var array $lists
*/
?>

<div class="col-sm-12">

    <?php $this->renderPartial('partials/_listsForm', ['model' => $model]); ?>

    <table class="table table-striped table-hover adloud_table black_and_white">

        <thead>
            <tr>
                <th colspan="1" class="sites_list_type_name"><?php echo Yii::t('lists', 'Название'); ?></th>
                <th colspan="5"><?php echo Yii::t('lists', 'Тип списка'); ?></th>
                <th colspan="5"><?php echo Yii::t('lists', 'Количество площадок'); ?></th>
                <th colspan="1"></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($lists as $list): ?>
                <tr id="list-id-<?php echo $list->id; ?>">
                    <td colspan="1" class="sites_list_type_name">
                        <?php echo $list->name; ?>
                    </td>
                    <td colspan="5"><?php echo $list->type; ?></td>
                    <td colspan="5"><?php echo $list->campaignsCount; ?></td>
                    <th colspan="1" class="sites_list_edit">

                        <!--
                            'data-toggle' => 'tooltip',
                            'data-tooltip-style' => 'light',
                            'data-placement' => 'bottom',
                            'data-original-title' => 'Настройки рекламного блока',
                        -->
                        <span
                            class="fui-new auto-link"
                            data-url="<?php echo Yii::app()->createUrl('advertiser/lists/manage', ['id' => $list->id]); ?>"
                            data-toggle="tooltip"
                            data-tooltip-style="light"
                            data-placement="bottom"
                            data-original-title="<?php echo Yii::t('lists', 'Редактировать список'); ?>"
                        ></span>

                        <span
                            class="fui-trash auto-ajax delete-button cursor-pointer"
                            data-url="<?php echo Yii::app()->createUrl('advertiser/lists/remove', ['id' => $list->id]); ?>"
                            data-update="#list-id-<?php echo $list->id; ?>"
                            data-list="remove=1"
                            data-confirm="<?php echo Yii::t('lists', 'Вы действительно хотите удалить список'); ?> '<?php echo $list->name; ?>'"
                            data-toggle="tooltip"
                            data-tooltip-style="light"
                            data-placement="bottom"
                            data-original-title="<?php echo Yii::t('lists', 'Удалить список'); ?>"
                        ></span>

                    </th>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>