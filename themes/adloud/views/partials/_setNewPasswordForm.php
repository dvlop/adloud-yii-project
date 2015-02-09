<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 20.06.14
 * Time: 12:09
 * @var UserRestorePasswordForm $model
 * @var ControllerBase $this
 */
?>

<?php
    $img = Yii::app()->theme->baseUrl.'/assets/images';
    if(!isset($model)) $model = new UserSetNewPasswordForm();
?>

<h1><?php echo $this->pageName; ?></h1>

<?php echo CHtml::beginForm(Yii::app()->createUrl('index/setNewPassword'), 'post', [
    'class' => 'new-pass-form',
    'id' => get_class($model).'_form'
]); ?>

    <?php echo CHtml::activeHiddenField($model, 'email'); ?>

    <div class="form-group text-center">
        <img src="<?php echo $img; ?>/adloud/new-pass/new-pass.png" />
    </div>

    <div class="form-group">
        <?php echo CHtml::activePasswordField($model, 'password' ,[
            'placeholder' => $model->getAttributeLabel('password'),
            'class' => 'form-control flat',
            'required' => true,
        ]); ?>
        <span class="input-icon fui-lock"></span>
    </div>

    <div class="form-group">
        <?php echo CHtml::activePasswordField($model, 'password2' ,[
            'placeholder' => $model->getAttributeLabel('password2'),
            'class' => 'form-control flat',
            'required' => true,
        ]); ?>
        <span class="input-icon glyphicon glyphicon-repeat"></span>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-block adloud_btn">Сохранить и войти<i class="fa fa-angle-right fa-2x pull-right"></i></button>
    </div>

<?php echo CHtml::endForm(); ?>