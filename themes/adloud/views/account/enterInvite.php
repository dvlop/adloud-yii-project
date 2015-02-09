<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 27.05.14
 * Time: 13:48
 * @var AccountController $this
 * @var UserInfoModel $model
 */
?>
<?php echo CHtml::beginForm('', 'post', ['id' => 'beta-key', 'class' => 'beta-key-form text-center']); ?>

    <fieldset>

        <legend>Введите ваш бета-ключ</legend>
        <div class="form-group">
            <span class="fui-check"></span>
            <span class="fui-check"></span>
            <span class="fui-check final-step"></span>
        </div>
        <div class="form-group">
            <?php echo CHtml::activeTextField($model, 'invite', [
                'class' => 'form-control flat',
                'value' => '',
                'placeholder' => $model->getAttributeLabel('invite'),
                'required' => true,
            ]); ?>
            <span class="input-icon fui-lock"></span>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-block adloud_btn signin-btn">
                Активировать
                <span class="pull-right"><i class="fa fa-angle-right"></i></span>
            </button>
        </div>

    </fieldset>

<?php echo CHtml::endForm(); ?>