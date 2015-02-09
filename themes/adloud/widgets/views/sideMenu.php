<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/6/14
 * Time: 12:05 AM
 * @var array $menu
 */
?>

<nav class="navbar menu-nav">
    <div class="col-sm-2 col-md-2 navbar-brand">
        <div class="row">
            <a href="<?php echo Yii::app()->createUrl('advertiser/campaign/list'); ?>">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/images/adloud/logo.png">
            </a>
        </div>
    </div>
    <div class="col-sm-7 col-md-10">
        <div class="row">
            <ul class="nav navbar-nav">
                <?php foreach($menu as $item): ?>
                    <li class="<?php echo $item['class'] ?>">
                        <?php echo CHtml::link($item['name'], Yii::app()->createUrl($item['url'])); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>



