<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 31.07.14
 * Time: 17:37
 * @var \application\components\ControllerBase $this
 * @var array $menu
 */
?>

<li class="footer-nav-item footer-logo">
    <a class="footer-nav-link" href="<?php echo Yii::app()->createUrl('index/index'); ?>" role="banner">
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/images/footer-logo.png"/>
    </a>
</li>

<?php foreach($menu as $item): ?>
    <?php $url = strpos($item['url'], 'http://') !== false ? $item['url'] : Yii::app()->createUrl($item['url']); ?>
    <li class="<?php echo $item['liClass']; ?>">
        <a class="<?php echo $item['aClass']; ?>" href="<?php echo $url; ?>">
            <?php echo $item['name']; ?>
        </a>
    </li>
<?php endforeach; ?>