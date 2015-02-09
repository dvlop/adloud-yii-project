<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 09.06.14
 * Time: 14:56
 * @var array $menu
 * @var string $class
 * @var string $container
 * @var string $aClass
 */
?>

<?php if($menu): ?>
    <<?php echo $container; ?> class="<?php echo $class; ?>">
        <?php foreach($menu as $elem): ?>
            <a class="<?php echo $elem['aClass']; ?>" href="<?php echo $elem['url']; ?>">
                <i class="<?php echo $elem['liClass']; ?>"></i>
            </a>
        <?php endforeach; ?>
    </<?php echo $container; ?>>
<?php endif; ?>
