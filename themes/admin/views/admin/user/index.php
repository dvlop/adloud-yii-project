<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 29.09.14
 * Time: 14:18
 * @var \application\modules\admin\controllers\UserController $this
 * @var \UsersForm $user
 * @var \UserBaseInfoModel $baseInfoModel
 */
?>

<div class="row">

    <div class="col-lg-6">

        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>

            <div class="headline"><h4>Пользовательская информация</h4></div>

            <div class="form-group <?php if($user->hasErrors('email')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user,'email', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeEmailField($user,'email', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('fullName')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'fullName', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'fullName', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo CHtml::activeLabel($user, 'registerDate', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo $user->registerDate; ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('lastLogin')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'lastLogin', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo $user->lastLogin; ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('webmoneyWmz')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'webmoneyWmz', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'webmoneyWmz', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('isq')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'isq', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'isq', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('skype')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'skype', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'skype', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('webmoneyWmr')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'webmoneyWmr', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'webmoneyWmr', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('yandexId')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'yandexId', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'yandexId', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('qiwiId')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'qiwiId', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'qiwiId', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group <?php if($user->hasErrors('invite')) echo 'has-error';?>">
                <?php echo CHtml::activeLabel($user, 'invite', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo CHtml::activeTextField($user, 'invite', ['class' => 'form-control']); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo CHtml::activeLabel($user, 'role', ['class'=>'col-lg-3 control-label']); ?>
                <div class="col-lg-9">
                    <?php echo $user->role; ?>
                </div>
            </div>

            <div class="row">
                <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-success btn-lg btn-block']); ?>
            </div>

        <?php echo CHtml::endForm(); ?>

    </div>

    <div class="col-lg-6">

        <div class="headline"><h4>Основные данные</h4></div>

        <?php if($baseInfoModel): ?>

            <form class="form-horizontal">

                <div class="form-group">
                    <?php echo CHtml::activeLabel($baseInfoModel, 'siteUrl', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $baseInfoModel->siteUrl; ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($baseInfoModel, 'desiredProfit', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $baseInfoModel->desiredProfit; ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($baseInfoModel, 'statLink', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $baseInfoModel->statLink; ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($baseInfoModel, 'statLogin', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $baseInfoModel->statLogin; ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($baseInfoModel, 'statPassword', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $baseInfoModel->statPassword; ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($baseInfoModel, 'description', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $baseInfoModel->description; ?>
                    </div>
                </div>

            </form>

        <?php else: ?>

            <h4>Этот пользователь не указал своих основныхданных</h4>

        <?php endif; ?>

    </div>


</div>



<?php Yii::app()->clientScript->registerScript('adminUserPage', '
    User.init();
'); ?>