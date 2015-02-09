<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 11.05.14
 * Time: 17:39
 * @var AccountController $this
 * @var UserInfoModel $model
 */
 ?>

<div class="col-sm-12">

    <?php echo CHtml::beginForm('', 'post', [
        'id' => 'change_profile',
        'class' => 'col-sm-12 form-inline',
        'enctype' => 'multipart/form-data'
    ]); ?>

        <div class="col-sm-6 billing_info">

            <legend><?php echo Yii::t('account', 'Платежные данные'); ?></legend>

            <div class="form-group col-sm-12">
                <div class="col-sm-2">
                    <img class="pay_system" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/images/adloud/webmoney.png">
                </div>
                <div class="col-sm-9">
                    <?php echo CHtml::activeTextField($model, 'wmz', ['class' => 'form-control flat', 'placeholder' => Yii::t('account', 'Номер WMZ кошелька')]) ?>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div class="col-sm-2">
                    <img class="pay_system" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/images/adloud/webmoney.png">
                </div>
                <div class="col-sm-9">
                    <?php echo CHtml::activeTextField($model, 'wmr', ['class' => 'form-control flat', 'placeholder' => Yii::t('account', 'Номер WMR кошелька')]) ?>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div class="col-sm-2">
                    <img class="pay_system yandex_money" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/images/adloud/yandex.png">
                </div>
                <div class="col-sm-9">
                    <?php echo CHtml::activeTextField($model, 'yandex', ['class' => 'form-control flat', 'placeholder' => Yii::t('account', 'Номер кошелька')]) ?>
                </div>
            </div>

        </div>

        <div class="col-sm-6 contacts">

            <div class="form-group user_avatar">

                <div data-provides="fileinput" class="fileinput fileinput-new">

                    <div class="fileinput-new thumbnail" style="width: 115px; height: 115px;border-radius: 50%;">
                        <img src="<?php echo $model->avatar; ?>" alt="" style="border-radius: 50%;">
                    </div>

                    <div id="crop-preview" class="fileinput-preview fileinput-exists thumbnail" style="width: 115px; height: 115px; border-radius: 50%; line-height: 115px;"></div>

                    <div class="upload_img">
                        <span class="btn btn-file">
                            <span class="fileinput-new"><?php echo Yii::t('account', 'Изменить'); ?></span>
                            <span class="fileinput-exists"><?php echo Yii::t('account', 'Изменить'); ?></span>
                            <?php echo CHtml::activeFileField($model, 'image'); ?>
                        </span>
                    </div>

                </div>

            </div>

            <div class="form-group col-sm-12 user_email">
                <div class="col-sm-2 cont_label">
                    <p for="user_email" class="adloud_label"><?php echo Yii::t('account', 'Email:'); ?></p>
                </div>
                <div class="col-sm-9">
                    <p id="user_email" class="adloud_label"><?php echo $model->email; ?></p>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div class="col-sm-2 cont_label">
                    <label class="adloud_label" for="user_name"><?php echo Yii::t('account', 'Name:'); ?></label>
                </div>
                <div class="col-sm-9">
                    <?php echo CHtml::activeTextField($model, 'fullName', ['class' => 'form-control flat']); ?>
                    <span class="input-icon glyphicon glyphicon-pencil"></span>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div class="col-sm-2 cont_label">
                    <label class="adloud_label" for="user_isq"><?php echo Yii::t('account', 'ICQ:'); ?></label>
                </div>
                <div class="col-sm-9">
                    <?php echo CHtml::activeTextField($model, 'isq', ['class' => 'form-control flat']); ?>
                    <span class="input-icon glyphicon glyphicon-pencil"></span>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div class="col-sm-2 cont_label">
                    <label class="adloud_label" for="user_skype"><?php echo Yii::t('account', 'Skype:'); ?></label>
                </div>
                <div class="col-sm-9 user_skype">
                    <span class="input-icon fui-skype"></span>
                    <?php echo CHtml::activeTextField($model, 'skype', ['class' => 'form-control flat']); ?>
                    <span class="input-icon glyphicon glyphicon-pencil"></span>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div class="col-sm-2 cont_label">
                    <label class="adloud_label" for="user_skype"><?php echo Yii::t('account', 'Язык'); ?>:</label>
                </div>
                <div class="col-sm-9 user_skype">
                    <select class="form-control flat" name="UserInfoModel[lang]">
                        <?php foreach($model->getLanguages() as $lang): ?>
                            <option
                                value="<?php echo $lang->value ?>"
                                <?php if($lang->checked) echo 'selected="selected"'; ?>
                            >
                                <?php echo Yii::t('account', $lang->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

        </div>

        <div class="col-sm-6 change_password">

            <div class="col-sm-12">
                <legend><?php echo Yii::t('account', 'Пароль'); ?></legend>
                <div class="form-group col-sm-9 col-sm-offset-2">
                    <?php echo CHtml::activePasswordField($model, 'password', ['class' => 'form-control flat', 'placeholder' => Yii::t('account', 'Текущий пароль')]) ?>
                </div>
                <div class="form-group col-sm-9 col-sm-offset-2">
                    <?php echo CHtml::activePasswordField($model, 'newPassword', ['class' => 'form-control flat', 'placeholder' => Yii::t('account', 'Новый пароль')]) ?>
                </div>
                <div class="form-group col-sm-9 col-sm-offset-2">
                    <?php echo CHtml::activePasswordField($model, 'newPassword2', ['class' => 'form-control flat', 'placeholder' => Yii::t('account', 'Подтверждение пароля')]) ?>
                </div>
                <div class="form-group col-md-5 col-md-offset-4 col-sm-9 col-sm-offset-2">
                    <button id="pass-submit" data-url="<?php echo Yii::app()->createUrl('account/changePassword', ['id' => Yii::app()->user->id]); ?>" class="btn btn-primary btn-block edit_pass" type="submit"><?php echo Yii::t('account', 'Изменить пароль'); ?></button>
                </div>
            </div>

        </div>

        <div id="preview-modal" class="fileinput fileinput-new" data-provides="fileinput" style="display: none;">
            <div id="crop-source" class="thumbnail col-sm-12"></div>
            <span id="crop-done" class="btn btn-primary btn-block btn-embossed btn-inverse"><?php echo Yii::t('account', 'Готово'); ?><span>
        </div>

        <?php echo CHtml::activeHiddenField($model, 'cropParams'); ?>

    <?php echo CHtml::endForm(); ?>

    <button type="submit" class="btn btn-embossed adloud_btn create_new_campaign col-sm-4 col-sm-offset-4 change_pro_submit" onclick="return $('#change_profile').submit();">
        <span class="input-icon fui-check fui-lg pull-left"></span><?php echo Yii::t('account', 'Сохранить изменения'); ?>
    </button>

</div>
<?php Yii::app()->clientScript->registerScript('account', '
    Account.init();
'); ?>