<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 16.02.14
 * Time: 21:48
 */

?>

<table class="table table-striped table-hover">
    <thead>
    <tr>Рекламный блок заголовки: название, показы клики, цтр, стоимость клика средняя, зароботок, операции
        <th>Название</th>
        <th>Показы</th>
        <th>Клики</th>
        <th>CTR (%)</th>
        <th>Заработок</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
    <?php if(empty($blocksList)):?>
        <tr>
            <td colspan="7">
                <div class="alert alert-danger" style="text-align: center;">
                    Нет ни одного блока! Но вы можете его добавить, нажав на кнопку ниже
                </div>
            </td>
        </tr>
    <?php else:?>
        <?php foreach($blocksList AS $block):?>
            <tr class="<?php echo !empty($createdBlock) && $createdBlock == $block['id'] ? 'active' : '';?>">
                <td>
                    <?php echo $block['description'];?>
                </td>
                <td><?php echo $block['shows'] > 0 ? $block['shows'] : 0;?></td>
                <td><?php echo $block['clicks'] > 0 ? $block['clicks'] : 0;?></td>
                <td><?php echo $block['shows'] > 0 ? round($block['clicks'] / $block['shows'], 3) * 100  : 0;?></td>
                <td><?php echo $block['blockIncome'] > 0 ? $block['blockIncome'] : 0;?> $</td>
                <td>
                    <a
                        href="<?php echo Yii::app()->createUrl("webmaster/block/", array('siteId' => $siteId, 'id'=>$block['id']));?>"
                        data-tooltip="tooltip" data-placement="top" title="Редактировать рекламный блок"
                        class="btn btn-default btn-xs">
                        <i class="icon-edit"></i>
                    </a>
                    <button data-toggle="modal"
                            data-target="#modalBlockDelete"
                            class="btn btn-danger btn-xs btnBlockDelete"
                            data-url="<?php echo Yii::app()->createUrl("webmaster/blockDelete", array('id'=>$block['id'], 'siteId' => $siteId));?>"
                            data-tooltip="tooltip" data-placement="top" title="Удалить рекламный блок"
                        ><i class="icon-remove"></i></button>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    </tbody>
</table>

