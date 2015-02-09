<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 09.06.14
 * Time: 14:56
 * @var array $menu
 * @var string $class
 * @var string $ulClass
 */
?>

<?php if($menu): ?>
    <?php if($class) echo '<aside class="'.$class.'">'; ?>

    <ul class="<?php echo $ulClass; ?>">
        <?php foreach($menu as $elem): ?>
            <li class="<?php echo $elem['liClass']; ?>"<?php if(isset($elem['liId']) && $elem['liId']) echo ' id="'.$elem['liId'].'"'; ?>>
                <a href="<?php echo $elem['url']; ?>" class="<?php echo $elem['aClass']; ?>"<?php if(isset($elem['aId']) && $elem['aId']) echo ' id="'.$elem['aId'].'"'; ?>>
                    <span class="<?php echo $elem['spanClass']; ?>"<?php if(isset($elem['spanId']) && $elem['spanId']) echo ' id="'.$elem['spanId'].'"'; ?>></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if($class) echo '</aside>'; ?>
<?php endif; ?>