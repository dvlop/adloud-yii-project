<?
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:28 PM
 */
?>
<!--=== Breadcrumbs ===-->
<ul class="breadcrumb">
    <?php if(($this->getController()->getId() == 'site' && $this->getController()->action->id != 'index') || $this->getController()->getId() != 'site'):?>
        <?php foreach($this->getController()->breadcrumbs AS $link => $name):?>
            <?php $active = '';?>
            <?php $linkString = '<a href="' . $link . '">' . $name . '</a>';?>
            <?php if(end($this->getController()->breadcrumbs) == $name):?>
                <?php $active = 'active';?>
                <?php $linkString = $name;?>
            <?php endif;?>

            <li class="<?php echo $active;?>"><?php echo $linkString;?></li>
        <?php endforeach;?>
    <?php endif;?>
</ul>
<!--=== End Breadcrumbs ===-->