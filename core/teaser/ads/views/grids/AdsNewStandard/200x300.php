<?php
/**
 * @var $this \ads\AdsRenderer
 */
?>

<div class="adloud-teaser-block adloud-block-200x300">

  <a href="#" class="adloud_logo"></a>
  <a href="#" class="refresh">
    <span class="glyphicon glyphicon-repeat"></span>
  </a>

<?php foreach($this->ads as $ads):?>
    <?= $ads->render(); ?>
<?php endforeach?>

</div>
<div class="adloud-load-check"></div>