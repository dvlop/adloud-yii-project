<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 04.09.14
 * Time: 13:37
 * To change this template use File | Settings | File Templates.
 */
?>

    <div class="col-sm-4 col-md-3 pull-right">
        <button class="btn adloud_btn btn-block toggle-add-target"><span class="input-icon fui-plus pull-left"></span>Добавить ретаргетинг</button>
        <?php echo CHtml::beginForm('', 'post', ['id' => 'create_campaign', 'class' => 'add-target']); ?>
            <h5 class="add-target-form-title">Создание списка ретаргетинга</h5>
            <label for="new-target-list-name">Введите название списка</label>
            <?php echo CHtml::activeTextField($model, 'name', ['class' => 'form-control target-title', 'required' => 'true']); ?>
            <span class="adloud_note"><?php echo Yii::t('main', 'Осталось символов'); ?>: <span class="title_adloud_note">30</span></span>
            <fieldset>
            <legend>Выберите категорию вашего таргетинга:</legend>
            <div class="add-target-categories">
            <select name="<?php echo $model->getModelName(); ?>[category_id]" class="selectpicker">
                <?php foreach($model->getCategoriesList() as $cat): ?>
                    <option
                        value="<?php echo $cat->id; ?>">
                        <?php echo $cat->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            </div>
            </fieldset>
            <button class="btn btn-block adloud_btn" type="submit"><span class="fui-check"></span>Создать новый список</button>
        <?php echo CHtml::endForm(); ?>
    </div>

    <div class="col-sm-12">
        <table class="table table-striped table-hover adloud_table target-table">

            <thead>
            <tr>
                <th>
                    <label class="checkbox toggle-all" for="all-table-checkbox">
                        <input type="checkbox" class="adloud_checkbox" id="all-table-checkbox" data-toggle="checkbox">
                    </label>
                </th>
                <th>
                    ID
                </th>
                <th class="text-left">
                    Название списка
                </th>
                <th>
                    Категория
                </th>
                <th>
                    Пользователи
                </th>
            </tr>
            </thead>

            <tbody>
            <?php if($targetList): ?>
                <?php foreach($targetList AS $target): ?>
                    <tr>
                        <td>
                            <label class="checkbox" for="table-checkbox-1">
                                <input type="checkbox" class="adloud_checkbox" id="table-checkbox-1" data-toggle="checkbox">
                            </label>
                        </td>
                        <td>
                            <?=$target->id;?>
                        </td>
                        <td class="text-left">
                            <span class="target-list-name"><?=$target->name;?></span>
                            <div class="target-table-btn-bar">
                                <button
                                    data-url="<?php echo Yii::app()->createUrl('advertiser/target/codemodal', ['id' => $target->id]); ?>"
                                    class="fa fa-code show-block-code"
                                    data-toggle="tooltip"
                                    data-tooltip-style="light"
                                    data-placement="bottom"
                                    data-original-title="Показать код"
                                    ></button>
                                <a
                                    href="<?php echo Yii::app()->createUrl('advertiser/target/delete', ['id' => $target->id]); ?>"
                                    class="fui-trash delete-button target-delete"
                                    data-toggle="tooltip"
                                    data-tooltip-style="light"
                                    data-placement="bottom"
                                    data-original-title="Удалить список"
                                    ></a>
                            </div>
                        </td>
                        <td>
                            <?=$target->category->name;?>
                        </td>
                        <td>
                            <?=$target->shows;?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else:?>
                <tr>
                    <td  colspan="10">
                        <div class="alert alert-danger" style="text-align: center;">
                            Список таргет-листов пуст.
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

    </div>

<?php Yii::app()->clientScript->registerScript('targetList', '
    TargetList.init({
        clipboardPath: "'.Yii::app()->theme->baseUrl.'/assets/plugins/zeroclipboard/dist",
        showModal: '.(int)$showModal.'
    });
'); ?>