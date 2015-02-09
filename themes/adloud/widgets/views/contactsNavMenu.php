<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 09.06.14
 * Time: 14:56
 * @var array $menu
 * @var string $class
 * @var string $navMenu
 */
?>

<?php if($menu): ?>
    <?php if($navMenu) echo '<nav class="'.$navMenu.'" role="navigation">'; ?>

    <ul class="<?php echo $class; ?>">
        <li class="nav-item nav-logo">
            <a class="nav-link" href="<?php echo Yii::app()->createUrl('index/index'); ?>" role="banner">
                <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/images/logo.png"/>
            </a>
        </li>
        <?php foreach($menu as $elem): ?>
            <li<?php if(isset($elem['liId']) && $elem['liId']) echo ' id="'.$elem['liId'].'"'; ?><?php if(isset($elem['liClass']) && $elem['liClass']) echo ' class="'.$elem['liClass'].'"'; ?>>
                <?php $url = strpos($elem['url'], 'http://') !== false ? $elem['url'] : Yii::app()->createUrl($elem['url']); ?>
                <a href="<?php echo $url; ?>"<?php if(isset($elem['id']) && $elem['id']) echo ' id="'.$elem['id'].'"'; ?><?php if(isset($elem['class']) && $elem['class']) echo ' class="'.$elem['class'].'"'; ?>>
                    <?php echo $elem['name']; ?>
                </a>
            </li>
        <?php endforeach; ?>
        <li class="nav-item nav-login pull-right">
            <a href="<?php echo Yii::app()->createUrl('index/auth'); ?>" class="nav-link">
                <?php echo Yii::t('contacts', 'Войти'); ?>
            </a>
        </li>
    </ul>

    <?php if($navMenu) echo '</nav>'; ?>
<?php endif; ?>