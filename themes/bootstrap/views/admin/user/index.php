<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 28.05.14
 * Time: 13:22
 * @var AdminController $this
 * @var UsersForm $model
 * @var UserBaseInfoModel $baseInfoModel
 */
?>
<div class="panel panel-blue margin-bottom-40">

    <div class="panel-heading">
        <h3 class="panel-title"><i class="icon-user"></i> Информация о пользователе</h3>
    </div>

    <div class="panel-body">

        <div class="row">

            <div class="col-lg-6">

                <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>

                <div class="headline"><h4>Пользовательская информация</h4></div>

                <div class="form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model,'email', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeEmailField($model,'email', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('fullName')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'fullName', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'fullName', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($model, 'registerDate', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $model->registerDate; ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($model, 'lastLogin', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $model->lastLogin; ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('webmoneyWmz')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'webmoneyWmz', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'webmoneyWmz', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('isq')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'isq', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'isq', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('skype')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'skype', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'skype', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('webmoneyWmr')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'webmoneyWmr', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'webmoneyWmr', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('yandexId')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'yandexId', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'yandexId', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('qiwiId')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'qiwiId', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'qiwiId', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group <?php if($model->hasErrors('invite')) echo 'has-error';?>">
                    <?php echo CHtml::activeLabel($model, 'invite', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo CHtml::activeTextField($model, 'invite', ['class' => 'form-control']); ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo CHtml::activeLabel($model, 'role', ['class'=>'col-lg-3 control-label']); ?>
                    <div class="col-lg-9">
                        <?php echo $model->role; ?>
                    </div>
                </div>

                <div class="row">
                    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn-u btn-block')); ?>
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



    </div>

</div>