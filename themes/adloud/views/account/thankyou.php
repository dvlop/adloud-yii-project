<?php
/**
 * @var AccountController $this
 */
?>

<?php $img = Yii::app()->theme->baseUrl.'/assets/images'; ?>

<div class="row">
    <section class="thank-you">
        <div class="col-sm-2">
            <a href="<?php Yii::app()->createUrl('index/index'); ?>" class="logo">
                <img src="<?php echo $img ?>/adloud/logo.png"/>
            </a>
        </div>
        <div class="col-sm-8 text-center">
            <h1>
                <?php echo Yii::t('thankyou', 'Спасибо за регистрацию!'); ?>
            </h1>
        </div>
        <div class="col-sm-12 text-center">
            			<span class="fui-check-inverted">
            			</span>
        </div>
        <div class="col-sm-12 text-center">
            <p>
                <?php echo Yii::t('thankyou', 'Мы рады что вы с нами!'); ?>
            </p>
            <a href="<?php echo Yii::app()->createUrl('webmaster/site/list'); ?>" class="btn btn-inverse"><?php echo Yii::t('thankyou', 'Начать работу'); ?></a>
        </div>
    </section>
</div>