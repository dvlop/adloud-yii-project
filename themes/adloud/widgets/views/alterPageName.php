<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.05.14
 * Time: 16:02
 * @var array $names
 */
?>

<?php foreach($names as $headerElem): ?>

    <?php $containers = array_reverse($headerElem['containers']); ?>
    <?php $elements = array_reverse($headerElem['elements']); ?>

    <?php foreach($headerElem['containers'] as $name=>$container): ?>
        <?php if(!is_string($name) || $name === '') $name = 'div' ?>
        <<?php echo $name; ?>
        class="<?php echo $container; ?>">
    <?php endforeach; ?>

    <?php foreach($headerElem['headers'] as $name=>$header): ?>
        <?php if(!is_string($name) || $name === '') $name = 'div' ?>
        <<?php echo $name; ?>
            class="<?php if(isset($header['class'])) echo $header['class']; ?>"
            <?php if(isset($header['id'])) echo 'id="'.$header['id'].'"'; ?>
            <?php if(isset($header['url'])) echo 'href="'.Yii::app()->createUrl($header['url']).'"'; ?>>
                <?php echo $header['name']; ?>
        </<?php echo $name; ?>>
    <?php endforeach; ?>

    <?php foreach($headerElem['elements'] as $name=>$elem): ?>
        <?php if(!is_string($name) || $name === '') $name = 'div' ?>
        <<?php echo $name; ?>
            class="<?php if(isset($elem['class'])) echo $elem['class']; ?>"
            <?php if(isset($elem['id'])) echo 'id="'.$elem['id'].'"'; ?>
            <?php if(isset($elem['url'])) echo 'href="'.Yii::app()->createUrl($elem['url']).'"'; ?>>
                <?php if(isset($elem['name'])) echo $elem['name']; ?>
    <?php endforeach; ?>

    <?php foreach($elements as $name=>$element): ?>
        <?php if(!is_string($name) || $name === '') $name = 'div' ?>
        </<?php echo $name; ?>>
    <?php endforeach; ?>

    <?php foreach($containers as $name=>$container): ?>
        <?php if(!is_string($name) || $name === '') $name = 'div' ?>
        </<?php echo $name; ?>>
    <?php endforeach; ?>

<?php endforeach; ?>