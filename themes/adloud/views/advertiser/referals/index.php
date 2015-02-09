<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 25.07.14
 * Time: 17:49
 */
?>
    <div class="col-sm-12 referal">
        <div class="col-sm-12 ref_url">
            <div class="row">
                <p class="ref_url_title col-md-3 col-sm-12"><?php echo Yii::t('webmaster_referals', 'Реферальная ссылка'); ?></p>
                <div class="col-sm-6 ref_url_input">
                    <div class="row">
                        <input type="text" id="ref-link-text" value="<?php echo $model->getReferalLink(); ?>" class="form-control flat" name="ref_url"/>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button class="btn adloud_btn btn-block btn-embossed copy_ref_url" data-clipboard-target="ref-link-text" id="copy-btn">
                        <span class="input-icon fui-exit pull-left"></span><?php echo Yii::t('webmaster_referals', 'Скопировать в буфер'); ?>
                    </button>
                </div>
            </div>
        </div>
        <legend><?php echo Yii::t('webmaster_referals', 'Статистика'); ?></legend>
        <?php if($referals): ?>
            <table class="table table-striped table-hover adloud_table referal_stat">
                <thead>
                <tr>
                    <th class="col-sm-2"><?php echo Yii::t('webmaster_referals', 'Имя пользователя'); ?></th>
                    <th class="col-sm-6"><?php echo Yii::t('webmaster_referals', 'Дата регистрации'); ?></th>
                    <th class="col-sm-4"><?php echo Yii::t('webmaster_referals', 'Сумма комиссионных'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($referals as $ref): ?>
                    <tr>
                        <td><?php echo $ref->getReferalName(); ?></td>
                        <td><time datetime="<?php echo $ref->date; ?>"><?php echo $ref->date; ?></time></td>
                        <td><?php echo $ref->sum; ?>$</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <?php echo Yii::t('webmaster_referals', 'У вас нет реферальных начислений'); ?>
        <?php endif; ?>
    </div>

<?php Yii::app()->clientScript->registerScript('referals', '
    Referals.init({
        clipboardPath: "'.Yii::app()->theme->baseUrl.'/assets/plugins/zeroclipboard/dist"
    });
'); ?>