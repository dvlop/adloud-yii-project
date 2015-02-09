<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 04.03.14
 * Time: 23:29
 */
?>
<!-- Блок перенести в форму, когда понадобится этот модал -->
<!--<div class="form-group" --><?php //if($model->hasErrors('blockList')) echo 'has-error';?><!--">-->
<!--    --><?php //echo CHtml::activeLabel($model,'blockList', array('class'=>'col-lg-3 control-label')); ?>
<!--    <div class="col-lg-9">-->
<!--        <div class="input-group">-->
<!--                            <span class="input-group-btn">-->
<!--                                <button-->
<!--                                    data-toggle="modal"-->
<!--                                    data-target="#modalSpecifyBlockList"-->
<!--                                    type="button" class="btn btn-u">Указать...</button>-->
<!--                            </span>-->
<!--            --><?php //echo CHtml::activeTextField($model,'blockList', array('class' => 'form-control', 'readonly' => true)); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- Конец блока -->

<!-- Modal -->
<div class="modal fade" id="modalSpecifyBlockList" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Укажите параметры блок-листов</h4>
            </div>
            <div class="modal-body">
                Контент...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="">Подтвердить</a>
            </div>
        </div>
    </div>
</div>