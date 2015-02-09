<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 09.06.14
 * Time: 14:56
 * @var array $menu
 * @var string $class
 * @var string $navMenu
 * @var bool $isBottom
 */
?>

<?php if($menu): ?>
    <?php if($navMenu) echo '<nav class="'.$navMenu.'">'; ?>

    <ul class="<?php echo $class; ?>">
        <?php foreach($menu as $elem): ?>
            <li<?php if(isset($elem['ulId']) && $elem['ulId']) echo ' id="'.$elem['ulId'].'"'; ?><?php if(isset($elem['ulClass']) && $elem['ulClass']) echo ' class="'.$elem['ulClass'].'"'; ?>>
                <?php $url = strpos($elem['url'], 'http://') !== false ? $elem['url'] : Yii::app()->createUrl($elem['url']); ?>
                <a href="<?php echo $url; ?>"<?php if(isset($elem['id']) && $elem['id']) echo ' id="'.$elem['id'].'"'; ?><?php if(isset($elem['class']) && $elem['class']) echo ' class="'.$elem['class'].'"'; ?>>
                    <?php echo $elem['name']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if($isBottom) :?>
        <ul class="bottom-links webmoney">
            <li>
                <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=410792709429" target="_blank"><img src="/images/v_blue_on_white_ru.png" alt="Здесь находится аттестат нашего WM идентификатора 410792709429" border="0"><br><span style="font-size: 9px;"><?php echo Yii::t('landing', 'Проверить аттестат'); ?></span></a>
            </li>
            <li style="vertical-align: top">
                <a href="http://www.megastock.ru/" target="_blank"><img src="/images/acc_blue_on_white_ru.png" alt="www.megastock.ru" border="0"></a>
            </li>
        </ul>
    <?php endif ?>

    <?php if($navMenu) echo '</nav>'; ?>
<?php endif; ?>