<?
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 1/30/14
 * Time: 10:28 PM
 */
?>
<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo $this->getController()->pageName;?></h1>
        <ul class="pull-right breadcrumb">
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
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->