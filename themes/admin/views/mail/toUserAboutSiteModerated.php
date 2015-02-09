<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.08.14
 * Time: 18:27
 * @var \application\components\ControllerBase $this
 * @var \application\models\Sites $model
 * @var string $title
 * @var string $reason
 */
?>

<center><h3><?php echo $title; ?></h3></center>

<?php if($reason): ?>
    <center><h3>Причина: <?php echo $reason; ?></h3></center>
<?php endif; ?>