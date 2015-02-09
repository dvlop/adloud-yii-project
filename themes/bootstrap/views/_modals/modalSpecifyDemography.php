<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 04.03.14
 * Time: 23:28
 */
?>
<!-- Блок перенести в форму, когда понадобится этот модал -->
<!--<div class="form-group">-->
<!--    --><?php //echo CHtml::activeLabel($model,'demography', array('class'=>'col-lg-3 control-label')); ?>
<!--    <div class="col-lg-9">-->
<!--        <div class="input-group">-->
<!--                        <span class="input-group-btn">-->
<!--                            <button-->
<!--                                data-toggle="modal"-->
<!--                                data-target="#modalSpecifyDemography"-->
<!--                                type="button" class="btn btn-u">Указать...</button>-->
<!--                        </span>-->
<!--            --><?php //echo CHtml::activeTextField($model,'demography', array('class' => 'form-control', 'readonly' => true)); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- Конец блока -->

<!-- Modal -->
<div class="modal fade" id="modalSpecifyDemography" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Укажите параметры демографии</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="t41">Пол</label>
                        <div class="col-lg-9">
                            <div class="btn-group" data-toggle="buttons" style="width: 100%;">
                                <label class="btn btn-primary active" style="width: 50%;">
                                    <input type="radio" name="options" id="option1"> Мужчины
                                </label>
                                <label class="btn btn-primary" style="width: 50%;">
                                    <input type="radio" name="options" id="option2"> Женшины
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="t42">Возраст, от</label>
                        <div class="col-lg-9">
                            <div class="input-group spinner" data-trigger="spinner">
                                <input type="text" value="21" data-rule="quantity" id="t42" class="form-control text-center">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" class="btn btn-primary" data-spin="up"><i class="icon-sort-up"></i></a>
                                            <a href="javascript:;" class="btn btn-primary" data-spin="down"><i class="icon-sort-down"></i></a>
                                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="t42">Возраст, до</label>
                        <div class="col-lg-9">
                            <div class="input-group spinner" data-trigger="spinner">
                                <input type="text" value="21" data-rule="quantity" id="t42" class="form-control text-center">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" class="btn btn-primary" data-spin="up"><i class="icon-sort-up"></i></a>
                                            <a href="javascript:;" class="btn btn-primary" data-spin="down"><i class="icon-sort-down"></i></a>
                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a href="" class="btn btn-primary" id="">Подтвердить</a>
            </div>
        </div>
    </div>
</div>
